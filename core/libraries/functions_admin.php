<?php
/**
 * Name: functions_admin.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: functions.php 0000 25/03/2009 21:25:19:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * xoolsa_displayConfirm()
 *
 * @param mixed $hiddens
 * @param mixed $op
 * @param mixed $msg
 * @param string $submit
 * @param string $cancel
 * @param mixed $noarray
 * @param mixed $echo
 * @return
 */
function xoolsa_displayConfirm( $hiddens, $op, $msg, $submit = '', $cancel = '', $noarray = false, $echo = true ) {
    $submit = ( $submit != '' ) ? trim( $submit ) : _SUBMIT;
    $cancel = ( $cancel != '' ) ? "onclick=\"location='" . htmlspecialchars( trim( $cancel ), ENT_QUOTES ) . "'\"" : "onClick=\"location.href='" . xoops_getenv( 'HTTP_REFERER' ) . "';\"";
    $ret = '
    <form method="post" op="' . $op . '">
    <div class="confirmMsg">' . $msg . '';
    foreach ( $hiddens as $name => $value ) {
        if ( is_array( $value ) && $noarray == true ) {
            foreach ( $value as $caption => $newvalue ) {
                $ret .= '<input type="radio" name="' . $name . '" value="' . htmlspecialchars( $newvalue ) . '" /> ' . $caption;
                $ret .= '<br />';
            }
        } else {
            if ( is_array( $value ) ) {
                foreach ( $value as $new_value ) {
                    $ret .= '<input type="hidden" name="' . $name . '[]" value="' . $new_value . '" />';
                }
            } else {
                $ret .= '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars( $value, ENT_QUOTES ) . '" />';
            }
        }
    }
    $ret .= "</div>";
    $ret .= "<div class='confirmButtons'>
             <input type='button' class='formbutton' name='confirm_back' $cancel value='Cancel' />
             <input type='submit' class='formbutton' name='confirm_submit' value='$submit' />";
    $ret .= $GLOBALS['xoopsSecurity']->getTokenHTML();
    $ret .= "</div></form>";
    if ( $echo ) {
        echo $ret;
    } else {
        return $ret;
    }
}


// /**
// * xoolsa_displayButton()
// *
// * @param string $butt_align
// * @param string $butt_id
// * @param string $class_id
// * @param array $button_array
// * @return
// */
// function xoolsa_displayButton( $butt_align = 'right', $butt_id = 'button', $class_id = 'formbutton' , $button_array = array() ) {
// if ( !is_array( $button_array ) ) {
// return false;
// }
// $ret = "<div style=\"text-align: $butt_align; margin-bottom: 12px;\">\n";
// $ret .= "<form id=\"{$butt_id}\" action=\"showbuttons\">\n";
// foreach ( $button_array as $k => $v ) {
// $ret .= "<input type=\"button\" style=\"cursor: hand;\" class=\"{$class_id}\"  name=\"" . trim( $v ) . "\" onclick=\"location='" . htmlspecialchars( trim( $k ), ENT_QUOTES ) . "'\" value=\"" . trim( $v ) . "\" />&nbsp;&nbsp;";
// }
// $ret .= "</form>\n";
// $ret .= "</div>\n";
// echo $ret;
// }
/**
 * xoolsa_displayImage()
 *
 * @param string $name
 * @param string $title
 * @param string $align
 * @param string $ext
 * @param string $path
 * @param string $size
 * @return
 */
function xoolsa_displayImage( $name = '', $title = '', $align = 'middle', $ext = 'png', $path = '', $size = '' ) {
    if ( empty( $path ) ) {
        $path = 'modules/xooslacore/media/icons';
    }
    if ( !empty( $name ) ) {
        $fullpath = $GLOBALS['xoops']->url( $path . '/' . $name . '.' . $ext );
        $ret = '<img src="' . $fullpath . '" ';
        if ( !empty( $size ) ) {
            $ret = '<img src="' . $fullpath . '" ' . $size;
        }
        $ret .= ' title="' . ucfirst( $title ) . '"';
        $ret .= ' alt="' . $title . '"';
        if ( !empty( $align ) ) {
            $ret .= ' style="vertical-align: ' . $align . '; border: 0px;"';
        }
        $ret .= ' />' . NWLINE;
        return $ret;
    }
    return '';
}

/**
 * xoolsa_displayIcon()
 *
 * @param array $_icon_array
 * @param mixed $key
 * @param mixed $value
 * @param mixed $extra
 * @return
 */
function xoolsa_displayIcon( $_icon_array = array(), $key, $value = null, $extra = null ) {
    $ret = '';
    if ( $value ) {
        foreach( $_icon_array as $_op => $_icon ) {
            $url = ( !is_numeric( $_op ) ) ? $_op . "?{$key}=" . $value : xoops_getenv( 'PHP_SELF' ) . "?op={$_icon}&amp;{$key}=" . $value;
            if ( $extra != null ) {
                $url .= $extra;
            }
            $ret .= '<a href="' . $url . '">' . xoolsa_displayImage( $_icon, xoolsa_displayConstant( "_XL_AD_ICO_" . $_icon ), null, 'png' ) . '</a>';
        }
    }
    return $ret;
}

/**
 * xoolsa_displayConstant()
 *
 * @param mixed $_title
 * @param string $prefix
 * @param string $suffix
 * @return
 */
function xoolsa_displayConstant( $_title, $prefix = '', $suffix = '' ) {
    static $constantArray;

    $time_start = microtime( true );

    $item = "$prefix$_title$suffix";
    if ( !isset( $constantArray[$item] ) ) {
        $constantArray[$item] = constant( strtoupper( $item ) );
    }
    $time_end = microtime( true );

    $time = $time_end - $time_start;
    echo "Did nothing in " . $time . " seconds\n<br />";
    return $constantArray[$item];
}

/**
 * xoolsa_displaySelection()
 *
 * @param array $this_array
 * @param integer $selected
 * @param string $value
 * @param string $size
 * @param mixed $emptyselect
 * @param mixed $multipule
 * @param string $noselecttext
 * @param string $extra
 * @param integer $vvalue
 * @param mixed $echo
 * @return
 */
function xoolsa_displaySelection( $this_array = array(), $selected = 0, $value = '', $size = '', $emptyselect = false , $multipule = false, $noselecttext = "------------------", $extra = '', $vvalue = 0, $echo = true ) {
    if ( $multipule == true ) {
        $ret = "<select size=\"" . $size . "\" name=\"" . $value . "[]\" id=\"" . $value . "[]\" multiple=\"multiple\" $extra>\n";
    } else {
        $ret = "<select size=\"" . $size . "\" name=\"" . $value . "\" id=\"" . $value . "\" $extra>\n";
    }
    if ( $emptyselect )
        $ret .= "<option value=\"\">{$noselecttext}</option>\n";
    if ( count( $this_array ) ) {
        foreach( $this_array as $key => $content ) {
            $opt_selected = '';
            $newKey = ( (int)$vvalue == 1 ) ? $content : $key;
            if ( is_array( $selected ) && in_array( $newKey, $selected ) ) {
                $opt_selected .= ' selected="selected"';
            } else {
                if ( $key == $selected ) {
                    $opt_selected = ' selected="selected"';
                }
            }
            $content = xoops_substr( $content, 0, 24 );
            $ret .= "<option value=\"" . $newKey . "\" $opt_selected>" . $content . "</option>\n";
        }
    }
    $ret .= "</select>\n";
    if ( $echo == true ) {
        echo "<div>' . $ret . '</div>\n<br />\n";
    } else {
        return $ret;
    }
}

?>