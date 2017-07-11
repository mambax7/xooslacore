<?php
/**
 * Name: loader.php
 * Description: loader.php
 *
 * @package : Xoosla Modules
 * @Module : Xoosla Core
 * @subpackage : Class
 * @since : v1.00
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: loader.php 0000 26/03/2009 23/06/2010 07:03:47 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

defined( 'DS' ) || define( 'DS', DIRECTORY_SEPARATOR );

/**
 * loader.php
 *
 * @package : Class
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @version $Id$
 * @access public
 */
class XooslaLoad {
    /**
     * XooslaLoad::getClass()
     *
     * @return
     */
    function &getClass( $param, $options = '', $module = 'xooslacore' ) {
        static $static_class;

        if ( !isset( $static_class[$param] ) ) {
            $param = strtolower( trim( $param ) );
            switch ( $module ) {
                case 'core':
                    $prefix = 'Xoops';
                    $fileName = 'class.' . $param;
                    break;
                default:
                    $prefix = 'Xoosla';
                    $fileName = 'modules.' . $module . '.core.class.' . $param;
                    break;
            } // switch
            /**
             */
            $class = $prefix . ucfirst( $param );
            if ( !class_exists( $class ) ) {
                self::getInclude( $fileName );
                if ( class_exists( $class ) ) {
                    $static_class[$param] = new $class( $options );
                } else {
                    trigger_error( 'Class <b>' . $class . '</b> does not exist<br />Class Name: ' . $param, E_USER_ERROR );
                }
            }
        }
        return $static_class[$param];
    }

    /**
     * XooslaLoad::getModel()
     *
     * @param mixed $param
     * @param string $module
     * @return
     */
    function &getModel( $param, $module = 'xooslacore' ) {
        static $static_model;

        if ( !isset( $static_model[$param] ) ) {
            $param = strtolower( trim( $param ) );
            /**
             */
            self::getInclude( 'modules.' . $module . '.class.models.' . $param );
            $class = ucfirst( $param ) . 'Model';
            if ( class_exists( $class ) ) {
                $static_model[$param] = new $class( $GLOBALS['xoopsDB'] );
            } else {
                $ret = false;
                return $ret;
            }
        }
        return $static_model[$param];
    }

    /**
     * XooslaLoad::getHandler()
     *
     * @return
     */
    function getView( $param, $options = '', $module = 'xooslacore' ) {
        static $static_view;

        if ( !isset( $static_view[$param] ) ) {
            $param = strtolower( trim( $param ) );
            /**
             */
            self::getInclude( 'modules.' . $module . '.class.views.' . $param );
            $class = ucfirst( $param ) . 'View';
            if ( class_exists( $class ) ) {
                $static_view[$param] = new $class( $options );
            } else {
                $ret = false;
                return $ret;
            }
        }
        return $static_view[$param];
    }

    /**
     * XooslaLoad::getHandler()
     *
     * @return
     */
    function getHelper( $param, $options = '', $module = 'xooslacore' ) {
        static $static_helper;

        if ( !isset( $static_helper[$param] ) ) {
            $param = strtolower( trim( $param ) );
            /**
             */
            self::getInclude( 'modules.' . $module . '.class.helpers.' . $param );
            $class = ucfirst( $param ) . 'Helper';
            if ( class_exists( $class ) ) {
                $static_helper[$param] = new $class( $options );
            } else {
                $ret = false;
                return $ret;
            }
        }
        return $static_helper[$param];
    }

    /**
     * XooslaLoad::getHandler()
     *
     * @return
     */
    function getController( $param, $options = '', $module = 'xooslacore' ) {
        static $static_controller;

        if ( !isset( $static_controller[$param] ) ) {
            $param = strtolower( trim( $param ) );
            /**
             */
            self::getInclude( 'modules.' . $module . '.class.' . $param );
            $class = ucfirst( $param ) . 'Controller';
            if ( class_exists( $class ) ) {
                $static_controller[$param] = new $class( $options );
            } else {
                $ret = false;
                return $ret;
            }
        }
        return $static_controller[$param];
    }

    /**
     * XooslaLoad::xoosla_loadHandler()
     *
     * @param mixed $param
     * @param string $dirname
     * @param string $c_prefix
     * @param mixed $optional
     * @return
     */
    function &getHandler( $param, $module = 'xooslacore', $c_prefix = 'Xoosla' ) {
        static $static_handler;

        $param = strtolower( trim( $param ) );
        if ( !isset( $static_handler[$param] ) ) {
            self::getInclude( 'modules.' . $module . '.core.class.' . $param );
            $class = $c_prefix . ucfirst( $param ) . 'Handler';
            if ( class_exists( $class ) ) {
                $static_handler[$param] = new $class( $GLOBALS['xoopsDB'] );
            }
        }
        if ( isset( $static_handler[$param] ) ) {
            return $static_handler[$param];
        } else {
            $ret = false;
            return $ret;
        }
        return $static_controller[$param];
    }

    /**
     * XooslaLoad::getInclude()
     *
     * @return
     */
    function getInclude( $param ) {
        if ( !is_array( explode ( '.', $param ) ) ) {
            $param = 'modules' . DS . 'xooslacore' . DS . 'class' . $param;
        } else {
            $param = str_replace( '.', DS, $param );
        }
        include_once $GLOBALS['xoops']->path( $param . '.php' );
    }
}

/**
 * XooslaLoad()
 *
 * @param mixed $param
 * @param string $options
 * @return
 */
function XooslaLoad( $param ) {
    XooslaLoad::getInclude( $param );
}

?>