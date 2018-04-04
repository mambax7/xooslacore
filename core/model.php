<?php
/**
 * Name:
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     : WF-Candy
 * @subpackage :
 * @since      : v1.00
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license    : GNU/LGPL, see docs/license.php
 */

use Xmf\Request;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_load('object');

defined('XOBJ_DTYPE_BOOL') or define('XOBJ_DTYPE_BOOL', 10000);
defined('XOBJ_DTYPE_DATE') or define('XOBJ_DTYPE_DATE', 10001);
defined('XOBJ_DTYPE_CONTENT') or define('XOBJ_DTYPE_CONTENT', 10002);
defined('XOBJ_DTYPE_USER') or define('XOBJ_DTYPE_USER', 10003);
defined('XOBJ_DTYPE_IMAGE') or define('XOBJ_DTYPE_IMAGE', 10004);
defined('XOBJ_DTYPE_IPADDRESS') or define('XOBJ_DTYPE_IPADDRESS', 10005);

/**
 * XooslaObject
 *
 * @package
 * @author    John
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaObject extends XoopsObject
{
    /**
     * XooslaObject::checkRequired()
     * @return bool
     * @internal param mixed $v
     * @internal param mixed $key
     * @internal param mixed $value
     */
    public function checkRequired()
    {
        if ($this->v['required'] && empty($this->v['value'])) {
            $this->setErrors(sprintf(XL_ER_VALUE_REQUIRED, $this->v['key']));

            return false;
        }

        return true;
    }

    /**
     * XooslaObject::checkLength()
     * @return bool
     */
    public function checkLength()
    {
        if ($this->v['maxlength'] && strlen($this->v['value']) > $this->v['maxlength']) {
            $this->setErrors(sprintf(XL_ER_VALUE_SHORTERTHAN, $this->v['key'], $this->v['maxlength']));

            return false;
        }

        return true;
    }

    /**
     * XooslaObject::doSanitize()
     *
     * @param $param
     * @internal param string $type
     */
    public function doSanitize($param)
    {
        $this->v['value'] = XooslaRequest::doSanitize($this->v['value'], $param);
    }

    /**
     * XooslaObject::doValidate()
     *
     * @param        $param
     * @param string $arg
     * @return bool
     */
    public function doValidate($param, $arg = XL_ER_FAILED_VALIDATION)
    {
        if (!XooslaRequest::doValidate($this->v['value'], $param)) {
            $this->setErrors(sprintf($arg, $this->v['key']));
        }

        return true;
    }

    /**
     * XooslaObject::cleanVars()
     * @return bool
     */
    public function cleanVars2()
    {
        foreach ($this->vars as $key => $value) {
            if (isset($value['changed'])) {
                /**
                 */
                $this->v['data_type'] = $value['data_type'];
                $this->v['value']     = $value['value'];
                $this->v['key']       = $key;
                $this->v['required']  = $value['required'];
                $this->v['maxlength'] = (int)$value['maxlength'];
                $this->v['not_gpc']   = isset($value['not_gpc']) ? true : false;
                /**
                 * Switch
                 */
                switch ($this->v['data_type']) {
                    case XOBJ_DTYPE_TXTBOX:
                        if (!$this->checkRequired() || !$this->checkLength()) {
                            continue;
                        }
                        $this->doSanitize('textbox');
                        break;

                    case XOBJ_DTYPE_TXTAREA:
                        if (!$this->checkRequired()) {
                            continue;
                        }
                        // $this->doSanitize( 'textarea' );
                        // $this->v['value'] = html_entity_decode( $this->v['value'] );
                        break;

                    case XOBJ_DTYPE_EMAIL:
                        if (!$this->checkRequired() || !$this->checkLength()
                            || !$this->doValidate('email', XL_ER_EMAIL_INVALID)) {
                            continue;
                        }
                        $this->doSanitize('email');
                        break;

                    case XOBJ_DTYPE_URL:
                        if (!$this->checkRequired() || !$this->checkLength()
                            || !$this->doValidate('url', XL_ER_URL_INVALID)) {
                            continue;
                        }
                        $this->doSanitize('url');
                        break;

                    case XOBJ_DTYPE_DATE:
                        $this->v['value'] = (false === strtotime($this->v['value'])) ? (int)$this->v['value'] : strtotime($this->v['value']);
                        if (!$this->checkRequired() || !$this->checkLength()) {
                            continue;
                        }
                        $this->doSanitize('int');
                        break;

                    case XOBJ_DTYPE_IMAGE:
                        if (!$this->checkRequired() || !$this->checkLength()) {
                            continue;
                        }
                        $this->doSanitize('textbox');
                        $imgWidth  = XooslaRequest::doRequest($_REQUEST, 'imgwidth', '', 'int');
                        $imgHeight = XooslaRequest::doRequest($_REQUEST, 'imgheight', '', 'int');
                        /**
                         * Clean Image
                         */
                        $cleanImage = explode('|', $this->v['value']);
                        if ($cleanImage[0]) {
                            $image            = $cleanImage[0];
                            $imgWidth         = $imgWidth ?: $cleanImage[1];
                            $imgHeight        = $imgHeight ?: $cleanImage[2];
                            $this->v['value'] = "{$image}|{$imgWidth}|{$imgHeight}";
                        } else {
                            $this->v['value'] = '||';
                        }
                        break;

                    case XOBJ_DTYPE_IPADDRESS:
                        if (!$this->checkRequired()) {
                            continue;
                        }
                        $this->doSanitize('ip');
                        break;

                    case XOBJ_DTYPE_USER:
                        if (!$this->checkRequired()) {
                            continue;
                        }
                        $this->doSanitize('int');
                        break;

                    case XOBJ_DTYPE_INT:
                        if (!$this->checkRequired()) {
                            continue;
                        }
                        $this->doSanitize('int');
                        break;

                    case XOBJ_DTYPE_ARRAY:
                        if (!$this->checkRequired()) {
                            continue;
                        }
                        $this->v['value'] = serialize($this->v['value']);
                        break;

                    case XOBJ_DTYPE_FLOAT:
                        if (!$this->checkRequired()) {
                            continue;
                        }
                        $this->doSanitize('float');
                        break;

                    case XOBJ_DTYPE_DECIMAL:
                        if (!$this->checkRequired()) {
                            continue;
                        }
                        $this->doSanitize('decimal');
                        break;

                    case XOBJ_DTYPE_BOOL:
                        $this->doSanitize('bool');
                        break;

                    case XOBJ_DTYPE_SOURCE:
                        $this->v['value'] = $this->v['value'];
                        break;

                    default:
                        $this->v['value'] = '';
                        break;
                }
            }
            $this->cleanVars[$this->v['key']] = str_replace('\\"', '"', $this->v['value']);
            unset($this->v);
        }
        if (count($this->_errors)) {
            echo $this->getHtmlErrors();
            exit();
        }
        $this->unsetDirty();

        return true;
    }

    /**
     * XooslaObject::getVars()
     *
     * @param        $key
     * @param string $format
     * @return array|mixed|null|string
     */
    public function getVars2($key, $format = 's')
    {
        $ret = null;
        if (!isset($this->vars[$key])) {
            return $ret;
        }
        switch ($this->vars[$key]['data_type']) {
            case XOBJ_DTYPE_TXTBOX:
                $ts = \MyTextSanitizer::getInstance();
                switch (strtolower($format)) {
                    case 's':
                        return $ts->stripSlashesGPC($ret);
                        break 1;
                    case 'e':
                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                    case 'n':
                        break 1;
                }
                break;

            case XOBJ_DTYPE_TXTAREA:
                switch (strtolower($format)) {
                    case 's':
                        return $ts->displayTarea($ret, (int)$this->vars['dohtml']['value'], (int)$this->vars['dosmiley']['value'], (int)$this->vars['doxcode']['value'], (int)$this->vars['doimage']['value'], (int)$this->vars['dobr']['value']);
                        break 1;
                    case 'e':
                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                    case 'n':
                        break 1;
                }
                break;

            case XOBJ_DTYPE_EMAIL:
                switch (strtolower($format)) {
                    case 's':
                        return $ts->stripSlashesGPC($ret);
                        break 1;
                    case 'e':
                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                        break 1;
                    case 'n':
                        break 1;
                }
                break;

            case XOBJ_DTYPE_URL:
                switch (strtolower($format)) {
                    case 's':
                        return $ts->stripSlashesGPC($ret);
                    case 'e':
                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                        break 1;
                    case 'n':
                        break 1;
                }
                break;

            case XOBJ_DTYPE_DATE:
                switch (strtolower($format)) {
                    case 's':
                        return (strlen($ret) >= 4) ? formatTimestamp($ret) : '';
                        break 1;
                    case 'e':
                        return (int)$ret;
                        break 1;
                    case 'n':
                    default:
                        break 1;
                }
                break;

            case XOBJ_DTYPE_IMAGE:
                switch (strtolower($format)) {
                    case 's':
                        $reti = explode('|', $ret);
                        $ret  = (count($reti) > 0) ? $reti[0] : $ret;
                        unset($reti);
                        break 1;
                    case 'e':
                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));

                        return 1;
                    case 'n':
                        break 1;
                }
                break;

            case XOBJ_DTYPE_IPADDRESS:
                switch (strtolower($format)) {
                    case 's':
                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                    case 'e':
                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                        break 1;
                    case 'n':
                        break 1;
                }
                break;

            case XOBJ_DTYPE_USER:
                switch (strtolower($format)) {
                    case 's':
                        XoopsLoad::load('userutility');

                        return XoopsUserUtility::getUnameFromId($ret, 0, 1);
                        break 1;
                    case 'e':
                        break 1;
                    case 'n':
                    default:
                        break 1;
                }
                break;

            case XOBJ_DTYPE_INT:
                break;

            case XOBJ_DTYPE_ARRAY:
                if (!is_array($ret)) {
                    if ('' != $ret) {
                        $ret = unserialize($ret);
                    }
                    $ret = is_array($ret) ? $ret : [];
                }
                break;

            case XOBJ_DTYPE_FLOAT:
                break;

            case XOBJ_DTYPE_DECIMAL:
                break;

            case XOBJ_DTYPE_BOOL:
                break;

            case XOBJ_DTYPE_SOURCE:
                switch (strtolower($format)) {
                    case 's':
                        return $ts->stripSlashesGPC($ret);
                        break 1;
                    case 'e':
                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                        break 1;
                    case 'n':
                        break 1;
                }
                break;

            default:
                if ('' != $this->vars[$key]['options'] && '' != $ret) {
                    switch (strtolower($format)) {
                        case 's':
                            $selected = explode('|', $ret);
                            $options  = explode('|', $this->vars[$key]['options']);
                            $i        = 1;
                            $ret      = [];
                            foreach ($options as $op) {
                                if (in_array($i, $selected)) {
                                    $ret[] = $op;
                                }
                                ++$i;
                            }

                            return implode(', ', $ret);
                        case 'e':
                            $ret = explode('|', $ret);
                            break 1;
                        default:
                            break 1;
                    }
                }
                break;
        }

        return $ret;
    }

    /**
     * XooslaObject::getTextBox()
     *
     * @param mixed   $id
     * @param mixed   $name
     * @param integer $size
     * @param mixed   $max
     * @return string
     */
    public function getTextBox($id = null, $name = null, $size = 25, $max = 255)
    {
        return '<input type="text" name="' . $name . '[' . $this->getVar($id) . ']" value="' . $this->getVar($name) . '" size="' . $size . '" maxlength="' . $max . '">';
    }

    /**
     * wfc_Page::getYesNobox()
     *
     * @param mixed $id
     * @param mixed $name
     * @param mixed $value
     * @return string
     */
    public function getYesNobox($id = null, $name = null, $value = null)
    {
        $i        = $this->getVar($id);
        $ret      = '<input type="radio" name="' . $name . '[' . $i . ']" value="1"';
        $selected = $this->getVar($name);
        if (isset($selected) && (1 == $selected)) {
            $ret .= ' checked';
        }
        $ret      .= '>' . _YES . ' ';
        $ret      .= '<input type="radio" name="' . $name . '[' . $i . ']" value="0"';
        $selected = $this->getVar($name);
        if (isset($selected) && (0 == $selected)) {
            $ret .= ' checked';
        }
        $ret .= '>' . _NO . ' ';

        return $ret;
    }

    /**
     * XoopsObject::getCheckBox()
     *
     * @param mixed $id
     * @return string
     */
    public function getCheckBox($id = null)
    {
        return '<input type="checkbox" value="' . $this->getVar($id) . '" name="checkbox[]" onclick="isChecked(this.checked);">';
    }

    /**
     * XooslaObjectHandler::formEdit()
     *
     * @param mixed $value
     * @return bool
     */
    public function formEdit($value = null)
    {
        XooslaLoad('modules.xooslacore.core.class.xooslaformloader');
        if ($value) {
            include XOOPS_ROOT_PATH . DS . 'modules' . DS . $GLOBALS['xoopsModule']->getVar('dirname') . DS . 'class/classforms/form_' . strtolower($value) . '.php';

            return $form;
        }

        return false;
    }
}

