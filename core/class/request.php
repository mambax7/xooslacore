<?php
/**
 * Name: class.filter.php
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

use Xmf\Request;

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * XooslaFilter
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaFilter
{
    protected static $instance;
    protected static $handlers;
    private static $name;

    /**
     * xo_Xoosla::getInstance()
     *
     * @return
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            $class          = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    /**
     * XooslaFilter::getFilter()
     *
     * @param mixed $name
     * @return bool
     */
    public static function getFilter($name)
    {
        static $handlers;

        self::$name = $name;
        /**
         */
        if (!isset($handlers[self::$name])) {
            $ret = self::loadFilter();
            if ($ret !== true) {
                $className = 'XooslaFilter_' . self::$name;
                if (class_exists($className) && is_callable(__CLASS__, $className)) {
                    $handler = new $className(__CLASS__);
                    if (!is_object($handler)) {
                        return false;
                    }
                    $handlers[self::$name] = $handler;
                }
            }
        }
        if (!isset($handlers[self::$name])) {
            return false;
        }

        return $handlers[self::$name];
    }

    /**
     * XooslaFilter::getCore()
     * @return bool
     */
    public static function loadFilter()
    {
        if (file_exists($file = __DIR__ . DS . 'filters' . DS . strtolower(self::$name) . '.php')) {
            require_once $file;
        }

        return false;
    }

    /**
     * XooslaFilter::getUser()
     *
     */
    public function getUser()
    {
    }

    /**
     * XooslaFilter::filterValidate()
     *
     * @param     $value
     * @param int $filterid
     * @return bool
     */
    public function filterValidate($value, $filterid = 0)
    {
        return filter_var($value, (int)$filterid) ? true : false;
    }
}

/**
 * XooslaRequest
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
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
    public static function doRequest($method, $key, $default = null, $type = null, $options = array())
    {
        if (ctype_alpha($type)) {
            $filter = XooslaFilter::getFilter('Sanitize_' . ucfirst($type));
            if (is_object($filter) && !empty($filter)) {
                $ret = $filter->doRender($method, $key, $options);

                return ($ret === false) ? $default : $ret;
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
    public static function doSanitize($method, $type = null, $options = array())
    {
        if (ctype_alpha($type)) {
            $filter = XooslaFilter::getFilter('Sanitize_' . ucfirst($type));
            if (!empty($filter) && is_object($filter)) {
                $ret = $filter->doRender($method, $options);

                return ($ret === false) ? false : $ret;
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
            $filter = XooslaFilter::getFilter('Validate_' . ucfirst($type));
            if (!empty($filter) && is_object($filter)) {
                if ($ret = $filter->doRender($value, $flags)) {
                    return ($ret === false) ? $default : $ret;
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
        return XooslaRequest::doRequest($_REQUEST, "{$value}", "{$default}", 'int');
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
        return XooslaRequest::doRequest($_REQUEST, "{$value}", "{$default}", 'textbox');
    }
}
