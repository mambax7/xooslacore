<?php namespace XoopsModules\Xooslacore\Forms;

/**
 * Name: Xoosla Form Sides
 * Description:
 *
 * @package   Xoosla Core Module
 * @subpackage
 * @since     v1.0.0
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

use XoopsModules\Xooslacore;

xoops_load('XoopsFormSelect');

/**
 * xo_FormImageSide
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaFormSides extends \XoopsFormSelect
{
    /**
     * XoopsXooslaFormSides::XoopsXooslaFormSides()
     *
     * @param mixed $caption
     * @param mixed $name
     * @param mixed $value
     * @param mixed $size
     */
    public function __construct($caption, $name, $value = 0, $size = 1)
    {
        parent::__construct($caption, $name, $value, $size, 0);
        $this->addOptionArray([0 => 'Left', 1 => 'Center', 2 => 'Right']);
    }
}
