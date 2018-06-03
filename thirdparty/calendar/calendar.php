<?php
/**
 * File: calendar.php | (c) dynarch.com 2004
 *                                   Distributed as part of "The Coolest DHTML Calendar"
 *                                   under the same terms.
 *                                   -----------------------------------------------------------------
 *                                   This file implements a simple PHP wrapper for the calendar.  It
 *                                   allows you to easily include all the calendar files and setup the
 *                                   calendar by instantiating and calling a PHP object.
 */

define('NEWLINE', "\n");

/**
 * DHTML_Calendar
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @access    public
 */
class DHTML_Calendar
{
    public $calendar_lib_path;
    public $calendar_file;
    public $calendar_lang_file;
    public $calendar_setup_file;
    public $calendar_theme_file;
    public $calendar_options;
    public $calendar_theme_url;

    /**
     * DHTML_Calendar::DHTML_Calendar()
     *
     * @param string $calendar_lib_path
     * @param string $lang
     * @param string $theme
     * @param mixed  $stripped
     * @param array  $calendar_options
     * @param array  $calendar_field_attributes
     */
    public function __construct(
        $calendar_lib_path = '',
        $lang = 'en',
        $theme = 'calendar-blue2',
        $stripped = true,
        $calendar_options = [],
        $calendar_field_attributes = []
    ) {
        $this->set_option('date', '');
        $this->set_option('ifFormat', '%m/%d/%Y %H:%M');
        $this->set_option('daFormat', '%m/%d/%Y %H:%M');
        $this->set_option('firstDay', 1); // show Monday first
        // $this->set_option( 'showOthers', true );
        $this->set_option('showsTime', true);

        if ($stripped) {
            $this->calendar_file       = 'calendar_stripped.js';
            $this->calendar_setup_file = 'calendar-setup_stripped.js';
        } else {
            $this->calendar_file       = 'calendar.js';
            $this->calendar_setup_file = 'calendar-setup.js';
        }
        $this->calendar_lang_file  = 'lang/calendar-en.js';
        $this->calendar_lib_path   = '/thirdparty/calendar/';
        $this->calendar_theme_file = 'calendar-blue.css';
        $this->calendar_theme_url  = 'calendar/css/';
    }

    /**
     * DHTML_Calendar::set_option()
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function set_option($name, $value)
    {
        $this->calendar_options[$name] = $value;
    }

    /**
     * DHTML_Calendar::load_files()
     *
     */
    public function load_files()
    {
        $this->get_load_files_code();
    }

    /**
     * DHTML_Calendar::get_load_files_code()
     *
     */
    public function get_load_files_code()
    {
        $GLOBALS['xoTheme']->addStylesheet($this->calendar_theme_url . $this->calendar_theme_file);
        $GLOBALS['xoTheme']->addScript($this->calendar_lib_path . $this->calendar_file);
        $GLOBALS['xoTheme']->addScript($this->calendar_lib_path . $this->calendar_lang_file);
        $GLOBALS['xoTheme']->addScript($this->calendar_lib_path . $this->calendar_setup_file);
    }

    /**
     * DHTML_Calendar::_make_calendar()
     *
     * @param array $other_options
     * @return string
     */
    public function _make_calendar($other_options = [])
    {
        $js_options = $this->_make_js_hash(array_merge($this->calendar_options, $other_options));
        $code       = ('<script type="text/javascript">Calendar.setup({' . $js_options . '});</script>');

        return $code;
    }

    /**
     * DHTML_Calendar::make_input_field()
     *
     * @param array $cal_options
     * @param array $field_attributes
     * @param mixed $show
     * @return string|void
     */
    public function make_input_field($cal_options = [], $field_attributes = [], $show = false)
    {
        $id      = $this->_gen_id();
        $attrstr = $this->_make_html_attr(array_merge($field_attributes, ['id' => $this->_field_id($id), 'type' => 'text']));

        $data    = '<input ' . $attrstr . '>';
        $data    .= '<a href="#" id="' . $this->_trigger_id($id) . '">' . '&nbsp;<img src="' . XOOPS_URL . '/' . $this->calendar_lib_path . 'img.png" style="vertical-align: middle; border: 0px;" alt=""></a>&nbsp;';
        $options = array_merge($cal_options, ['inputField' => $this->_field_id($id), 'button' => $this->_trigger_id($id)]);
        $data    .= $this->_make_calendar($options);
        $show    = false;
        if ($show) {
            echo $data;

            return;
        } else {
            return $data;
        }
    }

    public function _field_id($id)
    {
        return 'f-calendar-field-' . $id;
    }

    /**
     * DHTML_Calendar::_trigger_id()
     *
     * @param mixed $id
     * @return string
     */
    public function _trigger_id($id)
    {
        return 'f-calendar-trigger-' . $id;
    }

    /**
     * DHTML_Calendar::_gen_id()
     * @return int
     */
    public function _gen_id()
    {
        static $id = 0;

        return ++$id;
    }

    /**
     * DHTML_Calendar::_make_js_hash()
     *
     * @param mixed $array
     * @return string
     */
    public function _make_js_hash($array)
    {
        $jstr = '';
        reset($array);
        //        while (list($key, $val) = each($array)) {
        foreach ($array as $key => $val) {
            if (is_bool($val)) {
                $val = $val ? 'true' : 'false';
            } elseif (!is_numeric($val)) {
                $val = '"' . $val . '"';
            }
            if ($jstr) {
                $jstr .= ',';
            }
            $jstr .= '"' . $key . '":' . $val;
        }

        return $jstr;
    }

    /**
     * DHTML_Calendar::_make_html_attr()
     *
     * @param mixed $array
     * @return string
     */
    public function _make_html_attr($array)
    {
        $attrstr = '';
        reset($array);
        //        while (list($key, $val) = each($array)) {
        foreach ($array as $key => $val) {
            $attrstr .= $key . '="' . $val . '" ';
        }

        return $attrstr;
    }
}
