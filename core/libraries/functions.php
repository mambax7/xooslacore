<?php
/**
 * Name: functions.php
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
defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_loadLanguage('admin', 'xooslacore');

/**
 * xoosla_setPerms()
 *
 * @param mixed $h
 * @param       $groupids
 * @param mixed $id
 * @internal param mixed $groups
 */
function xoosla_setPerms(&$h, &$groupids, $id)
{
    $group = XooslaLoad::getClass('permissions');
    $group->setPermissions($h->tableName, $h->groupName, '', $GLOBALS['xoopsModule']->getVar('mid'));
    $group->save($groupids, $id);
}

/**
 * xoosla_clonePerms()
 *
 * @param mixed $h
 * @param mixed $old_id
 * @param mixed $new_id
 * @return bool
 */
function xoosla_clonePerms(&$h, $old_id = null, $new_id = null)
{
    if (is_null($old_id) or is_null($new_id)) {
        return false;
    }
    // set the persmissions
    $group = XooslaLoad::getClass('permissions');
    $group->setPermissions($h->tableName, $h->groupName, '', $GLOBALS['xoopsModule']->getVar('mid'));
    // get ID's for current page
    $groups = $group->getAdmin($old_id);
    // save new ID for new page
    $group->save($groups, $new_id);
}

/**
 * xoosla_deletePerms()
 *
 * @param mixed $h
 * @param mixed $id
 */
function xoosla_deletePerms(&$h, $id)
{
    $group = XooslaLoad::getClass('permissions');
    $group->setPermissions($h->tableName, $h->groupName, '', $GLOBALS['xoopsModule']->getVar('mid'));
    $group->doDelete($id);
}

/**
 * xoosla_getFileExt()
 *
 * @param string $value
 * @return mixed
 */
function xoosla_getFileExt($value = '')
{
    $filename        = explode('.', basename($value));
    $ret['basename'] = @$filename['0'];
    $ret['ext']      = @$filename['1'];

    return $ret;
}

/**
 * xoosla_getHtmlEditor()
 * @return bool
 */
function xoosla_getHtmlEditor()
{
    $use_wysiwyg = xoops_getModuleOption('use_wysiwyg', $GLOBALS['xoopsModule']->getVar('dirname'));
    if (isset($use_wysiwyg) && in_array($use_wysiwyg, ['tinymce', 'ckeditor', 'koivi', 'inbetween', 'spaw'])) {
        return true;
    }

    return false;
}

/**
 * xoosla_addSlashes()
 *
 * @param $text
 * @return string
 * @internal param mixed $value
 */
function xoosla_addSlashes($text)
{
    if (!get_magic_quotes_gpc()) {
        return addslashes($text);
    }

    return $text;
}

/**
 * xoosla_stripSlashes()
 *
 * @param mixed $text
 * @return mixed|string
 */
function xoosla_stripSlashes($text)
{
    if (get_magic_quotes_gpc()) {
        return stripslashes($text);
    }

    return $text;
}

/**
 * xoosla_isModInstalled()
 *
 * @param string $module
 * @return mixed
 */
function xoosla_isModInstalled($module = '')
{
    static $xoosla_module;
    if (!isset($xoosla_module[$module])) {
        $modulesHandler         = xoops_getHandler('module');
        $installed_mod          = $modulesHandler->getByDirName($module);
        $xoosla_module[$module] = (is_object($installed_mod) && $installed_mod->getVar('isactive')) ? true : false;
    }

    return $xoosla_module[$module];
}

if (!function_exists('print_r_html')) {
    /**
     * print_r_html()
     *
     * @param string $value
     * @param mixed  $debug
     * @param mixed  $extra
     */
    function print_r_html($value = '', $debug = false, $extra = false)
    {
        echo '<div>' . str_replace(["\n", ' '], ['<br>', '&nbsp;'], print_r($value, true)) . '</div>';
        if (false !== $extra) {
            foreach ($_SERVER as $k => $v) {
                if ('HTTP_REFERER' !== $k) {
                    echo "<div><b>Server:</b> $k value: $v</div>";
                } else {
                    echo "<div><b>Server:</b> $k value: $v</div>";
                    $v = strpos($_SERVER[$k], XOOPS_URL);
                    echo "<div><b>Server:</b> $k value: $v</div>";
                }
            }
        }
    }
}

function xoosla_securityCheck()
{
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? urldecode($_SERVER['HTTP_REFERER']) : xoops_getenv('PHP_SELF');
        redirect_header($redirect, 1, _XL_AD_ADM_DBERROR);
    }
}

XooslaLoad('modules.xooslacore.core.libraries.functions_admin');
