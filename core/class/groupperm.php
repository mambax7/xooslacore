<?php
/**
 * Name: class.groupperm.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: class.groupperm.php 0000 26/03/2009 04:33:10:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

xoops_load( 'XoopsGroupPermHandler' );

/**
 * XooslaGroupPermHandler
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaGroupPermHandler extends XoopsGroupPermHandler {
    /**
     * XooslaGroupPermHandler::insert()
     *
     * @param mixed $perm
     * @return
     */
    function insert( &$perm ) {
        if ( strtolower( get_class( $perm ) ) != 'xoopsgroupperm' ) {
            return false;
        }
        if ( !$perm->isDirty() ) {
            return true;
        }
        if ( !$perm->cleanVars() ) {
            return false;
        }
        foreach ( $perm->cleanVars as $k => $v ) {
            $ {
                $k} = $v;
        }
        if ( $perm->isNew() ) {
            $gperm_id = $this->db->genId( 'group_permission_gperm_id_seq' );
            $sql = sprintf( "INSERT INTO %s (gperm_id, gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (%u, %u, %u, %u, %s)", $this->db->prefix( 'group_permission' ), $gperm_id, $gperm_groupid, $gperm_itemid, $gperm_modid, $this->db->quoteString( $gperm_name ) );
        } else {
            $sql = sprintf( "UPDATE %s SET gperm_groupid = %u, gperm_itemid = %u, gperm_modid = %u WHERE gperm_id = %u", $this->db->prefix( 'group_permission' ), $gperm_groupid, $gperm_itemid, $gperm_modid, $gperm_id );
        }
        if ( !$result = $this->db->queryF( $sql ) ) {
            return false;
        }
        if ( empty( $gperm_id ) ) {
            $gperm_id = $this->db->getInsertId();
        }
        $perm->assignVar( 'gperm_id', $gperm_id );
        return true;
    }

    /**
     * XooslaGroupPermHandler::delete()
     *
     * @param mixed $perm
     * @return
     */
    function delete( &$perm ) {
        if ( strtolower( get_class( $perm ) ) != 'xoopsgroupperm' ) {
            return false;
        }
        $sql = sprintf( "DELETE FROM %s WHERE gperm_id = %u", $this->db->prefix( 'group_permission' ), $perm->getVar( 'gperm_id' ) );
        if ( !$result = $this->db->queryF( $sql ) ) {
            return false;
        }
        return true;
    }

    /**
     * XooslaGroupPermHandler::deleteAll()
     *
     * @param mixed $criteria
     * @return
     */
    function deleteAll( $criteria = null ) {
        $sql = sprintf( "DELETE FROM %s", $this->db->prefix( 'group_permission' ) );
        if ( isset( $criteria ) && is_subclass_of( $criteria, 'criteriaelement' ) ) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if ( !$result = $this->db->queryF( $sql ) ) {
            return false;
        }
        return true;
    }
}

?>