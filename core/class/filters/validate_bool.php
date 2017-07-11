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
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * XooslaFilter_Validate_String
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaFilter_Validate_Bool extends XooslaRequest
{
    /**
     * XooslaFilter_Validate_String::doRender()
     *
     * @param null $bool
     * @return bool
     * @internal param mixed $method
     * @internal param mixed $key
     * @internal param array $options
     */
    public function doRender($bool = null)
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN) === false) {
            return false;
        } else {
            return true;
        }
    }
}
