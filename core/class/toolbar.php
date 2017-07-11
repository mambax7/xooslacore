<?php
/**
 * Name: Xoosla ToolBar
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since :
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: class_toolbar.php 0000 22/06/2010 01:33:56 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XooslaToolbar
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaToolbar {
    var $pulldown = array();
    var $cleanvars = array();
    var $vars = array();
    var $resets = array();
    /**
     * Constructor
     */
    function __construct() {
        $this->doLogic();
    }

    /**
     * XooslaToolbar::doLogic()
     *
     * @return
     */
    function doLogic() {
        foreach ( array_keys( $_REQUEST ) as $key ) {
            $this->cleanvars[$key] = $_REQUEST[$key] = XooslaRequest::getString( $key, '' );
        }
        $this->cleanvars['search'] = XooslaRequest::getString( 'search', _XL_AD_TOOBAR_FILTER );
        $this->cleanvars['andor'] = XooslaRequest::getString( 'andor', 'OR' );
        $this->cleanvars['limit'] = XooslaRequest::getInt( 'limit', 10 );
        $this->cleanvars['start'] = XooslaRequest::getInt( 'start', 0 );

        $this->resets[] = 'document.getElementById(\'search\').value=\'' . _XL_AD_TOOBAR_FILTER . '\';';
        $this->resets[] = "document.getElementById('andor').value='OR';";
        $this->resets[] = "document.getElementById('limit').value='10';";
        $this->resets[] = "document.getElementById('order').value='ASC';";
    }

    /**
     * XooslToolbar::calander()
     *
     * @return
     */
    function getCalendar() {
        $display = func_get_arg( 0 );
        $date = func_get_arg( 1 );
        $jstime = formatTimestamp( 'F j Y', time() );
        $value = ( $_REQUEST['date'] == null ) ? '' : strftime( $_REQUEST['date'] );
        require_once XOOPS_ROOT_PATH . '/modules/xooslacore/class/calendar/calendar.php';
        $calendar = new DHTML_Calendar( XOOPS_URL . '/modules/xooslacore/class/calendar/', 'en', 'calendar-system', false );
        $calendar->load_files();
        return $calendar->make_input_field(
            array( 'firstDay' => 1, 'showsTime' => false, 'showOthers' => false, 'ifFormat' => '%Y-%m-%d', 'timeFormat' => '24' ), // field attributes go here
            array( 'style' => '', 'name' => 'date', 'value' => $value ), false
            );
    }

    /**
     * XooslaToolbar::_makeSelection()
     *
     * @return
     */
    function _makeSelection( $params = array() ) {
        if ( count( $params ) == 3 && is_array( $params['options'] ) ) {
            foreach ( $params as $key => $val ) {
                switch ( $key ) {
                    case 'options':
                        $this->vars['options'] = $val;
                        break;
                    case 'name':
                        $this->vars['name'] = $val;
                        $name = $this->vars['name'];
                        break;
                    case 'value':
                        $this->vars['value'] = ( isset( $this->cleanvars[$name] ) )? $this->cleanvars[$name]: $val;
                        break;
                } // switch
            }
            // Hack to stop the limit value from being changed again
            if ( $this->vars['name'] != 'limit' ) {
                $this->resets[] = 'document.getElementById(\'$name\').value=\'0\';';
            }
            $ret = "<select size=\"1\" name=\"$name\" id=\"$name\" onchange=\"document.adminform.submit();\">\n";
            if ( count( $this->vars['options'] ) ) {
                foreach( $this->vars['options'] as $k => $v ) {
                    $selected = '';
                    if ( $k == $this->vars['value'] ) {
                        $selected = ' selected="selected"';
                    }
                    $ret .= "<option value=\"{$k}\" $selected>{$v}</option>\n";
                }
            }
            $ret .= "</select>\n";
            $this->pulldown[] = $ret;
        }
    }

    /**
     * XooslToolbar::selection()
     *
     * @return
     */
    function addSelection( $params ) {
        $this->_makeSelection( $params );
    }

    /**
     * XooslaToolbar::getPulldowns()
     *
     * @return
     */
    function getPulldowns() {
        return $this->pulldown;
    }

    /**
     * XooslToolbar::render()
     *
     * @return
     */
    function render( &$tpl ) {
        foreach ( $this->cleanvars as $k => $v ) {
            $tpl->assign( $k, $v );
        }
        // $tpl->assign( 'calendar', $this->getCalendar() );
        $tpl->assign( 'pulldowns', $this->getPulldowns() );
        $tpl->assign( 'resets', $this->resets );
        unset( $tpl );
    }
}

?>