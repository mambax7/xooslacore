<?php namespace XoopsModules\Xooslacore;

/**
 * Name: class.permissions.php
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     :
 * @subpackage :
 * @since      : v1.0.0
 * @author     John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright  : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license    : GNU/LGPL, see docs/license.php
 */

use XoopsModules\Xooslacore;
use XoopsModules\Xooslacore\Core;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * XooslaPermissionsHandler
 *
 * @package
 * @author    Catzwolf
 * @copyright Copyright (c) 2005
 * @version   $Id: class.permissions.php,v 1.2 2007/03/30 22:05:45 catzwolf Exp $
 * @access    public
 */
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

/**
 * XooslaPermissions
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaPermissions extends \XoopsGroupPermForm
{
    public $db;
    public $tableName;
    public $mod_id = 0;
    public $perm_name;
    public $perm_descript;
    public $groups;

    /**
     * XooslaPermissions::XooslaPermissions()
     *
     * @internal param string $table
     * @internal param string $_perm_name
     * @internal param string $_perm_descript
     */
    public function __construct()
    {
    }

    /**
     * XooslaPermissions::getGroups()
     *
     * @return
     */
    public function getGroups()
    {
        static $grouplist;

        if (!$grouplist) {
            $memberHandler = xoops_getHandler('member');
            $grouplist     = $memberHandler->getGroupList();
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
     * @param mixed  $mod_id
     */
    public function setPermissions($table = '', $perm_name = '', $perm_descript = '', $mod_id = 0)
    {
        if (!empty($table)) {
            $this->db        = \XoopsDatabaseFactory::getDatabaseConnection();
            $this->tableName = $this->db->prefix($table);
        }
        $this->_mod_id        = $mod_id;
        $this->_perm_name     = $perm_name;
        $this->_perm_descript = $perm_descript;
    }

    /**
     * XooslaPermissions::XooslaPermissions_render()
     *
     * @param array $arr
     * @return string|void
     */
    public function render($arr = [])
    {
        if ($this->_perm_descript) {
            $perm_descript = $this->_perm_descript;
        } else {
            $perm_descript = null;
        }

        $sql = "SELECT {$arr['cid']}";
        if (!empty($arr['pid'])) {
            $sql = ", {$arr['pid']}";
        }
        $sql .= ", {$arr['title']} FROM " . $this->tableName;
        if (!empty($arr['where'])) {
            $sql .= " WHERE {$arr['where']}=" . $this->_mod_id;
        }
        if (!empty($arr['order'])) {
            $sql .= " ORDER BY {$arr['order']}";
        }
        if (!$result = $this->db->query($sql)) {
            $error = $this->db->error() . ' : ' . $this->db->errno();
            trigger_error($error);
        }

        $ret       = '';
        $form_info = new \XoopsGroupPermForm('', $this->_mod_id, $this->_perm_name, $this->_perm_descript);
        if ($this->db->getRowsNum($result)) {
            while (false !== ($row_arr = $this->db->fetcharray($result))) {
                if (!empty($arr['pid'])) {
                    $form_info->addItem($row_arr[$arr['cid']], $row_arr[$arr['title']], $row_arr[$arr['pid']]);
                } else {
                    $form_info->addItem($row_arr[$arr['cid']], $row_arr[$arr['title']], 0);
                }
            }
            $ret = $form_info->render();
        }
        unset($form_info);
        echo $ret;
    }

    /**
     * XooslaPermissions::save()
     *
     * @param array $groupids
     * @param mixed $item_id
     * @return bool
     * @internal param array $groups
     */
    public function save($groupids = [], $item_id = 0)
    {
        if (!is_array($groupids) || !count($groupids) || 0 == (int)$item_id) {
            return false;
        }

        /**
         * Save the new permissions
         */
        $grouppermHandler = Core\XooslaLoad::getHandler('GroupPerm');
        if (is_object($grouppermHandler) && !empty($grouppermHandler)) {
            /**
             * First, if the permissions are already there, delete them
             */
            $grouppermHandler->deleteByModule($this->_mod_id, $this->_perm_name, $item_id);
            foreach ($groupids as $groupid) {
                if (!$grouppermHandler->addRight($this->_perm_name, $item_id, $groupid, $this->_mod_id)) {
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
     * @return bool
     */
    public function get($item_id = 0)
    {
        $groups       = $this->getGroups();
        $grouppermHandler = Core\XooslaLoad::getHandler('GroupPerm');
        if ($groups && is_object($grouppermHandler)) {
            $ret = $grouppermHandler->checkRight($this->_perm_name, $item_id, $groups, $this->_mod_id);

            return $ret;
        }

        return false;
    }

    /**
     * XooslaPermissions::getAdmin()
     *
     * @param mixed $item_id
     * @param mixed $isNew
     * @return array
     */
    public function getAdmin($item_id = 0, $isNew = null)
    {
        $grouppermHandler = Core\XooslaLoad::getHandler('GroupPerm');
        $groups       = $grouppermHandler->getGroupIds($this->_perm_name, $item_id, $this->_mod_id);
        if (!count($groups) && true === $isNew) {
            $groups = [0 => 1, 1 => 2];
        }

        return $groups;
    }

    /**
     * XooslaPermissions::doDelete()
     *
     * @param mixed $item_id
     * @return bool
     */
    public function doDelete($item_id = 0)
    {
        $grouppermHandler = xoops_getModuleHandler('groupperm', 'xooslacore');
        if (is_object($grouppermHandler)) {
            $grouppermHandler->deleteByModule($this->_mod_id, $this->_perm_name, $item_id);
        }

        return false;
    }
}
