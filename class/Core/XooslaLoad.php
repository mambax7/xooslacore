<?php namespace XoopsModules\Xooslacore\Core;

/**
 * Name: loader.php
 * Description: loader.php
 *
 * @package    : Xoosla Modules
 * @Module     : Xoosla Core
 * @subpackage : Class
 * @since      : v1.00
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

use XoopsModules\Xooslacore;
use XoopsModules\Xooslacore\Core;

defined('XOOPS_ROOT_PATH') || die('Restricted access');
defined('DS') || define('DS', '/');

/**
 * loader.php
 *
 * @package   : Class
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaLoad
{
    /**
     * Core\XooslaLoad::getClass()
     *
     * @param        $param
     * @param string $options
     * @param string $module
     * @return mixed
     */
    public static function getClass($param, $options = '', $module = 'xooslacore')
    {
        static $static_class;

        if (!isset($static_class[$param])) {
//            $param = strtolower(trim($param));
            switch ($module) {
                case 'core':
                    $prefix   = 'Xoops';
                    $fileName = 'class.' . $param;
                    $class = $prefix . ucfirst($param);
                    break;
                default:
                    $prefix   = 'Xoosla';
                    $fileName = 'modules.' . $module . '.class.' . $prefix . ucfirst($param);
                    $class = '\\XoopsModules\\' . ucfirst(strtolower($module)) . '\\Xoosla' .ucfirst($param);
                    break;
            } // switch
            /**
             */
//            $class = $prefix . ucfirst($param);

            if (class_exists($class)) {
                $static_class[$param] = new $class($options);
            } else {
                self::getInclude($fileName);
                if (class_exists($class)) {
                    $static_class[$param] = new $class($options);
                } else {
                    trigger_error('Class <b>' . $class . '</b> does not exist<br>Class Name: ' . $param, E_USER_ERROR);
                }

            }
/*
            if (!class_exists($class)) {
                self::getInclude($fileName);
                if (class_exists($class)) {
                    $static_class[$param] = new $class($options);
                } else {
                    trigger_error('Class <b>' . $class . '</b> does not exist<br>Class Name: ' . $param, E_USER_ERROR);
                }
            }
*/
        }

        return $static_class[$param];
    }

    /**
     * Core\XooslaLoad::getModel()
     *
     * @param mixed  $param
     * @param string $module
     * @return bool
     */
    public static function getModel($param, $module = 'xooslacore')
    {
        static $static_model;

        if (!isset($static_model[$param])) {
            $param = strtolower(trim($param));
            /**
             */
//            self::getInclude('modules.' . $module . '.class.models.' . $param);
//            $class = ucfirst($param) . 'Model';
            $class = '\\XoopsModules\\' . ucfirst(strtolower($module)) . '\\Models\\' .ucfirst($param) . 'Model';
            if (class_exists($class)) {
                $static_model[$param] = new $class($GLOBALS['xoopsDB']);
            } else {
                $ret = false;

                return $ret;
            }
        }

        return $static_model[$param];
    }

    /**
     * Core\XooslaLoad::getHandler()
     *
     * @param        $param
     * @param string $options
     * @param string $module
     * @return bool
     */
    public static function getView($param, $options = '', $module = 'xooslacore')
    {
        static $static_view;

        if (!isset($static_view[$param])) {
            $param = strtolower(trim($param));
            /**
             */
//            self::getInclude('modules.' . $module . '.class.views.' . $param);
//            $class = ucfirst($param) . 'View';
            $class = '\\XoopsModules\\' . ucfirst(strtolower($module)) . '\\Views\\' .ucfirst($param) . 'View';
            if (class_exists($class)) {
                $static_view[$param] = new $class($options);
            } else {
                $ret = false;

                return $ret;
            }
        }

        return $static_view[$param];
    }

    /**
     * Core\XooslaLoad::getHandler()
     *
     * @param        $param
     * @param string $options
     * @param string $module
     * @return bool
     */
    public static function getHelper($param, $options = '', $module = 'xooslacore')
    {
        static $static_helper;

        if (!isset($static_helper[$param])) {
            $param = strtolower(trim($param));
            /**
             */
//            self::getInclude('modules.' . $module . '.class.helpers.' . $param);
//            $class = ucfirst($param) . 'Helper';
            $class = '\\XoopsModules\\' . ucfirst(strtolower($module)) . '\\Helpers\\' .ucfirst($param) . 'Helper';
            if (class_exists($class)) {
                $static_helper[$param] = new $class($options);
            } else {
                $ret = false;

                return $ret;
            }
        }

        return $static_helper[$param];
    }

    /**
     * Core\XooslaLoad::getHandler()
     *
     * @param        $param
     * @param string $options
     * @param string $module
     * @return bool
     */
    public static function getController($param, $options = '', $module = 'xooslacore')
    {
        static $static_controller;

        if (!isset($static_controller[$param])) {
            $param = strtolower(trim($param));
            /**
             */
//            self::getInclude('modules.' . $module . '.class.' . $param);
            $class = '\\XoopsModules\\' . ucfirst(strtolower($module)) . '\\' .ucfirst($param) . 'Controller';
            if (class_exists($class)) {
                $static_controller[$param] = new $class($options);
            } else {
                $ret = false;

                return $ret;
            }
        }

        return $static_controller[$param];
    }

    /**
     * Core\XooslaLoad::xoosla_loadHandler()
     *
     * @param mixed  $param
     * @param string $module
     * @param string $c_prefix
     * @return bool
     * @internal param string $dirname
     * @internal param mixed $optional
     */
    public static function getHandler($param, $module = 'xooslacore', $c_prefix = 'Xoosla')
    {
        static $staticHandler;

//        $param = strtolower(trim($param));
        if (!isset($staticHandler[$param])) {
            //            self::getInclude('modules.' . $module . '.class.' . $param);
            $class = '\\XoopsModules\\' . ucfirst(strtolower($module)) . '\\' . $c_prefix . ucfirst($param) . 'Handler';
        }
            if (class_exists($class)) {
                $staticHandler[$param] = new $class($GLOBALS['xoopsDB']);
//            }

//        if (isset($staticHandler[$param])) {
//            return $staticHandler[$param];
        } else {
            $ret = false;

            return $ret;
        }

//        return $static_controller[$param];
        return $staticHandler[$param];
    }

    /**
     * Core\XooslaLoad::getInclude()
     *
     * @param $param
     */
    public static function getInclude($param)
    {
        if (!is_array(explode('.', $param))) {
            $param = 'modules/xooslacore/class/Core' . $param;
        } else {
            $param = str_replace('.', '/', $param);
        }
        require_once $GLOBALS['xoops']->path($param . '.php');
    }
}

/**
 * Core\XooslaLoad()
 *
 * @param mixed $param
 * @internal param string $options
 */
function XooslaLoad($param)
{
    Core\XooslaLoad::getInclude($param);
}
