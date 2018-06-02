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
 * XooslaFilter_Validate_String
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class SanitizeBool extends Xooslacore\XooslaRequest
{
    /**
     * XooslaFilter_Sanitize_Urlencode::doRender()
     *
     * @param  mixed  $method
     * @param  string $key
     * @return bool|int|mixed
     */
    public function doRender($method, $key = '')
    {
        if (!empty($method) && is_int($method)) {
            $ret = filter_input($method, $key, FILTER_SANITIZE_STRING);
        } else {
            $method = (is_array($method) && isset($method[$key])) ? $method[$key] : $method;
            $ret    = (1 == (int)$method) ? 1 : 0;
            // $ret = filter_var( $method, FILTER_SANITIZE_ENCODED );
        }

        return $ret;
    }
}
