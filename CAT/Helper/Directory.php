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

declare(strict_types=1);

namespace CAT\Helper;

use \CAT\Base as Base;
use \CAT\Registry as Registry;

if (!class_exists('\CAT\Helper\Directory')) {
    class Directory extends Base
    {
        /**
         * IMPORTANT: Enabling debugging here causes endless loop! DON'T!!!
         **/
        protected static $loglevel     = \Monolog\Logger::EMERGENCY;
        /**
         * enable INTERNAL logging
         **/
        protected static $debug        = false;
        /**
         * Window or not
         **/
        protected static $is_win       = null;
        /**
         * current instance
         **/
        private static $instance     = null;
        /**
         * collect trace (debug output)
         **/
        private static $trace        = array();

        /**
         * get an instance of the directory class; optional param $reset
         * allows to reset all settings to default (example: $suffix_filter)
         *
         * @access public
         * @param  boolean  $reset
         * @return object
         **/
        public static function getInstance()
        {
            if (!self::$instance || !self::$instance instanceof self) {
                self::$instance = new self();
            }
            return self::$instance;
        }   // end function getInstance()

        /**
         * checks for valid path
         *
         * @access public
         * @param  string   $path
         * @param  string   $inside (optional) - MEDIA | SITE | TEMP | ENGINE
         * @return boolean
         **/
        public static function checkPath(string $path, string $inside = '')
        {
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// TODO: Review
//       Rechte pruefen (nicht jeder darf in CAT_ENGINE_PATH schreiben)
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $paths_to_check = array();
            if (!empty($inside)) {
                switch ($inside) {
                case 'MEDIA':
                        $paths_to_check[] = self::user()->getHomeFolder();
                    break;
                case 'SITE':
                        $paths_to_check[] = CAT_PATH;
                    break;
                case 'TEMP':
                        $paths_to_check[] = CAT_TEMP_FOLDER;
                    break;
                case 'ENGINE':
                        $paths_to_check[] = CAT_ENGINE_PATH;
                    break;
            }
            } else {
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// TODO: nicht bei jedem Aufruf, sondern einmal global festlegen
//       (Rechte angemeldeter Benutzer)
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                // will be sanitized within loop
                $paths_to_check = array(
                    CAT_TEMP_FOLDER,               // "everyone"
                    self::user()->getHomeFolder(), // current user
                    CAT_PATH,                      // site
                    CAT_ENGINE_PATH                // root
                );
            }
            for ($i=0;$i<count($paths_to_check);$i++) {
                $check = self::sanitizePath($paths_to_check[$i]);
            $path  = self::sanitizePath($path);
            if (substr_compare($path, $check, 0, strlen($check), true)==0) {
                return true;
                }
            }
            return false;
        }   // end function checkPath()

        /**
         * copy directory structure with files
         *
         * @access public
         * @param  string  $dirsource
         * @param  string  $dirdest
         **/
        public static function copyRecursive(string $dirsource, string $dirdest)
        {
            $dir_handle = null;
            if (is_dir($dirsource)) {
                $dir_handle = dir($dirsource);
            }
            if (! is_object($dir_handle)) {
                return false;
            }
            while ($file = $dir_handle->read()) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($dirsource.'/'.$file)) {
                        copy($dirsource.'/'.$file, $dirdest.'/'.$file);
                    } else {
                        self::createDirectory($dirdest.'/'.$file);
                        self::copyRecursive($dirsource.'/'.$file, $dirdest.'/'.$file);
                    }
                }
            }
            $dir_handle->close();
            return true;
        }   // end function copyRecursive()

        /**
         * Create directories recursively
         *
         * @access public
         * @param  string   $dir_name - directory to create
         * @param  octal    $dir_mode - access mode
         * @return boolean
         **/
        public static function createDirectory(string $dir_name, string $dir_mode=null, bool $createIndex=false)
        {
            if (!$dir_mode) {
                $dir_mode = Registry::exists('OCTAL_DIR_MODE')
                          ? Registry::get('OCTAL_DIR_MODE')
                          : (int) octdec(self::defaultDirMode());
            }
            $dir_name = self::sanitizePath($dir_name);
            if ($dir_name != '' && !is_dir($dir_name)) {
                $umask = umask(0);
                mkdir($dir_name, $dir_mode, true);
                umask($umask);
                if ($createIndex) {
                    self::createIndex($dir_name);
                }
                return true;
            }
            return false;
        }   // end function createDirectory()

        /**
         * This method creates index.php files in every subdirectory of a given
         * path
         *
         * @access public
         * @param  string  $dir - directory to start with
         * @return boolean
         *
         **/
        public static function createIndex(string $dir)
        {
            if ($handle=dir($dir)) {
                if (!file_exists($dir.'/index.php')) {
                    $fh = fopen($dir.'/index.php', 'w');
                    fwrite($fh, '<' . '?' . 'php' . "\n");
                    fclose($fh);
                }
                while (false !== ($file=$handle->read())) {
                    if ($file != "." && $file != "..") {
                        if (is_dir($dir.'/'.$file)) {
                            self::createIndex($dir.'/'.$file);
                        }
                    }
                }
                $handle->close();
                return true;
            } else {
                return false;
            }
        }   // end function createIndex()

        /**
         * If the configuration setting 'string_dir_mode' is missing, we need
         * a default value that fits most cases.
         *
         * @access public
         * @return string
         **/
        public static function defaultDirMode()
        {
            return (!self::isWin())
                ? '0755'
                : '0777';
        }   // end function defaultDirMode()

        /**
         *
         * @access public
         * @return
         **/
        public static function defaultFileMode()
        {
            // we've already created some new files, so just check the perms they've got
            $check_for = dirname(__FILE__).'/../../../temp/logs/index.php';
            if (file_exists($check_for)) {
                $default_file_mode = octdec('0'.substr(sprintf('%o', fileperms($check_for)), -3));
            } else {
                $default_file_mode = '0777';
            }
            return $default_file_mode;
        }   // end function defaultFileMode()

        /**
         *
         * @access public
         * @param  string   $dir - path to start with
         * @param  array    $options - several options
         * @return array
         **/
        public static function findDirectories($dir, $options=array())
        {
            if (!is_dir($dir)) {
                return array();
            }

            // merge options with defaults
            $options = array_merge(array(
                'curr_depth'    => 0,       // pass current depth
                'max_depth'     => 9,       // max recursion depth
                'recurse'       => false,   // recurse or not
                'remove_prefix' => false,   // remove prefix or not
                'ignore'        => array(), // folders to ignore
                'as_tree'       => false,
            ), $options);

            $options['curr_depth']++;

            $dir = self::sanitizePath($dir);

            if (isset($options['remove_prefix']) && is_bool($options['remove_prefix']) && $options['remove_prefix']===true) {
                $options['remove_prefix'] = self::sanitizePath($dir).'/';
            }

            $directories = array();
            foreach (scandir($dir) as $file) {
                if (substr($file, 0, 1)=='.') {
                    continue;
                }
                $curr_item = self::getName(self::sanitizePath($dir.'/'.$file));
                if (is_dir($curr_item)) {
                    $name = str_ireplace($options['remove_prefix'], '', $curr_item);
                    if ($options['recurse']===true && $options['curr_depth']<$options['max_depth']) {
                        if ($options['as_tree']==false) {
                            $directories[] = $name;
                            $directories = array_merge($directories, self::findDirectories($curr_item, $options));
                        } else {
                            $sub_opt     = $options;
                            $sub_opt['remove_prefix'] .= "$name/";
                            $subdirs     = self::findDirectories($curr_item, $sub_opt);
                            $directories[$name] = $subdirs;
                        }
                    } else {
                        $directories[] = $name;
                    }
                }
            }

            return $directories;
        }   // end function findDirectories()

        /**
         *
         * @access public
         * @return
         **/
        public static function findFiles($dir, $options=array())
        {
            if (!strlen($dir) || !is_dir($dir)) {
                self::$trace[] = sprintf('[%s] is not a directory', $dir);
                return array();
            }

            if (self::$debug) {
                self::$trace[] = sprintf('scanning path [%s]', $dir);
            }

            // merge options with defaults
            $options = array_merge(array(
                'curr_depth'    => 0,     // pass current recursion depth
                'extension'     => null,  // file extension to scan for
                'extensions'    => null,  // array of extensions to scan for
                'filename'      => '',    // filename to scan for
                'filter'        => '',    // filename filter
                'max_depth'     => 9,     // max recursion depth
                'recurse'       => false, // recurse or not
                'remove_prefix' => false, // prefix to remove from path
                'remove_suffix' => false, // suffix to remove from path
                'as_uri'        => false, // converts paths to URLs
            ), $options);

            $options['curr_depth']++;

            // add extension to scan for to extensions array
            if ($options['extension']) {
                if (!is_array($options['extensions'])) {
                    $options['extensions'] = array($options['extension']);
                } else {
                    $options['extensions'][] = $options['extension'];
                }
                $options['extensions'] = array_unique($options['extensions']);
            }

            // sanitize extensions
            if (is_array($options['extensions']) && count($options['extensions'])) {
                unset($options['extension']);
                for ($i=0;$i<count($options['extensions']);$i++) {
                    $options['extensions'][$i] = preg_replace('~^\.~', '', $options['extensions'][$i], 1);
                }
            }

            $dir   = self::sanitizePath($dir);
            $files = array();

            if (
                   isset($options['remove_prefix'])
                && is_bool($options['remove_prefix'])
                && $options['remove_prefix'] === true
            ) {
                $options['remove_prefix'] = self::sanitizePath($dir);
            }

            if (self::$debug) {
                self::$trace[] = var_export($options, true);
            }

            $scanned_directory = array_diff(scandir($dir), array('..', '.'));
            if (is_array($scanned_directory) && count($scanned_directory)>0) {
                foreach ($scanned_directory as $file) {
                    if (substr($file, 0, 1)=='.') {
                        continue;
                    }
                    $curr_item = self::getName(self::sanitizePath($dir.'/'.$file));
                    if (self::$debug) {
                        self::$trace[] = sprintf('current item: %s', $curr_item);
                    }
                    if (is_file($curr_item)) {
                        $filename = str_ireplace($options['remove_prefix'], '', $curr_item);
                        if (self::$debug) {
                            self::$trace[] = sprintf('checking file: %s', $filename);
                        }
                        // filename match
                        if (
                               strlen($options['filename'])
                            && pathinfo($curr_item, PATHINFO_FILENAME) != $options['filename']
                        ) {
                            if (self::$debug) {
                                self::$trace[] = sprintf(
                                '>>> skipped by filename filter --- [%s] != [%s]',
                                pathinfo($curr_item, PATHINFO_FILENAME),
                                $options['filename']
                            );
                            }
                            continue;
                        }
                        // extension match
                        if (
                               is_array($options['extensions'])
                            && count($options['extensions'])
                            && !in_array(pathinfo($curr_item, PATHINFO_EXTENSION), $options['extensions'])
                        ) {
                            if (self::$debug) {
                                self::$trace[] = sprintf(
                                '>>> skipped by extensions filter; allowed extensions:',
                                implode(',', $options['extensions'])
                            );
                            }
                            continue;
                        }
                        // filter match
                        if (strlen($options['filter'])) {
                            $filter = "~^".$options['filter'];
                            if (count($options['extensions'])>0) {
                                $filter .= "\.(" . implode("|", $options['extensions']) . ")";
                            } else {
                                $filter .= "\..*";
                            }
                            $filter .= "$~i";
                            if (!preg_match($filter, pathinfo($curr_item, PATHINFO_BASENAME))
                            ) {
                                if (self::$debug) {
                                    self::$trace[] = sprintf(
                                    '>>>skipped by regexp filter: [%s]',
                                    $filter
                                );
                                }
                                continue;
                            }
                        }
                        if (self::$debug) {
                            self::$trace[] = sprintf(
                            'adding file [%s]',
                            $filename
                        );
                        }

                        $files[] = (
                            $options['as_uri'] === true
                            ? \CAT\Helper\Validate::path2uri($filename)
                            : $filename
                        );
                    } else {
                        if (is_dir($curr_item) && $options['recurse']===true && $options['curr_depth']<$options['max_depth']) {
                            $files = array_merge($files, self::findFiles($curr_item, $options));
                        }
                    }
                }
            }

            return $files;
        }   // end function findFiles()

        /**
         * counts subdirs and files in the given path
         *
         * @access public
         * @param  string  $path
         * @return array
         **/
        public static function getDirectoryItemCount(string $path)
        {
            $path = realpath($path);
            if ($path!==false && $path!='' && is_dir($path)) {
                $files = self::findFiles($path);
                $dirs  = self::findDirectories($path);
                return array('files'=>count($files), 'dirs'=>count($dirs));
            }
            return array('files'=>0,'dirs'=>0);
        }   // end function getDirectoryItemCount()

        /**
         *
         *
         **/
        public static function getDirectorySize(string $path, bool $humanize=false)
        {
            $bytestotal = 0;
            $path = realpath($path);
            if ($path!==false && $path!='' && file_exists($path)) {
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $object) {
                    try {
                        $bytestotal += $object->getSize();
                    } catch (Exception $e) {
                    }
                }
            }
            return (
                $humanize ? self::humanize((string)$bytestotal) : $bytestotal
            );
        }

        /**
         * tries several methods to get the mime type of a file
         *
         * @access public
         * @return
         **/
        public function getMimeType()
        {
            $mime = self::getID3Mime();
            return $mime;
        }   // end function getMimeType()

        /**
         * get file modification date (timestamp)
         *
         * @access public
         * @param  string  $file
         * @return string
         **/
        public static function getModdate(string $file, bool $humanize=false) : string
        {
            $file = self::sanitizePath($file);
            if (mb_detect_encoding($file, 'UTF-8', true)) {
                $file = utf8_decode($file);
            }
            if (is_dir($file)) {
                return false;
            }
            if (!file_exists($file)) {
                return false;
            }
            $stat  = stat($file);
            $date  = isset($stat['mtime'])
                   ? $stat['mtime']
                   : null;
            if ($humanize && !empty($date)) {
                return DateTime::getDateTime($date);
            }
            return $date;
        }   // end function getModdate()

        /**
         * returns the "real" filename (UTF-8)
         *
         * @access public
         * @param  string  $file
         * @return string
         **/
        public static function getName($file)
        {
            return (mb_detect_encoding($file, 'UTF-8', true) ? $file : utf8_encode($file));
        }   // end function getName()

        /**
         * get file size
         *
         * @access public
         * @param  string  $file
         * @param  boolean $convert - call byte_convert(); default: false
         * @return string
         **/
        public static function getSize($file, $convert=false)
        {
            $file = self::sanitizePath($file);
            if (is_dir($file)) {
                return false;
            }
            if (!file_exists($file)) {
                return false;
            }
            $size = @filesize($file);
            if ($size < 0) {
                if (!self::isWin()) {
                    $size = trim(`stat -c%s $file`);
                } else {
                    if (extension_loaded('COM')) {
                        $fsobj = new COM("Scripting.FileSystemObject");
                        $f = $fsobj->GetFile($file);
                        $size = $file->Size;
                    }
                }
            }
            if ($size && $convert) {
                $size = self::humanize((string)$size);
            }
            return $size;
        }   // end function getSize()

        /**
         *
         * @access public
         * @return
         **/
        public static function getTrace($nl="\n")
        {
            return implode($nl, self::$trace);
        }   // end function getTrace()
        
        /**
         * convert bytes to human readable string
         *
         * @access public
         * @param  string $bytes
         * @return string
         **/
        public static function humanize(string $bytes) : string
        {
            $symbol          = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            $exp             = 0;
            $converted_value = 0;
            $bytes           = (int)$bytes;
            if ($bytes > 0) {
                $exp = floor(log($bytes) / log(1024));
                $converted_value = ($bytes / pow(1024, floor($exp)));
            }
            return sprintf('%.2f '.$symbol[$exp], $converted_value);
        }   // end function format()

        /**
         *
         * @access public
         * @return
         **/
        public static function isWin()
        {
            if (!self::$is_win) {
                self::$is_win = false;
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    self::$is_win = true;
                }
            }
            return self::$is_win;
        }   // end function isWin()

        /**
         *
         * @access public
         * @return
         **/
        public static function readFile(string $file, bool $asArray)
        {
            if (file_exists($file)) {
                $in   = file($file);
                return ($asArray ? $in : implode('', $in));
            }
        }   // end function readFile()

        /**
         * remove directory recursively
         *
         * @access public
         * @param  string  $directory
         * @return boolean
         *
         **/
        public static function removeDirectory(string $directory) : bool
        {
            // If suplied dirname is a file then unlink it
            if (is_file($directory)) {
                return unlink($directory);
            }
            // Empty the folder
            if (is_dir($directory)) {
                $dir = dir($directory);
                while (false !== $entry = $dir->read()) {
                    // Skip pointers
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }
                    // recursive delete
                    if (is_dir($directory . '/' . $entry)) {
                        self::removeDirectory($directory . '/' . $entry);
                    } else {
                        unlink($directory . '/' . $entry);
                    }
                }
                // Now delete the folder
                $dir->close();
                return rmdir($directory);
            }
        }   // end function removeDirectory()

        /**
         * convert a string like '2M' into bytes
         **/
        public static function asBytes($val)
        {
            $val  = trim($val);
            $last = strtolower($val[strlen($val)-1]);
            $val  = intval($val);
            switch ($last) {
                case 'g':
                    $val *= 1024;
                    // no break
                case 'm':
                    $val *= 1024;
                    // no break
                case 'k':
                    $val *= 1024;
            }

            return $val;
        }
        /**
         * convert string to a valid filename
         *
         * @access public
         * @param  string  $string - filename
         * @return string
         **/
        public static function sanitizeFilename(string $string)
        {
            self::log()->addDebug('> sanitizeFilename [{file}]', array('file'=>$string));
            require_once(CAT_ENGINE_PATH . '/framework/functions-utf8.php');
            $string = entities_to_7bit($string);
            // remove all bad characters
            $bad    = array('\'', '"', '`', '!', '@', '#', '$', '%', '^', '&', '*', '=', '+', '|', '/', '\\', ';', ':', ',', '?','(',')');
            $string = str_replace($bad, '', $string);
            // replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
            $string = preg_replace(array('/\.+/', '/\.+$/'), array('.', ''), $string);
            // replace spaces
            $string = trim($string);
            $string = preg_replace('/(\s)+/', '_', $string);
            // replace any weird language characters
            $string = str_replace(array('%2F', '%'), array('/', ''), urlencode($string));
            // remove path
            $string = pathinfo($string, PATHINFO_FILENAME);
            // Finally, return the cleaned string
            self::log()->addDebug('< sanitizeFilename result [{file}]', array('file'=>$string,__METHOD__,__LINE__));
            return $string;
        }   // end function sanitizeFilename()

        /**
         * fixes a path by removing //, /../ and other things
         *
         * @access public
         * @param  string  $path - path to fix
         * @return string
         **/
        public static function sanitizePath(string $path, bool $as_array=false)
        {
            #self::log()->addDebug(sprintf('> sanitizePath(%s)',$path));

            // remove trailing slash; this will make sanitizePath fail otherwise!
            $path       = preg_replace('~/{1,}$~', '', $path);
            // make all slashes forward
            $path       = str_replace('\\', '/', $path);
            // bla/./bloo ==> bla/bloo
            $path       = preg_replace('~/\./~', '/', $path);

            // relative path
            if (strlen($path)>2 && !substr_compare($path, '..', 0, 2)) {
                if (defined('CAT_ENGINE_PATH')) {
                    $path = substr_replace($path, CAT_ENGINE_PATH, 1, 2);
                }
            }

            // resolve /../
            // loop through all the parts, popping whenever there's a .., pushing otherwise.
            $parts = array();
            foreach (explode('/', preg_replace('~/+~', '/', $path)) as $part) {
                if ($part === ".." || $part == '') {
                    array_pop($parts);
                } elseif ($part!="") {
                    $part = (self::isWin() && mb_detect_encoding($part, 'UTF-8', true))
                          ? utf8_decode($part)
                          : $part;
                    $parts[] = $part;
                }
            }

            if ($as_array) {
                return $parts;
            }

            $new_path = implode("/", $parts);
            // windows
            if (!preg_match('/^[a-z]\:/i', $new_path)) {
                $new_path = '/' . $new_path;
            }
            #self::log()->addDebug('< returning path [{path}]',array('path'=>$new_path),array(__METHOD__,__LINE__));
            return $new_path;
        }   // end function sanitizePath()

        /*******************************************************************************
           PRIVATE METHODS
        *******************************************************************************/

        /**
         * uses getID3 to get the mime type
         *
         * @access public
         * @param  string  $filename
         * @return string
         **/
        private static function getID3Mime(string $filename)
        {
            $mime = null;

            if (!file_exists($filename)) {
                self::setError('File does not exist: "'.htmlentities($filename));
                return false;
            } elseif (!is_readable($filename)) {
                self::setError('File is not readable: "'.htmlentities($filename));
                return false;
            }

            require_once CAT_ENGINE_PATH.'/modules/lib_getid3/getid3/getid3.php';

            $getID3 = new getID3;
            if ($fp = fopen($filename, 'rb')) {
                $getID3->openfile($filename);
                if (empty($getID3->info['error'])) {
                    // ID3v2 is the only tag format that might be prepended in front of files, and it's non-trivial to skip, easier just to parse it and know where to skip to
                    getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'module.tag.id3v2.php', __FILE__, true);
                    $getid3_id3v2 = new getid3_id3v2($getID3);
                    $getid3_id3v2->Analyze();

                    fseek($fp, $getID3->info['avdataoffset'], SEEK_SET);
                    $formattest = fread($fp, 16);  // 16 bytes is sufficient for any format except ISO CD-image
                    fclose($fp);

                    $DeterminedFormatInfo = $getID3->GetFileFormat($formattest);
                    $mime = $DeterminedFormatInfo['mime_type'];
                } else {
                    self::setError('Failed to getID3->openfile "'.htmlentities($filename));
                }
            } else {
                self::setError('Failed to fopen "'.htmlentities($filename));
            }
            self::log()->addDebug(sprintf(
                'MIME type detected as [%s] by getID3 library',
                $mime
            ));
            return $mime;
        }   // end function getID3Mime()
    }
}
