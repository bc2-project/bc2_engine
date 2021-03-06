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

namespace CAT\Backend;

use CAT\Base as Base;
use CAT\Helper\DB        as DB;

if(!class_exists('\CAT\Backend\Permissions',false))
{
    class Permissions extends Base
    {
        /**
         * returns the permissions for a role; the role_id is retrieved from
         * the route
         *
         * @access public
         * @return
         **/
        public static function byrole()
        {
            if(!self::user()->hasPerm('roles_perms'))
                Json::printError('You are not allowed for the requested action!');

            $id    = self::getItem('perm_id','is_numeric');
            $perms = self::perms()->getPerms($id);

            if(!is_array($perms)) {
                $perms = array();
            }

            if(self::asJSON())
            {
                echo json_encode($perms,1);
                exit;
            }
        }   // end function byrole()

        /**
         *
         * @access public
         * @return
         **/
        public static function index()
        {
            echo "not here yet...";
        }
    }
}