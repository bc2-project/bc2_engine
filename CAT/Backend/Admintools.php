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

namespace CAT\Backend;

use \CAT\Base as Base;
use \CAT\Backend as Backend;
use \CAT\Helper\Json as Json;
use \CAT\Helper\Addons as Addons;
use \CAT\Helper\Directory as Directory;
use \CAT\Helper\Validate as Validate;

if (!class_exists('Backend\Admintools')) {
    class Admintools extends Base
    {
        // array to store config options
        protected static $instance = null;
        protected static $loglevel = \Monolog\Logger::EMERGENCY;
        //protected static $loglevel = \Monolog\Logger::DEBUG;
        protected static $debug    = false;

        /**
         *
         * @access public
         * @return
         **/
        public static function getInstance()
        {
            if (!is_object(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }   // end function getInstance()
        
        /**
         *
         * @access public
         * @return
         **/
        public static function index()
        {
            $d = self::list();
            Backend::show('backend_dashboard', array('id'=>0,'dashboard'=>$d));
        }   // end function index()

        /**
         *
         *
         *
         *
         **/
        public static function list($as_array=false)
        {
            if (!self::user()->hasPerm('tools_list')) {
                self::printError('You are not allowed for the requested action!');
            }

            $d = \CAT\Helper\Dashboard::getDashboardConfig('backend/admintools');

            // no configuration yet
            if (!isset($d['widgets']) || !is_array($d['widgets']) || !count($d['widgets'])) {
                $count = 0;
                $tools = Addons::getAddons('tool', 'name', false);

                if (count($tools)) {
                    // order tools by name
                    $tools = \CAT\Helper\HArray::sort($tools, 'name', 'asc', true);
                    $count = count($tools);
                    for($n=0;$n<$count;$n++) {
                        $tool = $tools[$n];
                        Base::addLangFile(CAT_ENGINE_PATH.'/'.CAT_MODULES_FOLDER.'/'.$tool['directory'].'/languages/');
                        $tools[$n]['image'] = (
                            file_exists(CAT_ENGINE_PATH.'/'.CAT_MODULES_FOLDER.'/'.$tool['directory'].'/icon.png') ?
                            CAT_SITE_URL.'/'.CAT_MODULES_FOLDER.'/'.$tool['directory'].'/icon.png' :
                            null
                        );
                    }
                }

                if (!$as_array && self::asJSON()) {
                    \CAT\Helper\JSON::printData($tools);
                    return;
                }

                $col          = 1; // init column
                $d['columns'] = (isset($d['columns']) ? $d['columns'] : 2); // init col number

                if ($count>0) {
                    foreach ($tools as $tool) {
                        // init widget
                        $d['widgets'][] = array(
                            'column'        => $col,
                            'widget_name '  => self::lang()->translate($tool['name']),
                            'content'       => (isset($tool['description']) ? self::lang()->translate($tool['description']) : ''),
                            'link'          => '<a href="'.CAT_ADMIN_URL.'/admintools/tool/'.$tool['directory'].'">'.$tool['name'].'</a>',
                            'position'      => 1,
                            'open'          => true,
                            'image'         => $tool['image'],
                        );
                        $col++;
                        if ($col > $d['columns']) {
                            $col = 1;
                        }
                    }
                    //\CAT\Helper\Dashboard::saveDashboardConfig($d,'global','admintools');
                    //$d = \CAT\Helper\Dashboard::getDashboard('backend/admintools');
                }
            }
            return $d;
        }   // end function list()

        /**
         *
         * @access public
         * @return
         **/
        public static function tool()
        {
            // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            // TODO: tool perm
            // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            if (!self::user()->hasPerm('tools_list')) {
                self::printFatalError('You are not allowed for the requested action!');
            }
            $tool    = self::getItem('tool');

            // kind of dirty hack...
            if (!$tool || $tool=='admintools') {
                self::router()->reroute(CAT_BACKEND_PATH.'/admintools');
                return;
            }

            $name    = Addons::getDetails($tool, 'name');
            $handler = null;
            foreach (array_values(array(str_replace(' ', '', $name),$tool)) as $classname) {
                foreach (array_values(array(
                    Directory::sanitizePath(CAT_ENGINE_PATH.'/'.CAT_MODULES_FOLDER.'/'.$tool.'/inc/class.'.$classname.'.php'),
                    Directory::sanitizePath(CAT_ENGINE_PATH.'/modules/tool_'.$tool.'/inc/class.'.$classname.'.php'),
                )) as $filename) {
                    if (file_exists($filename)) {
                        $handler = $filename;
                    }
                }
            }

            $tpl_data = array('content'=>'Ooops, no content');
            if ($handler) {
                self::log()->addDebug(sprintf('found class file [%s]', $handler));
                self::addLangFile(CAT_ENGINE_PATH.'/'.CAT_MODULES_FOLDER.'/'.$tool.'/languages/');
                self::addLangFile(CAT_ENGINE_PATH.'/modules/tool_'.$tool.'/languages/');
                self::setTemplatePaths($tool);
                include_once $handler;
                if (!class_exists($classname)) {
                    $classname = '\CAT\Addon\\'.$classname;
                }

                // init forms
                $init = Directory::sanitizePath(
                    CAT_ENGINE_PATH.'/'.CAT_MODULES_FOLDER.'/'.$tool.'/forms.init.php'
                );
                if (file_exists($init)) {
                    Backend::initForm();
                    require $init;
                }
                if (is_callable(array($classname,'initialize'))) {
                    $classname::initialize(array());
                }

                // check for function call in route
                $func = self::router()->getRoutePart(-1);
                if (is_callable(array($classname,$func))) {
                    $tpl_data['content'] = $classname::$func();
                } elseif (is_callable(array($classname,'tool'))) {
                    $tpl_data['content'] = $classname::tool();
                }
            }

            if (self::asJSON()) {
                echo json_encode($tpl_data, 1);
                exit;
            }

            Backend::show('backend_admintool', $tpl_data);
        }   // end function tool()
        
    } // class \CAT\Helper\Admintools
} // if class_exists()
