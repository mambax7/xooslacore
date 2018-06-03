<?php namespace XoopsModules\Xooslacore;

/**
 * Name: class.help.php
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
 * xoosla_Help
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaHelp
{
    public $path;
    public $filename;

    /**
     * XooslaHelp::__construct()
     *
     */
    public function __construct()
    {
    }

    /**
     * XoopsAbout::display()
     *
     * @return
     */
    public function render()
    {
        $contents        = '';
        $this->_path     = $GLOBALS['xoops']->path('modules/' .  $GLOBALS['xoopsModule']->getVar('dirname') . '/docs');
        $this->_filename = 'help.txt';
        /**
         */
        $contents = '';
        if (file_exists($file = $this->path . '/' . $this->filename)) {
            $contents = file_get_contents($file);
        }

        $myts = \MyTextSanitizer::getInstance();

        return $myts->displayTarea($contents);
    }
}
