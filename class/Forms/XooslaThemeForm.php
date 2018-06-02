<?php namespace XoopsModules\Xooslacore\Forms;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use XoopsModules\Xooslacore;

/**
 * Xoops Form Class Elements
 *
 * @copyright  XOOPS Project (https://xoops.org)
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package    kernel
 * @subpackage form
 * @since      2.0.0
 * @author     Kazumi Ono <onokazu@xoops.org>
 * @author     John Neill <catzwolf@xoops.org>
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_load('XoopsForm');

/**
 *
 * Form that will output as a theme-enabled HTML table
 *
 * Also adds JavaScript to validate required fields
 *
 * @author     Kazumi Ono <onokazu@xoops.org>
 * @author     John Neill <catzwolf@xoops.org>
 * @copyright  XOOPS Project (https://xoops.org)
 * @package    kernel
 * @subpackage form
 * @access     public
 */
class XooslaThemeForm extends \XoopsForm
{
    /**
     * Insert an empty row in the table to serve as a seperator.
     *
     * @param string $extra HTML to be displayed in the empty row.
     * @param string $class CSS class name for <td> tag
     */
    public function insertBreak($extra = '', $class = '')
    {
        $class = ('' != $class) ? " class='" . preg_replace('/[^A-Za-z0-9\s\s_-]/i', '', $class) . "'" : '';
        // Fix for $extra tag not showing
        if ($extra) {
            $extra = '<tr><td colspan="2" ' . $class . '>' . $extra . '</td></tr>';
            $this->addElement($extra);
        } else {
            $extra = '<tr><td colspan="2" ' . $class . '>&nbsp;</td></tr>';
            $this->addElement($extra);
        }
    }

    /**
     * create HTML to output the form as a theme-enabled table with validation.
     *
     * To use the noColspan simply use the following example:
     *
     * $colspan = new \XoopsFormDhtmlTextArea( '', 'key', $value, '100%', '100%' );
     * $colspan->setNocolspan();
     * $form->addElement( $colspan );
     *
     * @return string
     */
    public function render()
    {
        $ele_name = $this->getName();
        $ret      = '<table width="100%" class="outer" style="text-align: left;" cellspacing="1"><tr><th colspan="2">' . $this->getTitle() . '</th></tr>';
        $hidden   = '';
        $class    = 'even';
        foreach ($this->getElements() as $ele) {
            if (!is_object($ele)) {
                $ret .= $ele;
            } elseif (!$ele->isHidden()) {
                if (!$ele->getNocolspan()) {
                    $ret .= '<tr valign="top" align="left"><td class="head" style="width: 40%">';
                    if ('' != ($caption = $ele->getCaption())) {
                        $ret .= '<div class="xoops-form-element-caption' . ($ele->isRequired() ? '-required' : '') . '">';
                        $ret .= '<span class="caption-text">' . $caption . '</span>';
                        $ret .= '<span class="caption-marker">*</span>';
                        $ret .= '</div>';
                    }
                    if ('' != ($desc = $ele->getDescription())) {
                        $ret .= '<div class="xoops-form-element-help">' . $desc . '</div>';
                    }
                    $ret .= '</td><td class="' . $class . '">' . $ele->render() . '</td></tr>' . NWLINE;
                } else {
                    $ret .= '<tr valign="top" align="left"><td class="head" colspan="2">';
                    if ('' != ($caption = $ele->getCaption())) {
                        $ret .= '<div class="xoops-form-element-caption' . ($ele->isRequired() ? '-required' : '') . '">';
                        $ret .= '<span class="caption-text">' . $caption . '</span>';
                        $ret .= '<span class="caption-marker">*</span>';
                        $ret .= '</div>';
                    }
                    $ret .= '</td></tr><tr valign="top" align="left"><td class="' . $class . '" colspan="2">' . $ele->render() . '</td></tr>';
                }
            } else {
                $hidden .= $ele->render();
            }
        }
        $ret .= '</table>' . NWLINE . ' ' . $hidden . NWLINE;
        $ret .= $this->renderValidationJS(true);

        return $ret;
    }
}
