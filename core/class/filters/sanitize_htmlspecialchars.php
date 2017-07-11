<?php
/**
 * Name: validate_string.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: validate_string.php 0000 02/04/2009 22:21:06:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XooslaFilter_Validate_String
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaFilter_Sanitize_Htmlspecialchars extends XooslaRequest {
    /**
     * XooslaFilter_Sanitize_Htmlspecialchars::doRender()
     *
     * @param mixed $method
     * @param string $key
     * @return
     */
    function doRender( $method, $key = '' ) {
        if ( !empty( $method ) && is_int( $method ) ) {
            $ret = filter_input( $method, $key, FILTER_SANITIZE_SPECIAL_CHARS );
        } else {
            $method = ( is_array( $method ) && isset( $method[$key] ) ) ? $method[$key] : $method;
            $ret = filter_var( $method, FILTER_SANITIZE_SPECIAL_CHARS );
        }

        return ( $ret === false ) ? false : $ret;
    }
}

?>