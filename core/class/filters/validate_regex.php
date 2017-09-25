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
 * XooslaFilter_Validate_String
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaFilter_Validate_Regex extends XooslaRequest
{
    /**
     * XooslaFilter_Validate_Regex::doRender()
     *
     * @param mixed $value
     * @return bool
     */
    public function doRender($value = null)
    {
        if (false === filter_var($value, FILTER_VALIDATE_REGEXP)) {
            return false;
        } else {
            return true;
        }
    }
}
