<?php namespace XoopsModules\Xooslacore;

/**
 * Name: class.filter.php
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
use Xmf\Request;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * XooslaFilter
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */


/**
 * XooslaRequest
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaRequest
{
    public static $method;

    /**
     * Constructor
     *
     * @access protected
     */
    public function __construct()
    {
    }

    /**
     * XooslaFilter::doRequest()
     *
     * @param       $method
     * @param mixed $key
     * @param mixed $default
     * @param mixed $type
     * @param array $options
     * @return bool|mixed|null
     * @internal param array $filters
     */
    public static function doRequest($method, $key, $default = null, $type = null, $options = [])
    {
        if (ctype_alpha($type)) {
            $filter = \XoopsModules\Xooslacore\XooslaFilter::getFilter('Sanitize' . ucfirst($type));
            if (is_object($filter) && !empty($filter)) {
                $ret = $filter->doRender($method, $key, $options);

                return (false === $ret) ? $default : $ret;
            }
        }
        unset($filter);

        return false;
    }

    /**
     * XooslaRequest::doSanitize()
     *
     * @param mixed $method
     * @param mixed $type
     * @param array $options
     * @return bool
     * @internal param mixed $key
     * @internal param mixed $default
     * @internal param string $module
     */
    public static function doSanitize($method, $type = null, $options = [])
    {
        if (ctype_alpha($type)) {
            $filter = \XoopsModules\Xooslacore\XooslaFilter::getFilter('Sanitize' . ucfirst($type));
            if (!empty($filter) && is_object($filter)) {
                $ret = $filter->doRender($method, $options);

                return $ret;
            }
        }
        unset($filter);

        return false;
    }

    /**
     * XooslaRequest::doValidate()
     *
     * @param      $value
     * @param      $type
     * @param null $flags
     * @return bool
     */
    public static function doValidate($value, $type, $flags = null)
    {
        if (ctype_alpha($type)) {
            $filter = \XoopsModules\Xooslacore\XooslaFilter::getFilter('Validate' . ucfirst($type));
            if (!empty($filter) && is_object($filter)) {
                if ($ret = $filter->doRender($value, $flags)) {
                    return (false === $ret) ? $default : $ret;
                }
            }
        }
        unset($filter);

        return false;
    }

    /**
     * XooslaRequest::inArray()
     *
     * @param $method
     * @param $key
     * @return bool
     */
    public static function inArray($method, $key)
    {
        if (empty($method) || empty($key)) {
            return filter_has_var($method, $key);
        }
    }

    /**
     * These are alaises of the above function
     * @param        $value
     * @param string $default
     * @return bool|mixed|null
     */
    public static function getInt($value, $default = '')
    {
        return self::doRequest($_REQUEST, (string)$value, (string)$default, 'int');
    }

    /**
     * XooslaRequest::textbox()
     *
     * @param        $value
     * @param string $default
     * @return bool|mixed|null
     */
    public static function getString($value, $default = '')
    {
        return self::doRequest($_REQUEST, (string)$value, (string)$default, 'textbox');
    }
}
