<?php namespace XoopsModules\Xooslacore\Filters;

/**
 * Name: String Validation
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
class SanitizeArray extends Xooslacore\XooslaRequest
{
    /**
     * Sanitize_Array::doRender()
     *
     * @param mixed $method
     * @return array
     * @internal param string $key
     */
    public function doRender($method)
    {
        $ret = [];

        if (is_array($method)) {
            foreach ($method as $k => $v) {
                $ret[$k] = filter_var($v, FILTER_SANITIZE_STRING);
            }
        }

        return $ret;
    }
}
