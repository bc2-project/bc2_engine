<?php

/*
   ____  __      __    ___  _  _  ___    __   ____     ___  __  __  ___
  (  _ \(  )    /__\  / __)( )/ )/ __)  /__\ (_  _)   / __)(  \/  )/ __)
   ) _ < )(__  /(__)\( (__  )  (( (__  /(__)\  )(    ( (__  )    ( \__ \
  (____/(____)(__)(__)\___)(_)\_)\___)(__)(__)(__)    \___)(_/\/\_)(___/

   @author          Black Cat Development
   @copyright       Black Cat Development
   @link            https://blackcat-cms.org
   @license         http://www.gnu.org/licenses/gpl.html
   @category        CAT_Core
   @package         CAT_Core

*/


namespace CAT\Helper;

use \CAT\Base as Base;
use \CAT\Registry as Registry;

if (!class_exists('Page')) {
    class Page extends Base
    {
        /**
         * log level
         **/
        protected static $loglevel            = \Monolog\Logger::EMERGENCY;
        #protected static $loglevel            = \Monolog\Logger::DEBUG;
        /**
         * current instance (singleton pattern)
         **/
        private static $instance            = null;

        /**
         * tables used in this class
         **/
        private static $pages_table         = ':prefix:pages';
        private static $headers_table       = ':prefix:pages_headers';
        private static $page_refs_table     = ':prefix:pages_langs';
        private static $visibility_table    = ':prefix:visibility';
        private static $routes_table        = ':prefix:pages_routes';

        private static $pages               = array();
        private static $id_to_index         = array();
        private static $pages_sections      = array();
        private static $visibilities        = array();
        private static $pages_by_visibility = array();

        private static $jquery_enabled      = false;
        private static $jquery_seen         = false;
        private static $jquery_ui_enabled   = false;
        private static $jquery_ui_seen      = false;

        private static $scan_paths          = array();
        // header js files
        private static $js                  = array();
        // footer js files
        private static $f_js                = array();
        // header static js
        private static $header_js           = array();
        // js files having prerequisites
        private static $prereq_js           = array();
        // already loaded files
        private static $loaded              = array();
        // conditionals
        private static $conditionals        = array();

        private static $meta                = array();
        private static $css                 = array();
        private static $title               = null;

        /**
         * the constructor loads the available pages from the DB and stores it
         * in internal arrays
         *
         * @access private
         * @return void
         **/
        public static function getInstance($skip_init=false)
        {
            if (!self::$instance) {
                self::$instance = new self();
                if (!$skip_init) {
                    self::init();
                }
            }
            return self::$instance;
        }   // end function getInstance()

        /**
         * allow methods to be called as object
         **/
        public function __call($method, $args)
        {
            if (! isset($this) || ! is_object($this)) {
                return false;
            }
            if (method_exists($this, $method)) {
                return call_user_func_array(array($this, $method), $args);
            }
        }

        /**
         * checks if a page exists; checks access file and database entry
         *
         * @access public
         * @return
         **/
        public static function exists($id)
        {
            // search by ID
            if (is_numeric($id)) {
                $page = self::properties($id);
                if ($page && is_array($page) && count($page)) {
                    return true;
                }
            } else {
                $sth = self::db()->query(
                    "SELECT `page_id` FROM `".self::$routes_table."` WHERE `route`=:link",
                    array('link'=>$id)
                );
                if ($sth->rowCount() > 0) {
                    return true;
                }
            }
            return false;
        }   // end function exists()

        /**
         * determine default page
         *
         * @access public
         * @return void
         **/
        public static function getDefaultPage()
        {
            if (!count(self::$pages)) {
                self::init();
            }

            // for all pages with level 0...
            $root    = array();
            $now     = time();
            $ordered = HArray::sort(self::$pages, 'ordering');

            foreach ($ordered as $page) {
                if (
                       $page['level']      == 0
                    && $page['visibility'] == 'public'
                    && self::isActive($page['page_id'])
                ) {
                    if (!Registry::get('PAGE_LANGUAGES')===true || $page['language'] == Registry::get('LANGUAGE')) {
                        return $page['page_id'];
                    }
                }
            }
            // no page so far, return first visible page on level 0
            foreach ($ordered as $page) {
                if (
                       $page['level'] == 0
                    && $page['visibility'] == 'public'
                    && self::isActive($page['page_id'])
                ) {
                    return $page['page_id'];
                }
            }
            // no page
            return false;
        } // end function getDefaultPage()

        /**
         *
         * @access public
         * @return
         **/
        public static function getDescendants($page_id)
        {
            $desc = array();
            $stmt = self::db()->query(
                    'SELECT `u`.`page_id` '
                  . 'FROM `cat_pages_closure` AS `c` '
                  . 'INNER JOIN `cat_pages_copy` AS `u` '
                  . 'ON (u.page_id = c.descendant) '
                  . 'WHERE `site_id`=? AND c.ancestor = ? AND c.depth >= 1 '
                  . 'ORDER BY `parent` ASC, `ordering` ASC',
                  array(CAT_SITE_ID,CAT_PAGE_ID)
            );
            $data = $stmt->fetchAll();
            if (is_array($data) && count($data)>0) {
                foreach ($data as $index => $item) {
                    $desc[] = $item['page_id'];
                }
            }
            return $desc;
        }   // end function getDescendants()
        
        /**
         *
         * @access public
         * @return
         **/
        public static function getExtraHeaderFiles($page_id=null)
        {
            $data = array(); //'js'=>array(),'css'=>array(),'code'=>''
            $q    = 'SELECT * FROM `'.self::$headers_table.'` WHERE `page_id`=:page_id';
            $r    = Base::db()->query($q, array('page_id'=>$page_id));
            $data = $r->fetchAll();

            foreach ($data as $i => $row) {
                if (isset($row['page_js_files']) && $row['page_js_files']!='') {
                    $data[$i]['js'] = unserialize($row['page_js_files']);
                }
                if (isset($row['page_css_files']) && $row['page_css_files']!='') {
                    $data[$i]['css'] = unserialize($row['page_css_files']);
                }
            }

            return $data;
        }   // end function getExtraHeaderFiles()

        /**
         *
         * @access public
         * @return
         **/
        public static function getLastEdited($number=10)
        {
            $result = array();
            $pages  = self::getPages(1);
            // sort pages by when_changed
            $res = usort($pages, function ($a, $b) {
                return (($a["modified_when"] < $b["modified_when"]) ? 1 : -1);
            });
            return array_slice($pages, 0, $number);
        }   // end function getLastEdited()

        /**
         * creates a full url for the given pageID
         *
         * @access public
         * @params integer  $page_id
         * @return string
         **/
        public static function getLink($page_id) : string
        {
            if (!is_numeric($page_id)) {
                $link = $page_id;
            } else {
                $link = self::properties($page_id, 'route');
            }

            if (!$link) {
                return '';
            }

            // Check for :// in the link (used in URL's) as well as mailto:
            if (strstr($link, '://') == '' && substr($link, 0, 7) != 'mailto:') {
                return CAT_SITE_URL.$link.Registry::get('PAGE_EXTENSION');
            } else {
                return $link;
            }
        }   // end function getLink()

        /**
         * get a list of pages in other languages that are linked to the
         * given page; returns an array of pageIDs or boolean false if no
         * linked pages are found
         *
         * @access public
         * @param  integer  $page_id
         * @return mixed
         **/
        public static function getLinkedByLanguage(int $page_id) : array
        {
            $sql     = 'SELECT * FROM `'.self::$page_refs_table.'` AS t1'
                     . ' RIGHT OUTER JOIN `'.self::$pages_table.'` AS t2'
                     . ' ON `t1`.`link_page_id`=`t2`.`page_id`'
                     . ' JOIN `:prefix:pages_routes` AS t3'
                     . ' ON `t1`.`page_id`=`t3`.`page_id`'
                     . ' WHERE `t1`.`page_id` = :id'
                     ;

            $results = self::getInstance()->db()->query($sql, array('id'=>$page_id));
            if ($results->rowCount()) {
                $items = array();
                while (($row = $results->fetch()) !== false) {
                    $row['href'] = self::getLink($row['route']);
                    $items[]     = $row;
                }
                return $items;
            }
            return array();
        }   // end function getLinkedByLanguage()

        /**
         * get properties for page $page_id
         *
         * @access public
         * @param  integer  $page_id
         * @param  string   $type
         * @param  string   $key
         * @return
         **/
        public static function getPageSettings($page_id, $type='internal', $key=null)
        {
            $set = self::properties($page_id, 'settings');
            if ($type) {
                if ($key) {
                    if (isset($set[$type][$key])) {
                        if (is_array($set[$type][$key]) && count($set[$type][$key]) == 1) {
                            return $set[$type][$key][0];
                        }
                        return $set[$type][$key];
                    } else {
                        return null;
                    }
                } else {
                    return (isset($set[$type]) ? $set[$type] : null);
                }
            }
            return $set;
        }   // end function getPageSettings()

        /**
         * returns complete pages array
         *
         * @access public
         * @param  boolean $all - show all pages or only visible (default:false)
         * @return array
         **/
        public static function getPages($all=false)
        {
            if (!count(self::$pages)) {
                self::getInstance();
            }
            if ($all) {
                $pages =  self::$pages;
            } else {
                // only visible for current lang
                $pages = array();
                foreach (self::$pages as $pg) {
                    if (self::isVisible($pg['page_id'])) {
                        $pages[] = $pg;
                    }
                }
            }
            return $pages;
        }   // end function getPages()

        /**
         *
         * @access public
         * @return
         **/
        public static function getPagesAsList($all=false)
        {
            $pages = self::getPages($all);
            // sort by children
            //$pages = self::lb()->sort($pages);
            if (!is_array($pages) || !count($pages)>0) {
                return false;
            }
            $list  = array(0=>self::lang()->translate('none'));
            foreach ($pages as $p) {
                $list[$p['page_id']] = str_repeat('|-- ', $p['level']) . $p['menu_title'];
            }
            return $list;
        }   // end function getPagesAsList()

        /**
         * returns a list of page_id's by visibility
         *
         * @access public
         * @param  string  $visibility - optional
         * @return array
         **/
        public static function getPagesByVisibility($visibility=null)
        {
            self::init();
            if (!count(self::$pages_by_visibility)) {
                foreach (self::$pages as $page) {
                    self::$pages_by_visibility[$page['visibility']][] = $page['page_id'];
                }
            }
            if ($visibility) {
                if (isset(self::$pages_by_visibility[$visibility])) {
                    return self::$pages_by_visibility[$visibility];
                } else {
                    return array();
                }
            }
            return self::$pages_by_visibility;
        }   // end function getPagesByVisibility()

        /**
         *
         * @access public
         * @return
         **/
        public static function getPagesForLanguage($lang)
        {
            if (!count(self::$pages)) {
                self::getInstance();
            }
            $result = array();
            foreach (self::$pages as $pg) {
                // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// Achtung: isVisible() funktioniert nicht richtig, wenn der Benutzer im BE
// angemeldet ist, jedoch per AJAX z.B. \CAT\Backend::list() aufgerufen wird
// Daher erst mal zum Testen auskommentiert
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                if ($pg['language']==$lang) { // && self::isVisible($pg['page_id']) )
                    $result[] = $pg;
                }
            }
            return $result;
        }   // end function getPagesForLanguage()

        /**
         * returns pages array for given menu number
         *
         * @access public
         * @param  integer  $id    - menu id
         * @return array
         **/
        public static function getPagesForMenu($id)
        {
            if (!count(self::$pages)) {
                self::getInstance();
            }
            $menu = array();
            foreach (self::$pages as $pg) {
                if ($pg['menu'] == $id && self::isVisible($pg['page_id'])) {
                    $menu[] = $pg;
                }
            }
            return $menu;
        }   // end function getPagesForMenu()

        /**
         * get the path of the given page
         *
         * @access public
         * @param  integer  $page_id
         * @param  boolean  $skip_zero
         * @param  boolean  $as_array
         * @return mixed
         **/
        public static function getPageTrail($page_id, $skip_zero=false, $as_array=false)
        {
            $ids = array_reverse(self::getParentIDs($page_id));
            if ($skip_zero) {
                array_shift($ids);
            }
            $ids[] = $page_id;
            return (
                $as_array ? $ids : implode(',', $ids)
            );
        }   // end function getPageTrail()

        /**
         *
         * @access public
         * @return
         **/
        public static function getPageTypes()
        {
            return array(
                'page' => 'Page',
                'menu_link' => 'Menu Link',
            );
        }   // end function getPageTypes()

        /**
         * resolves the path to root and returns the list of parent IDs
         *
         * @access public
         * @return
         **/
        public static function getParentIDs($page_id)
        {
            $ids = array();
            while (self::properties($page_id, 'parent') !== null) {
                if (self::properties($page_id, 'level') == 1) {
                    break;
                }
                $ids[]   = self::properties($page_id, 'parent');
                $page_id = self::properties($page_id, 'parent');
            }
            return $ids;
        }   // end function getParentIDs()

        /**
         * returns the root level page of a trail
         *
         * @access public
         * @return integer
         **/
        public static function getRootParent($page_id)
        {
            if (self::properties($page_id, 'level')==0) {
                return 0;
            }
            $trail = self::getPageTrail($page_id, false, true);
            return $trail[0];
        }   // end function getRootParent()

        /**
         *
         * @access public
         * @return
         **/
        public static function getTitle()
        {
            return self::$title;
        }   // end function getTitle()

        /**
         *
         * @access public
         * @return
         **/
        public static function getVisibilityID(string $vis)
        {
            self::getVisibilities();
            foreach (self::$visibilities as $id => $name) {
                if ($name==$vis) {
                    return $id;
                }
            }
            return 4;
        }   // end function getVisibilityID()

        /**
         *
         * @access public
         * @return
         **/
        public static function getVisibilities()
        {
            if (!count(self::$visibilities)) {
                $sth = self::db()->query(
                    'SELECT * FROM `'.self::$visibility_table.'`'
                );
                $temp = $sth->fetchAll();
                foreach ($temp as $item) {
                    self::$visibilities[$item['vis_id']] = $item['vis_name'];
                }
            }
            return self::$visibilities;
        }   // end function getVisibilities()
        
        /**
         * checks if page is active (=has active sections and is between
         * publ_start and publ_end)
         *
         * @access public
         * @param  integer $page_id
         * @return boolean
         **/
        public static function isActive($page_id)
        {
            if (self::isDeleted($page_id)) {
                return false;
            }
            #            $sections = \CAT\Sections::getSections($page_id,null,true);
            #            if(is_array($sections) && count($sections))
            #                return true;
            return true;
        } // end function isActive()

        /**
         * checks if page is deleted
         *
         * @access public
         * @param  integer $page_id
         * @return boolean
         **/
        public static function isDeleted($page_id)
        {
            $page    = self::properties($page_id);
            if ($page['page_visibility']==5) {
                return true;
            }
            return false;
        } // end function isDeleted()

        /**
         *
         * @access public
         * @return
         **/
        public static function isLinkedTo($page_id, $linked_id, $lang)
        {
            $data = self::db()->query(
                'SELECT * FROM `'.self::$page_refs_table.'` WHERE '
                .'`page_id`=? AND `lang`=? AND `link_page_id`=?',
                array($page_id,$lang,$linked_id)
            );
            return $data->rowCount();
        }   // end function isLinkedTo()

        /**
         * Check whether a page is visible or not
         * This will check page-visibility, user- and group permissions
         *
         * @access public
         * @param  integer  $page_id
         * @return boolean
         **/
        public static function isVisible($page_id)
        {
            $show_it = false;
            $page    = self::properties($page_id);

            switch ($page['page_visibility']) {
                // public - always visible
                case 1:
                    $show_it = true;
                    break;
                // none, deleted - never shown in FE
                case 4:
                case 5:
                    $show_it = false;
                    break;
                // hidden - shown if called, but not in menu; skip intro page (selectPage(true))
                case 3:
                    if (\CAT\Page::getID()==$page_id) {
                        $show_it = true;
                    }
                    break;
                // private, registered - shown if user is allowed
                case 2:
                case 6:
                    if (self::user()->isAuthenticated() == true) {
                        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// TODO: ANPASSEN FUER NEUES BERECHTIGUNGSZEUGS
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
/*
                        // check language
                        if(Registry::get('PAGE_LANGUAGES')=='false'||(self::properties($page_id,'language')==''||self::properties($page_id,'language')==LANGUAGE))
                        $show_it = (
                               \CAT\Users::is_group_match(\CAT\Users::get_groups_id(), $page['viewing_groups'])
                            || \CAT\Users::is_group_match(\CAT\Users::get_user_id(), $page['viewing_users'])
                            || \CAT\Users::isRoot()
                        );
*/
                    } else {
                        $show_it = false;
                    }
                    break;
            }
            return $show_it;
        } // end function isVisible()

        /**
         * returns the properties for the given page ID
         *
         * @access public
         * @param  integer $page_id
         * @param  string  $key      - optional property name
         * @return mixed
         **/
        public static function properties($page_id=null, $key=null)
        {
            if (!$page_id) {
                $page_id = \CAT\Page::getID();
            }

            if (!count(self::$pages) && !Registry::exists('CAT_HELPER_PAGE_INITIALIZED')) {
                self::init();
            }

            // get page data
            $page = isset(self::$id_to_index[$page_id])
                  ? self::$pages[self::$id_to_index[$page_id]]
                  : null;

            if (is_array($page) && count($page)) {
                if ($key) {
                    if (isset($page[$key])) {
                        return $page[$key];
                    } else {
                        return null;
                    }
                } else {
                    return $page;
                }
            }
            return null;
        }   // end function properties()

        /**
         *
         * @access public
         * @return
         **/
        public static function reload()
        {
            self::init(true);
        }   // end function reload()


        /**
         * allows to set the page title for the current page
         *
         * @access public
         * @return
         **/
        public static function setTitle($title)
        {
            self::$title = $title;
        }   // end function setTitle()

        /**
         * initialize; fills the internal pages array
         *
         * @access private
         * @param  boolean $force - always reload
         * @return void
         **/
        private static function init($force=false)
        {
            if (Registry::exists('CAT_HELPER_PAGE_INITIALIZED') && !$force) {
                return;
            }

            // fill pages array
            if (count(self::$pages)==0 || $force) {
                $result = self::db()->query(
                      'SELECT `t1`.*, `t2`.`vis_name` AS `visibility`, `t3`.`variant`, `t4`.`route` '
                    . 'FROM `'.self::$pages_table.'` AS `t1` '
                    . 'JOIN `'.self::$visibility_table.'` AS `t2` '
                    . 'ON `t1`.`page_visibility`=`t2`.`vis_id` '
                    . 'LEFT JOIN `:prefix:pages_template` AS `t3` '
                    . 'ON `t1`.`page_id`=`t3`.`page_id` '
                    . 'JOIN `'.self::$routes_table.'` AS `t4` '
                    . 'ON `t1`.`page_id`=`t4`.`page_id` '
                    . 'WHERE `site_id`=? '
                    //. 'ORDER BY `level` ASC, `ordering` ASC',
                    . 'ORDER BY `parent` ASC, `ordering` ASC',
                    array(CAT_SITE_ID)
                );

                /*
                                $result = self::db()->query(
                                       'SELECT `u`.* '
                                     . 'FROM `cat_pages_closure` AS `c` '
                                     . 'INNER JOIN `cat_pages_copy` AS `u` '
                                     . 'ON (u.page_id = c.descendant) '
                                     . 'WHERE `site_id`=? AND c.ancestor = 1 AND c.depth >= 1 '
                                     . 'ORDER BY `parent` ASC, `ordering` ASC',
                                     array(CAT_SITE_ID)
                                );
                
                                // get descendants
                                $desc = self::getDescendants(CAT_PAGE_ID);
                */

                // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                // Das fuehrt zu einer Endlos-Schleife, wenn die Default-Page gesucht wird!
                // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                #$curr = \CAT\Page::getID();
                #$curr = CAT_PAGE_ID;
                $curr = 0;
                // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                // TODO:
//     Infos zu is_in_trail etc fehlen noch
                // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                self::$pages = $result->fetchAll();

                // map index to page id
                foreach (self::$pages as $index => $page) {
                    // note: order is important! $id_to_index first!
                    self::$id_to_index[$page['page_id']]  = $index;
                    self::$pages[$index]['href']          = self::getLink($page['page_id']);
                    self::$pages[$index]['link']          = '<a href="'.self::$pages[$index]['href'].'">'.self::$pages[$index]['menu_title'].'</a>';
                    self::$pages[$index]['is_current']    = ($curr==$page['page_id'] ? true : false);
                    self::$pages[$index]['is_in_trail']   = true;
//                    self::$pages[$index]['is_descendant'] = in_array($page['page_id'],$desc);
                }
            }

            Registry::register('CAT_HELPER_PAGE_INITIALIZED', true);
        }   // end function init()
    }   // end class Page
}
