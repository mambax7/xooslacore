<?php namespace XoopsModules\Xooslacore\Core;

/**
 * Name:
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     : WF-Candy
 * @subpackage :
 * @since      : v1.00
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\Request;
use XoopsModules\Xooslacore;
use XoopsModules\Xooslacore\Core;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_load('object');

defined('XOBJ_DTYPE_BOOL') || define('XOBJ_DTYPE_BOOL', 10000);
defined('XOBJ_DTYPE_DATE') || define('XOBJ_DTYPE_DATE', 10001);
defined('XOBJ_DTYPE_CONTENT') || define('XOBJ_DTYPE_CONTENT', 10002);
defined('XOBJ_DTYPE_USER') || define('XOBJ_DTYPE_USER', 10003);
defined('XOBJ_DTYPE_IMAGE') || define('XOBJ_DTYPE_IMAGE', 10004);
defined('XOBJ_DTYPE_IPADDRESS') || define('XOBJ_DTYPE_IPADDRESS', 10005);


/**
 * XooslaModel
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaObjectHandler extends \XoopsObjectHandler
{
    public $tableName;
    public $className;
    public $keyName;
    public $tkeyName;
    public $ckeyName;
    public $identifierName;
    public $groupName;
    public $doPermissions;
    public $_errors = [];
    public $userGroups;

    /**
     * XooslaObjectHandler::XooslaObjectHandler()
     * @param null|\XoopsDatabase $db
     * @param string        $tableName
     * @param string        $className
     * @param string        $keyName
     * @param bool          $identifierName
     * @param string        $groupName
     */
    public function __construct(
        \XoopsDatabase $db,
        $tableName = '',
        $className = '',
        $keyName = '',
        $identifierName = false,
        $groupName = ''
    ) {
        static $db;
        if (null === $db) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
        }

        parent::__construct($db);
        $this->tableName = $db->prefix($tableName);
        $this->className = $className;
        // **//
        $this->identifierName = (false !== $identifierName) ? $identifierName : '';
        $this->groupName      = $groupName;
        $this->userGroups     = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
        $this->doPermissions  = ('' != $this->groupName && !in_array(1, $this->userGroups)) ? 1 : 0;
        $this->keyName        = $keyName;
        $this->ckeyName       = $this->doPermissions ? 'c.' . $keyName : $keyName;
        $this->tkeyName       = null;
    }

    /**
     * XooslaObjectHandler::setPermission()
     *
     * @param mixed $value
     */
    public function setPermission($value = true)
    {
        $this->doPermissions = $value;
    }

    /**
     * XooslaObjectHandler::setTempKeyName()
     *
     * @param mixed $value
     */
    public function setTempKeyName($value)
    {
        $this->tkeyName = $value;
    }

    /**
     * XooslaObjectHandler::getTempKeyName()
     *
     * @return null
     */
    public function getTempKeyName()
    {
        return $this->tkeyName;
    }

    /**
     * XooslaObjectHandler::unsetTempKeyName()
     *
     */
    public function unsetTempKeyName()
    {
        unset($this->tkeyName);
    }

    /**
     * XooslaObjectHandler::create()
     *
     * @param mixed $isNew
     * @return bool|\XoopsObject
     */
    public function &create($isNew = true)
    {
        $obj = new $this->className();
        if (!is_object($obj)) {
            return false;
        } else {
            if (true === $isNew) {
                $obj->setNew();
            }

            return $obj;
        }
    }

    /**
     * XooslaObjectHandler::get()
     *
     * @param int|string $id
     * @param mixed      $as_object
     * @param string     $keyName
     * @return bool|mixed|\XoopsObject
     */
    public function get($id = 0, $as_object = true, $keyName = '')
    {
        $criteria = new \CriteriaCompo();
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
            if (is_array($this->keyName)) {
                for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                    $criteria->add(new \Criteria($this->keyName[$i], $id[$i]));
                }
            } else {
                $id = (int)$id;
                if ($id > 0) {
                    $criteria = new \Criteria($this->ckeyName, $id);
                } else {
                    $criteria = new \Criteria((string)$keyName, 1);
                }
            }
            $criteria->setLimit(1);
        }
        $obj_array = $this->getObjects($criteria, false, $as_object);
        if (!is_array($obj_array) || 1 != count($obj_array)) {
            return false;
        }

        return $obj_array[0];
    }

    /**
     * XooslaObjectHandler::getObjects()
     *
     * @param mixed $criteria
     * @param mixed $id_as_key
     * @param mixed $as_object
     * @param mixed $return_error
     * @return array|bool
     */
    public function getObjects($criteria = null, $id_as_key = false, $as_object = true, $return_error = false)
    {
        $ret   = [];
        $limit = $start = 0;
        if ($this->doPermissions) {
            $sql = 'SELECT DISTINCT c.* FROM ' . $this->tableName . ' c LEFT JOIN ' . $this->db->prefix('group_permission') . " l   ON l.gperm_itemid = $this->ckeyName WHERE ( l.gperm_name = '$this->groupName' AND l.gperm_groupid IN ( " . implode(',', $this->userGroups) . ' )    )';
        } else {
            $sql = 'SELECT * FROM ' . $this->tableName;
        }
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
            if ($this->doPermissions) {
                $sql .= ' AND ' . $criteria->render();
            } else {
                $sql .= ' ' . $criteria->renderWhere();
            }
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return false;
        } else {
            $result = $this->convertResultSet($result, $id_as_key, $as_object);

            return $result;
        }
    }

    /**
     * XooslaObjectHandler::convertResultSet()
     *
     * @param mixed $result
     * @param mixed $id_as_key
     * @param mixed $as_object
     * @return array|bool
     */
    public function &convertResultSet($result, $id_as_key = false, $as_object = true)
    {
        $ret = [];
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $obj = $this->create(false);
            if (!$obj) {
                return false;
            }
            $obj->assignVars($myrow);
            if (!$id_as_key) {
                if ($as_object) {
                    $ret[] =& $obj;
                } else {
                    $row  = [];
                    $vars = $obj->getVars();
                    foreach (array_keys($vars) as $i) {
                        $row[$i] = $obj->getVar($i);
                    }
                    $ret[] = $row;
                }
            } else {
                if ($as_object) {
                    $ret[$myrow[$this->keyName]] =& $obj;
                } else {
                    $row  = [];
                    $vars = $obj->getVars();
                    foreach (array_keys($vars) as $i) {
                        $row[$i] = $obj->getVar($i);
                    }
                    $ret[$myrow[$this->keyName]] = $row;
                }
            }
            unset($obj);
        }

        return $ret;
    }

    /**
     * XooslaObjectHandler::getList()
     *
     * @param mixed  $criteria
     * @param string $querie
     * @param mixed  $show
     * @param mixed  $doCriteria
     * @return array|bool
     */
    public function getList($criteria = null, $querie = '*', $show = null, $doCriteria = true)
    {
        $ret   = [];
        $limit = $start = 0;
        if ($this->doPermissions) {
            if ($querie) {
                $query = $querie;
            } else {
                $query = $this->ckeyName;
                if (!empty($this->identifierName)) {
                    $query .= ', c.' . $this->identifierName;
                }
            }
            $sql = 'SELECT DISTINCT c.* FROM ' . $this->tableName . ' c LEFT JOIN ' . $this->db->prefix('group_permission') . " l ON l.gperm_itemid = $this->ckeyName WHERE ( l.gperm_name = '$this->groupName' AND l.gperm_groupid IN ( " . implode(',', $this->userGroups) . ' ))';
        } else {
            if ($querie) {
                $query = $querie;
            } else {
                $query = $this->ckeyName;
                if (!empty($this->identifierName)) {
                    $query .= ', ' . $this->identifierName;
                }
            }
            $sql = 'SELECT ' . $query . ' FROM ' . $this->tableName;
        }

        if (false !== $doCriteria) {
            if (null === $criteria) {
                $criteria = new \CriteriaCompo();
            }
            if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
                if ('' == $criteria->getSort()) {
                    $criteria->setSort($this->identifierName);
                }
                if ($this->doPermissions) {
                    $sql .= ' AND ' . $criteria->render();
                } else {
                    $sql .= ' ' . $criteria->renderWhere();
                }
                if ('' != $criteria->getSort()) {
                    $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
                }
                $limit = $criteria->getLimit();
                $start = $criteria->getStart();
            }
        }
        if (!$result = $this->db->query($sql, $limit, $start)) {
            return false;
        }
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            if ($this->getTempKeyName()) {
                $ret[$myrow[$this->tkeyName]] = empty($this->identifierName) ? '' : htmlspecialchars($myrow[$this->identifierName], ENT_QUOTES);
            } else {
                $ret[$myrow[$this->keyName]] = empty($this->identifierName) ? '' : htmlspecialchars($myrow[$this->identifierName], ENT_QUOTES);
            }
        }
        $this->unsetTempKeyName();

        return $ret;
    }

    /**
     * XooslaObjectHandler::getCount()
     *
     * @param mixed  $criteria
     * @param string $querie
     * @return bool
     */
    public function getCount($criteria = null, $querie = '*')
    {
        if ($this->doPermissions) {
            $sql = "SELECT ${querie} FROM " . $this->tableName . ' c LEFT JOIN ' . $this->db->prefix('group_permission') . " l ON l.gperm_itemid = $this->ckeyName WHERE ( l.gperm_name = '$this->groupName' AND l.gperm_groupid IN ( " . implode(',', $this->userGroups) . ' ) )';
        } else {
            $sql = "SELECT ${querie} FROM " . $this->tableName;
        }

        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
            if ($this->doPermissions) {
                $sql .= ' AND ' . $criteria->render();
            } else {
                $sql .= ' ' . $criteria->renderWhere();
            }
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if ('*' !== $querie) {
            return $this->db->fetchArray($result);
        } else {
            $count = $this->db->getRowsNum($result);
        }

        return $count;
    }

    /**
     * XooslaObjectHandler::insert()
     *
     * @param \XoopsObject $obj
     * @param mixed        $checkObject
     * @param mixed        $andclause
     * @param mixed        $force
     * @return bool|void
     */
    public function insert(\XoopsObject $obj, $checkObject = true, $andclause = null, $force = false)
    {
        if (true === $checkObject) {
            if (!is_object($obj) || !is_a($obj, $this->className)) {
                $this->setErrors('is not an object');

                return false;
            }
            if (!$obj->isDirty()) {
                $this->setErrors('Is not dirty');

                return false;
            }
        }
        if (!$obj->cleanVars()) {
            $this->setErrors($obj->getErrors());

            return false;
        }

        if ($obj->isNew()) {
            $obj->cleanVars[$this->keyName] = '';
            foreach ($obj->cleanVars as $k => $v) {
                $cleanvars[$k] = (XOBJ_DTYPE_INT == $obj->vars[$k]['data_type']) ? (int)$v : $this->db->quoteString($v);
            }
            $sql = 'INSERT INTO ' . $this->tableName . ' (`' . implode('`, `', array_keys($cleanvars)) . '`) VALUES (' . implode(',', array_values($cleanvars)) . ')';
        } else {
            $sql = 'UPDATE ' . $this->tableName . ' SET';
            foreach ($obj->cleanVars as $k => $v) {
                if ($notfirst !== null) {
                    $sql .= ', ';
                }
                if (XOBJ_DTYPE_INT == $obj->vars[$k]['data_type']) {
                    $sql .= ' ' . $k . ' = ' . (int)$v;
                } else {
                    $sql .= ' ' . $k . ' = ' . $this->db->quoteString($v);
                }
                $notfirst = true;
            }
            $sql .= ' WHERE ' . $this->keyName . " = '" . $obj->getVar($this->keyName) . "'";
            if ($andclause) {
                $sql .= $andclause;
            }
        }
        $result = (true === $force) ? $this->db->queryF($sql) : $this->db->query($sql);
        if (!$result) {
            $this->setErrors('Error');

            return false;
        }
        if ($obj->isNew() && !is_array($this->keyName)) {
            $obj->assignVar($this->keyName, $this->db->getInsertId());
        }

        return true;
    }

    /**
     * XooslaObjectHandler::updateAll()
     *
     * @param mixed   $fieldname
     * @param integer $fieldvalue
     * @param mixed   $criteria
     * @param mixed   $force
     * @return bool
     */
    public function updateAll($fieldname, $fieldvalue = 0, $criteria = null, $force = true)
    {
        if (is_array($fieldname) && 0 == $fieldvalue) {
            $set_clause = '';
            foreach ($fieldname as $key => $value) {
                if ($notfirst !== null) {
                    $set_clause .= ', ';
                }
                $set_clause .= is_numeric($key) ? ' ' . $key . ' = ' . $value : ' ' . $key . ' = ' . $this->db->quoteString($value);
                $notfirst   = true;
            }
        } else {
            $set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . $this->db->quoteString($fieldvalue);
        }
        $sql = 'UPDATE ' . $this->tableName . ' SET ' . $set_clause;
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * XooslaObjectHandler::delete()
     *
     * @param \XoopsObject $obj
     * @param mixed        $force
     * @return bool|void
     */
    public function delete(\XoopsObject $obj, $force = false)
    {
        if (!is_object($obj) || !is_a($obj, $this->className)) {
            return false;
        }
        if (is_array($this->keyName)) {
            $clause = [];
            for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                $clause[] = $this->keyName[$i] . ' = ' . $obj->getVar($this->keyName[$i]);
            }
            $whereclause = implode(' AND ', $clause);
        } else {
            $whereclause = $this->keyName . ' = ' . $obj->getVar($this->keyName);
        }
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE ' . $whereclause;
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * XooslaObjectHandler::updateCounter()
     *
     * @param mixed $fieldname
     * @param mixed $criteria
     * @param mixed $force
     * @return bool
     */
    public function updateCounter($fieldname, $criteria = null, $force = true)
    {
        $set_clause = $fieldname . '=' . $fieldname . '+1';
        $sql        = 'UPDATE ' . $this->tableName . ' SET ' . $set_clause;
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }
}
