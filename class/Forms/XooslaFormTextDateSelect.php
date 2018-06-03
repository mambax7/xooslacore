<?php namespace XoopsModules\Xooslacore\Forms;

/**
 * Name: class.objecthandler.php
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

use XoopsModules\Xooslacore;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 *
 * @package    kernel
 * @subpackage form
 * @author     Kazumi Ono
 * @copyright  copyright (c) 2007 Xoops Project - http.www.xoops.com
 */
//require_once XOOPS_ROOT_PATH . '/modules/xooslacore/core/class/Forms/xoosla_formcalendar.php';

/**
 * A text field with calendar popup
 *
 * @package    kernel
 * @subpackage form
 * @author     Kazumi Ono
 * @copyright  copyright (c) 2007 Xoops Project - http.www.xoops.com
 */
class XooslaFormTextDateSelect extends Xooslacore\Forms\XooslaFormCalendar
{
    /**
     * XoopsFormTextDateSelect::XoopsFormTextDateSelect()
     *
     * @param mixed      $caption
     * @param mixed      $name
     * @param integer    $size
     * @param int|string $value
     * @param bool       $display
     * @param mixed      $showtime
     */
    public function __construct($caption, $name, $size = 30, $value = 0, $display = false, $showtime = true)
    {
        $calendar_options['showsTime'] = $showtime;
        $field_attributes['size']      = $size;

        $value = (0 == $value) ? (true === $display) ? time() : '' : $value;
        if ('' != $value || $value > 0) {
            $field_attributes['value'] = is_numeric($value) ? strftime('%m/%d/%Y %H:%M', $value) : $value;
        } else {
            $field_attributes['value'] = '';
        }
        parent::__construct($caption, $name, $value, $calendar_options, $field_attributes);
    }
}
