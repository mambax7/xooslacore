<?php namespace XoopsModules\Xooslacore\Filters;

/**
 * Name: validate_string.php
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     :
 * @subpackage :
 * @since      : v1.0.0
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

use XoopsModules\Xooslacore;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * XooslaFilter_Validate_String
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class SanitizeEmail extends Xooslacore\XooslaRequest
{
    /**
     * XooslaFilter_Sanitize_Email::doRender()
     *
     * @param mixed $method
     * @param mixed $key
     * @return bool|mixed
     */
    public function doRender($method, $key = '')
    {
        if (!empty($method) && is_int($method)) {
            $ret = filter_input($method, $key, FILTER_SANITIZE_EMAIL);
        } else {
            $method = (is_array($method) && isset($method[$key])) ? $method[$key] : $method;
            $ret    = filter_var($method, FILTER_SANITIZE_EMAIL);
        }

        return $ret;
    }
}
