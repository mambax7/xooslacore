<?php
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
defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * XooslaFilter_Validate_Int
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */

/**
 * XooslaFilter_Sanitize_Int
 *
 * @package
 * @author    John
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaFilter_Sanitize_Int extends XooslaRequest
{
    /**
     * XooslaFilter_Sanitize_Int::doRender()
     *
     * @param  mixed $method
     * @param  mixed $key
     * @return bool|mixed
     */
    public function doRender($method, $key = '')
    {
        //if ( !empty( $method ) && is_int( $method ) ) {
        //    $ret = filter_input( $method, $key, FILTER_SANITIZE_NUMBER_INT );
        //} else {
        $method = (is_array($method) && isset($method[$key])) ? $method[$key] : $method;
        $ret    = filter_var($method, FILTER_SANITIZE_NUMBER_INT);

        //}
        return ($ret === false) ? false : $ret;
    }
}
