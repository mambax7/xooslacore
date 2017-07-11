<?php
/**
 * Name: class.permissions.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: class.permissions.php 0000 26/03/2009 04:33:10:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XooslaPermissionsHandler
 *
 * @package
 * @author Catzwolf
 * @copyright Copyright (c) 2005
 * @version $Id: class.permissions.php,v 1.2 2007/03/30 22:05:45 catzwolf Exp $
 * @access public
 */
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

/**
 * XooslaPermissions
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaPermissions extends XoopsGroupPermForm {
    var $db;
    var $tableName;
    var $mod_id = 0;
    var $perm_name;
    var $perm_descript;
    var $groups;
    /**
     * XooslaPermissions::XooslaPermissions()
     *
     * @param string $table
     * @param string $_perm_name
     * @param string $_perm_descript
     * @return
     */
    function __Construct() {
    }

    /**
     * XooslaPermissions::getGroups()
     *
     * @return
     */
    function getGroups() {
        static $grouplist;

        if ( !$grouplist ) {
            $member_handler = &xoops_gethandler( 'member' );
            $grouplist = $member_handler->getGroupList();
        }
        $groups = $grouplist;
        return $groups;
    }

    /**
     * XooslaPermissions::setPermissions()
     *
     * @param string $table
     * @param string $perm_name
     * @param string $perm_descript
     * @param mixed $mod_id
     * @return
     */
    function setPermissions( $table = '', $perm_name = '', $perm_descript = '', $mod_id = 0 ) {
        if ( !empty( $table ) ) {
            $this->db = &XoopsDatabaseFactory::getDatabaseConnection();
            $this->tableName = $this->db->prefix( $table );
        }
        $this->_mod_id = $mod_id;
        $this->_perm_name = $perm_name;
        $this->_perm_descript = $perm_descript;
    }
    /**
     * XooslaPermissions::XooslaPermissions_render()
     *
     * @param array $arr
     * @return
     */
    function render( $arr = array() ) {
        if ( $this->_perm_descript ) {
            $perm_descript = $this->_perm_descript;
        } else {
            $perm_descript = null;
        }

        $sql = "SELECT {$arr['cid']}";
        if ( !empty( $arr['pid'] ) ) {
            $sql = ", {$arr['pid']}";
        }
        $sql .= ", {$arr['title']} FROM " . $this->tableName;
        if ( !empty( $arr['where'] ) ) {
            $sql .= " WHERE {$arr['where']}=" . $this->_mod_id;
        }
        if ( !empty( $arr['order'] ) ) {
            $sql .= " ORDER BY {$arr['order']}";
        }
        if ( !$result = $this->db->query( $sql ) ) {
            $error = $this->db->error() . " : " . $this->db->errno();
            trigger_error( $error );
        }

        $ret = '';
        $form_info = new XoopsGroupPermForm( '', $this->_mod_id, $this->_perm_name, $this->_perm_descript );
        if ( $this->db->getRowsNum( $result ) ) {
            while ( $row_arr = $this->db->fetcharray( $result ) ) {
                if ( !empty( $arr['pid'] ) ) {
                    $form_info->addItem( $row_arr[$arr['cid']], $row_arr[$arr['title']], $row_arr[$arr['pid']] );
                } else {
                    $form_info->addItem( $row_arr[$arr['cid']], $row_arr[$arr['title']], 0 );
                }
            }
            $ret = $form_info->render();
        }
        unset( $form_info );
        echo $ret;
    }
    /**
     * XooslaPermissions::save()
     *
     * @param array $groups
     * @param mixed $item_id
     * @return
     */
    function save( $groupids = array(), $item_id = 0 ) {
        if ( !is_array( $groupids ) || !count( $groupids ) || (int)$item_id == 0 ) {
            return false;
        }

        /**
         * Save the new permissions
         */
        $gperm_handler = &XooslaLoad::getHandler( 'groupperm' );
        if ( is_object( $gperm_handler ) && !empty( $gperm_handler ) ) {
            /**
             * First, if the permissions are already there, delete them
             */
            $gperm_handler->deleteByModule( $this->_mod_id, $this->_perm_name, $item_id );
            foreach ( $groupids as $groupid ) {
                if ( !$gperm_handler->addRight( $this->_perm_name, $item_id, $groupid, $this->_mod_id ) ) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * XooslaPermissions::get()
     *
     * @param mixed $item_id
     * @return
     */
    function get( $item_id = 0 ) {
        $groups = $this->getGroups();
        $gperm_handler = &XooslaLoad::getHandler( 'groupperm' );
        if ( $groups && is_object( $gperm_handler ) ) {
            $ret = $gperm_handler->checkRight( $this->_perm_name, $item_id , $groups, $this->_mod_id );
            return $ret;
        }
        return false;
    }

    /**
     * XooslaPermissions::getAdmin()
     *
     * @param mixed $item_id
     * @param mixed $isNew
     * @return
     */
    function getAdmin( $item_id = 0, $isNew = null ) {
        $gperm_handler = &XooslaLoad::getHandler( 'groupperm' );
        $groups = $gperm_handler->getGroupIds( $this->_perm_name, $item_id, $this->_mod_id );
        if ( !count( $groups ) && $isNew == true ) {
            $groups = array( 0 => 1, 1 => 2 );
        }
        return $groups;
    }

    /**
     * XooslaPermissions::doDelete()
     *
     * @param mixed $item_id
     * @return
     */
    function doDelete( $item_id = 0 ) {
        $gperm_handler = &xoops_getmodulehandler( 'groupperm', 'xooslacore' );
        if ( is_object( $gperm_handler ) ) {
            $gperm_handler->deleteByModule( $this->_mod_id, $this->_perm_name, $item_id );
        }
        return false;
    }
}

?>