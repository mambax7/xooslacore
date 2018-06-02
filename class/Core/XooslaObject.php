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
 * @copyright  : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license    : GNU/LGPL, see docs/license.php
 */

use Xmf\Request;
use XoopsModules\Xooslacore;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_load('object');

defined('XOBJ_DTYPE_BOOL') || define('XOBJ_DTYPE_BOOL', 10000);
defined('XOBJ_DTYPE_DATE') || define('XOBJ_DTYPE_DATE', 10001);
defined('XOBJ_DTYPE_CONTENT') || define('XOBJ_DTYPE_CONTENT', 10002);
defined('XOBJ_DTYPE_USER') || define('XOBJ_DTYPE_USER', 10003);
defined('XOBJ_DTYPE_IMAGE') || define('XOBJ_DTYPE_IMAGE', 10004);
defined('XOBJ_DTYPE_IPADDRESS') || define('XOBJ_DTYPE_IPADDRESS', 10005);

/**
 * XooslaObject
 *
 * @package
 * @author    John
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaObject extends \XoopsObject
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
        $this->v['value'] = \XoopsModules\Xooslacore\XooslaRequest::doSanitize($this->v['value'], $param);
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
        if (!\XoopsModules\Xooslacore\XooslaRequest::doValidate($this->v['value'], $param)) {
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
                            continue 2;
                        }
                        $this->doSanitize('textbox');
                        break;

                    case XOBJ_DTYPE_TXTAREA:
                        if (!$this->checkRequired()) {
                            continue 2;
                        }
                        // $this->doSanitize( 'textarea' );
                        // $this->v['value'] = html_entity_decode( $this->v['value'] );
                        break;

                    case XOBJ_DTYPE_EMAIL:
                        if (!$this->checkRequired() || !$this->checkLength()
                            || !$this->doValidate('email', XL_ER_EMAIL_INVALID)) {
                            continue 2;
                        }
                        $this->doSanitize('email');
                        break;

                    case XOBJ_DTYPE_URL:
                        if (!$this->checkRequired() || !$this->checkLength()
                            || !$this->doValidate('url', XL_ER_URL_INVALID)) {
                            continue 2;
                        }
                        $this->doSanitize('url');
                        break;

                    case XOBJ_DTYPE_DATE:
                        $this->v['value'] = (false === strtotime($this->v['value'])) ? (int)$this->v['value'] : strtotime($this->v['value']);
                        if (!$this->checkRequired() || !$this->checkLength()) {
                            continue 2;
                        }
                        $this->doSanitize('int');
                        break;

                    case XOBJ_DTYPE_IMAGE:
                        if (!$this->checkRequired() || !$this->checkLength()) {
                            continue 2;
                        }
                        $this->doSanitize('textbox');
                        $imgWidth  = \XoopsModules\Xooslacore\XooslaRequest::doRequest($_REQUEST, 'imgwidth', '', 'int');
                        $imgHeight = \XoopsModules\Xooslacore\XooslaRequest::doRequest($_REQUEST, 'imgheight', '', 'int');
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
                            continue 2;
                        }
                        $this->doSanitize('ip');
                        break;

                    case XOBJ_DTYPE_USER:
                        if (!$this->checkRequired()) {
                            continue 2;
                        }
                        $this->doSanitize('int');
                        break;

                    case XOBJ_DTYPE_INT:
                        if (!$this->checkRequired()) {
                            continue 2;
                        }
                        $this->doSanitize('int');
                        break;

                    case XOBJ_DTYPE_ARRAY:
                        if (!$this->checkRequired()) {
                            continue 2;
                        }
                        $this->v['value'] = serialize($this->v['value']);
                        break;

                    case XOBJ_DTYPE_FLOAT:
                        if (!$this->checkRequired()) {
                            continue 2;
                        }
                        $this->doSanitize('float');
                        break;

                    case XOBJ_DTYPE_DECIMAL:
                        if (!$this->checkRequired()) {
                            continue 2;
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
                        \XoopsLoad::load('userutility');

                        return \XoopsUserUtility::getUnameFromId($ret, 0, 1);
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
        \XoopsModules\Xooslacore\Core\XooslaLoad::getInclude('modules.xooslacore.class.xooslaformloader');
        if ($value) {
            include XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/' . 'class/Form/' . ucfirst(strtolower($value)) . 'Form.php';
//            $className =   '\\XoopsModules\\' . ucfirst(strtolower($GLOBALS['xoopsModule']->getVar('dirname'))) . '\\Classforms\\' .ucfirst(strtolower($value)) . 'Form';
//            $form = new $className();
            return $form;
        }

        return false;
    }
}
