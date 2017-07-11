<?php
/**
 * Name: class.filter.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: class.filter.php 0000 02/04/2009 19:19:28:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XooslaFilter
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaFilter {
    protected static $instance;
    protected static $handlers;
    private static $name;

    /**
     * xo_Xoosla::getIntance()
     *
     * @return
     */
    public static function &getInstance() {
        if ( self::$instance == null ) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    /**
     * XooslaFilter::getFilter()
     *
     * @param mixed $name
     * @return
     */
    public static function getFilter( $name ) {
        static $handlers;

        self::$name = $name;
        /**
         */
        if ( !isset( $handlers[self::$name] ) ) {
            $ret = self::loadFilter();
            if ( $ret !== true ) {
                $className = 'XooslaFilter_' . self::$name;
                if ( class_exists( $className ) && is_callable( __CLASS__, $className ) ) {
                    $handler = new $className( __CLASS__ );
                    if ( !is_object( $handler ) ) {
                        return false;
                    }
                    $handlers[self::$name] = $handler;
                }
            }
        }
        if ( !isset( $handlers[self::$name] ) ) {
            return false;
        }
        return $handlers[self::$name];
    }

    /**
     * XooslaFilter::getCore()
     *
     * @return
     */
    function loadFilter() {
        if ( file_exists( $file = dirname( __FILE__ ) . DS . 'filters' . DS . strtolower( self::$name ) . '.php' ) ) {
            include_once $file;
        }
        return false;
    }

    /**
     * XooslaFilter::getUser()
     *
     * @return
     */
    function getUser() {
    }

    /**
     * XooslaFilter::filterValidate()
     *
     * @return
     */
    function filterValidate( $value , $filterid = 0 ) {
        return ( filter_var( $value, ( int )$filterid ) ) ? true : false;
    }
}

/**
 * XooslaRequest
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaRequest {
    static $method;
    /**
     * Constructor
     *
     * @access protected
     */
    public function __construct() {
    }

    /**
     * XooslaFilter::doRequest()
     *
     * @param mixed $type
     * @param mixed $key
     * @param mixed $default
     * @param array $filters
     * @return
     */
    public static function doRequest( $method, $key, $default = null, $type = null, $options = array() ) {
        if ( ctype_alpha( $type ) ) {
            $filter = XooslaFilter::getFilter( 'Sanitize_' . ucfirst( $type ) );
            if ( is_object( $filter ) && !empty( $filter ) ) {
                $ret = $filter->doRender( $method, $key, $options );
                return ( $ret === false ) ? $default : $ret;
            }
        }
        unset( $filter );
        return false;
    }

    /**
     * XooslaRequest::doSanitize()
     *
     * @param mixed $method
     * @param mixed $key
     * @param mixed $default
     * @param mixed $type
     * @param array $options
     * @param string $module
     * @return
     */
    public static function doSanitize( $method, $type = null, $options = array() ) {
        if ( ctype_alpha( $type ) ) {
            $filter = XooslaFilter::getFilter( 'Sanitize_' . ucfirst( $type ) );
            if ( !empty( $filter ) && is_object( $filter ) ) {
                $ret = $filter->doRender( $method, $options );
                return ( $ret === false ) ? false : $ret;
            }
        }
        unset( $filter );
        return false;
    }

    /**
     * XooslaRequest::doValidate()
     *
     * @return
     */
    public static function doValidate( $value, $type, $flags = null ) {
        if ( ctype_alpha( $type ) ) {
            $filter = XooslaFilter::getFilter( 'Validate_' . ucfirst( $type ) );
            if ( !empty( $filter ) && is_object( $filter ) ) {
                if ( $ret = $filter->doRender( $value, $flags ) ) {
                    return ( $ret === false ) ? $default : $ret;
                }
            }
        }
        unset( $filter );
        return false;
    }

    /**
     * XooslaRequest::inArray()
     *
     * @return
     */
    public static function inArray( $method, $key ) {
        if ( empty( $method ) || empty( $key ) ) {
            return filter_has_var( $method, $key );
        }
    }

    /**
     * These are alaises of the above function
     */
    function getInt( $value, $default = '' ) {
        return XooslaRequest::doRequest( $_REQUEST, "{$value}", "{$default}", 'int' );
    }

    /**
     * XooslaRequest::textbox()
     *
     * @return
     */
    function getString( $value, $default = '' ) {
        return XooslaRequest::doRequest( $_REQUEST, "{$value}", "{$default}", 'textbox' );
    }
}

?>