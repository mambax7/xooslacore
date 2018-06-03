<?php namespace XoopsModules\Xooslacore;

/**
 * Name: Xoosla ToolBar
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     :
 * @subpackage :
 * @since      :
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

use XoopsModules\Xooslacore;
use XoopsModules\Xooslacore\Core;
use Xmf\Request;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * XooslaToolbar
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaToolbar
{
    public $pulldown  = [];
    public $cleanvars = [];
    public $vars      = [];
    public $resets    = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->doLogic();
    }

    /**
     * XooslaToolbar::doLogic()
     *
     */
    public function doLogic()
    {
        foreach (array_keys($_REQUEST) as $key) {
            $this->cleanvars[$key] = $_REQUEST[$key] = XooslaRequest::getString($key, '');
        }
        $this->cleanvars['search'] = \Xmf\Request::getString('search', _XL_AD_TOOBAR_FILTER);
        $this->cleanvars['andor']  = \Xmf\Request::getString('andor', 'OR');
        $this->cleanvars['limit']  = \Xmf\Request::getInt('limit', 10);
        $this->cleanvars['start']  = \Xmf\Request::getInt('start', 0);

        $this->resets[] = 'document.getElementById(\'search\').value=\'' . _XL_AD_TOOBAR_FILTER . '\';';
        $this->resets[] = "document.getElementById('andor').value='OR';";
        $this->resets[] = "document.getElementById('limit').value='10';";
        $this->resets[] = "document.getElementById('order').value='ASC';";
    }

    /**
     * XooslToolbar::calander()
     * @return string|void
     */
    public function getCalendar()
    {
        $display = func_get_arg(0);
        $date    = func_get_arg(1);
        $jstime  = formatTimestamp('F j Y', time());
        $value   = (null === $_REQUEST['date']) ? '' : strftime($_REQUEST['date']);
        require_once XOOPS_ROOT_PATH . '/modules/xooslacore/thirdparty/calendar/calendar.php';
        $calendar = new \DHTML_Calendar(XOOPS_URL . '/modules/xooslacore/thirdparty/calendar/', 'en', 'calendar-system', false);
        $calendar->load_files();

        return $calendar->make_input_field([
                                               'firstDay'   => 1,
                                               'showsTime'  => false,
                                               'showOthers' => false,
                                               'ifFormat'   => '%Y-%m-%d',
                                               'timeFormat' => '24'
                                           ], // field attributes go here
                                           ['style' => '', 'name' => 'date', 'value' => $value], false);
    }

    /**
     * XooslaToolbar::_makeSelection()
     *
     * @param array $params
     */
    public function _makeSelection($params = [])
    {
        if (3 == count($params) && is_array($params['options'])) {
            foreach ($params as $key => $val) {
                switch ($key) {
                    case 'options':
                        $this->vars['options'] = $val;
                        break;
                    case 'name':
                        $this->vars['name'] = $val;
                        $name               = $this->vars['name'];
                        break;
                    case 'value':
                        $this->vars['value'] = isset($this->cleanvars[$name]) ? $this->cleanvars[$name] : $val;
                        break;
                } // switch
            }
            // Hack to stop the limit value from being changed again
            if ('limit' !== $this->vars['name']) {
                $this->resets[] = 'document.getElementById(\'$name\').value=\'0\';';
            }
            $ret = "<select size=\"1\" name=\"$name\" id=\"$name\" onchange=\"document.adminform.submit();\">\n";
            if (count($this->vars['options'])) {
                foreach ($this->vars['options'] as $k => $v) {
                    $selected = '';
                    if ($k == $this->vars['value']) {
                        $selected = ' selected';
                    }
                    $ret .= "<option value=\"{$k}\" $selected>{$v}</option>\n";
                }
            }
            $ret              .= "</select>\n";
            $this->pulldown[] = $ret;
        }
    }

    /**
     * XooslToolbar::selection()
     *
     * @param $params
     */
    public function addSelection($params)
    {
        $this->_makeSelection($params);
    }

    /**
     * XooslaToolbar::getPulldowns()
     * @return array
     */
    public function getPulldowns()
    {
        return $this->pulldown;
    }

    /**
     * XooslToolbar::render()
     *
     * @param $tpl
     */
    public function render(&$tpl)
    {
        foreach ($this->cleanvars as $k => $v) {
            $tpl->assign($k, $v);
        }
        // $tpl->assign( 'calendar', $this->getCalendar() );
        $tpl->assign('pulldowns', $this->getPulldowns());
        $tpl->assign('resets', $this->resets);
        unset($tpl);
    }
}