/**
 * XooslaModel
 *
 * @package
 * @author    John
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaObjectHandler extends XoopsObjectHandler
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

    /**
     * XooslaObjectHandler::XooslaObjectHandler()
     * @param XoopsDatabase $db
     * @param string        $tableName
     * @param string        $className
     * @param string        $keyName
     * @param bool          $identifierName
     * @param string        $groupName
     */
    public function __construct(
        $db,
        $tableName = '',
        $className = '',
        $keyName = '',
        $identifierName = false,
        $groupName = ''
    ) {
        static $db;
        if (!isset($db)) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
        }

        parent::__construct($db);
        $this->tableName = $db->prefix($tableName);
        $this->className = $className;
        // **//
        $this->identifierName = (false !== $identifierName) ? $identifierName : '';
        $this->groupName      = ('' != $groupName) ? $groupName : '';
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
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            if (is_array($this->keyName)) {
                for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                    $criteria->add(new \Criteria($this->keyName[$i], $id[$i]));
                }
            } else {
                $id = (int)$id;
                if ($id > 0) {
                    $criteria = new \Criteria($this->ckeyName, $id);
                } else {
                    $criteria = new \Criteria((string)($keyName), 1);
                }
            }
            $criteria->setLimit(1);
        }
        $obj_array =& $this->getObjects($criteria, false, $as_object);
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
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
     * @return array
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
            if (null == $criteria) {
                $criteria = new \CriteriaCompo();
            }
            if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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

        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
     * @param XoopsObject $obj
     * @param mixed       $checkObject
     * @param mixed       $andclause
     * @param mixed       $force
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
                if (isset($notfirst)) {
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
                if (isset($notfirst)) {
                    $set_clause .= ', ';
                }
                $set_clause .= is_numeric($key) ? ' ' . $key . ' = ' . $value : ' ' . $key . ' = ' . $this->db->quoteString($value);
                $notfirst   = true;
            }
        } else {
            $set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . $this->db->quoteString($fieldvalue);
        }
        $sql = 'UPDATE ' . $this->tableName . ' SET ' . $set_clause;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
     * @param XoopsObject $obj
     * @param mixed       $force
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
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
