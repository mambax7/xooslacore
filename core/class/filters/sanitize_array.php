<?php
/**
 * Name: String Validation
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
class XooslaFilter_Sanitize_Array extends XooslaRequest {
    /**
     * XooslaFilter_Sanitize_Array::doRender()
     *
     * @param mixed $method
     * @param string $key
     * @return
     */
    function doRender( $method ) {
        $ret = array();

        if ( is_array( $method ) ) {
            foreach ( $method as $k => $v ) {
                $ret[$k] = filter_var( $v, FILTER_SANITIZE_STRING );
            }
        }
        return $ret;
    }
}

?>