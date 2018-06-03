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

use Xmf\Request;
use XoopsModules\Xooslacore;
use XoopsModules\Xooslacore\Filters;

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
        if (null === self::$instance) {
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
//            $ret = self::loadFilter();
//            if (true !== $ret) {
//                $className = 'XooslaFilter_' . self::$name;
                $className = '\\XoopsModules\\' . ucfirst(strtolower(basename(dirname(__DIR__)))) . '\\Filters\\' .self::$name;
                if (class_exists($className) && is_callable(__CLASS__, $className)) {
                    $handler = new $className(__CLASS__);
                    if (!is_object($handler)) {
                        return false;
                    }
                    $handlers[self::$name] = $handler;
                }
//            }
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
        if (file_exists($file = __DIR__ . '/Filters/' . strtolower(self::$name) . '.php')) {
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
