<?php namespace XoopsModules\Xooslacore\Filters;

/**
 * Name: validate_string.php
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

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * XooslaFilter_Validate_Int
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class ValidateInt extends Xooslacore\XooslaRequest
{
    /**
     * XooslaFilter_Validate_String::render()
     *
     * @param  mixed $value
     * @return bool
     */
    public function doRender($value = null)
    {
        if (false === filter_var($value, FILTER_VALIDATE_INT)) {
            return false;
        } else {
            return true;
        }
    }
}
