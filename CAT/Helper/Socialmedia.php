<?php

/*
   ____  __      __    ___  _  _  ___    __   ____     ___  __  __  ___
  (  _ \(  )    /__\  / __)( )/ )/ __)  /__\ (_  _)   / __)(  \/  )/ __)
   ) _ < )(__  /(__)\( (__  )  (( (__  /(__)\  )(    ( (__  )    ( \__ \
  (____/(____)(__)(__)\___)(_)\_)\___)(__)(__)(__)    \___)(_/\/\_)(___/

   @author          Black Cat Development
   @copyright       Black Cat Development
   @link            http://blackcat-cms.org
   @license         http://www.gnu.org/licenses/gpl.html
   @category        CAT_Core
   @package         CAT_Core

*/

namespace CAT\Helper;
use \CAT\Base as Base;

if(!class_exists('\CAT\Helper\Socialmedia'))
{
    class Socialmedia extends Base
    {
        protected static $loglevel       = \Monolog\Logger::EMERGENCY;
        protected static $avail_services = null;
        protected static $global_enabled = null;

        /**
         *
         * @access public
         * @return
         **/
        public static function exists($name) : bool
        {
            $field = 'name';
            if(is_numeric($name)) { $field = 'id'; }
            $sth = self::db()->query(
                'SELECT `name` FROM `:prefix:socialmedia` WHERE `:field:`=:val',
                array('field'=>$field,'val'=>$name)
            );
            return ($sth->rowCount() > 0);
        }   // end function exists()

        /**
         *
         * @access public
         * @return
         **/
        public static function getEnabled($what='share')
        {
            if(!self::$global_enabled || !isset(self::$global_enabled[$what]))
            {
                $data = self::db()->query(
                      'SELECT `t1`.`name`, ifnull(`t2`.`'.$what.'_url`,`t1`.`'.$what.'_url`) as `url`, `t2`.`account` '
                    . 'FROM `:prefix:socialmedia` as `t1` '
                    . 'LEFT JOIN `:prefix:socialmedia_site` as `t2` '
                    . 'ON `t1`.`id`=`t2`.`id` '
                    . 'WHERE (`t2`.`'.$what.'_disabled` IS NULL OR `t2`.`'.$what.'_disabled` != "Y" ) '
                    . 'AND `t1`.`'.$what.'_url` IS NOT NULL '
                    . 'AND `t2`.`account` IS NOT NULL '
                    . 'ORDER BY `name`'
                );
                if($data)
                {
                    self::$global_enabled[$what] = $data->fetchAll();
                }
            }
            return self::$global_enabled[$what];
        }   // end function getEnabled()

        /**
         *
         * @access public
         * @return
         **/
        public static function getFollowButtons(int $pageID) : array
        {
            $data = self::getEnabled('follow');
            if(!is_array($data) || empty($data)) {
                return array();
            }
            return self::getBtns(intval($pageID),$data);
        }   // end function getFollowButtons()

        /**
         * get all available services
         **/
        public static function getServices($site=null)
        {
            if(!self::$avail_services)
            {
                $query = \CAT\Helper\DB::qb()
                             ->from(sprintf('%ssocialmedia',CAT_TABLE_PREFIX),'t1');
                if($site) {
                    $query->select('`t1`.*, `t2`.`account`, `t2`.`follow_disabled`, `t2`.`share_disabled`')
                          ->leftJoin(
                                't1',self::db()->prefix().'socialmedia_site',
                                't2','`t1`.`id`=`t2`.`id`'
                            )
                          ->orderBy('name');
                } else {
                    $query->select('`t1`.*');
                }
                $data = $query->execute();
                if($data)
                {
                    self::$avail_services = $data->fetchAll();
                }
            }
            return self::$avail_services;
        }   // end function getServices()

        /**
         *
         * @access public
         * @return
         **/
        public static function getShareButtons(int $pageID) : array
        {
            $data = self::getEnabled('share');
            if(!is_array($data) || empty($data)) {
                return array();
            }
            return self::getBtns(intval($pageID),$data);
        }   // end function getShareButtons()

        /**
         *
         * @access public
         * @return
         **/
        public static function showFollowButtons($tpl)
        {
            $pageID = \CAT\Page::getID();
            if(empty($tpl)) {
                // look for social.tpl in template folder
                $path = \CAT\Helper\Template::getPath($pageID);
                $files = array('social_follow.tpl','social.tpl');
                foreach($files as $file) {
                    $filepath = Directory::sanitizePath($path.'/'.$file);
                    if(file_exists($filepath)) {
                        $tpl = $filepath;
                        break;
                    }
                }
            }
            try {
                $btns = self::getFollowButtons($pageID);
                if(is_array($btns) && !empty($btns)) {
                    if(empty($tpl)) {
                        $string = '<li><a href="{$follow_url}" target="_blank" title="{$name}" onClick="return confirm(\'Sie verlassen diese Seite und besuchen Facebook!\')"><span>{$name}</span></a></li>';
                    }
                    if($tpl) {
                        \CAT\Base::tpl()->setPath(pathinfo($tpl,PATHINFO_BASENAME));
                        return \CAT\Base::tpl()->get($tpl,array('btns'=>$btns));
                    } else {
                        $tpl    = new \Dwoo\Template\Str($string);
                        foreach($btns as $name => $data) {
                            $output[] = \CAT\Base::tpl()->get($tpl,array('name'=>$name,'follow_url'=>$data));
                        }
                        return '<strong class="socialmedia_follow">'.\CAT\Base::lang()->translate('Follow us on').':</strong><ul class="socialmedia">'.implode("\n",$output)."</ul>\n";
                    }
                }
            } catch( Exception $e ) {
            }
        }   // end function showFollowButtons()

        /**
         *
         * @access public
         * @return
         **/
        public static function showShareButtons($tpl)
        {
            $pageID = \CAT\Page::getID();
            if(empty($tpl)) {
                // look for social.tpl in template folder
                $path = \CAT\Helper\Template::getPath($pageID);
                $files = array('social_share.tpl','social.tpl');
                foreach($files as $file) {
                    $filepath = Directory::sanitizePath($path.'/'.$file);
                    if(file_exists($filepath)) {
                        $tpl = $filepath;
                        break;
                    }
                }
            }
            try {
                $btns = self::getShareButtons($pageID);
                if(is_array($btns) && !empty($btns)) {
                    if(empty($tpl)) {
                        $string = '<li><a href="{$share_url}" target="_blank" title="{$name}"><span>{$name}</span></a></li>';
                    }
                    if($tpl) {
                        \CAT\Base::tpl()->setPath(pathinfo($tpl,PATHINFO_BASENAME));
                        return \CAT\Base::tpl()->get($tpl,array('btns'=>$btns));
                    } else {
                        $tpl    = new \Dwoo\Template\Str($string);
                        foreach($btns as $name => $data) {
                            $output[] = \CAT\Base::tpl()->get($tpl,array('name'=>$name,'share_url'=>$data));
                        }
                        return '<strong class="socialmedia_share">'.\CAT\Base::lang()->translate('Share this page on').':</strong><ul class="socialmedia">'.implode("\n",$output)."</ul>\n";
                    }
                }
            } catch( Exception $e ) {
            }
        }   // end function showShareButtons()
        

        /**
         *
         * @access protected
         * @return
         **/
        protected static function getBtns(int $pageID, array $data) : array
        {
            $btns = array();
            foreach($data as $i => $item) {
                // Sonderfall (?) Twitter: Leerzeichen nicht nur durch %20
                // ersetzen, sondern das % darin auch noch maskieren
                $url = rawurlencode((isset($_SERVER['HTTPS']) ? 'https:' : 'http:').\CAT\Helper\Page::getLink(intval($pageID),true));
                if($item['name'] == 'Twitter') {
                    $url = str_replace('%20','%2520',$url);
                }
                $btns[$item['name']] = \CAT\Base::tpl()->get(
                    new \Dwoo\Template\Str($item['url']),
                    array(
                        'NAME'        => $item['name'],
                        'ACCOUNT'     => $item['account'],
                        'PAGE_URL'    => $url,
                        'PAGE_TITLE'  => urlencode(\CAT\Helper\Page::properties($pageID,'page_title')),
                        'DESCRIPTION' => urlencode(\CAT\Helper\Page::properties($pageID,'description')),
                    )
                );
            }
            return $btns;
        }   // end function getBtns()
        

    }
}