<?php namespace XoopsModules\Xooslacore;

/**
 * Name: class.help.php
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

use XoopsModules\Xooslacore;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * xoosla_Help
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaHelp
{
    public $path;
    public $filename;

    /**
     * xoosla_Help::xoosla_Help()
     *
     * @internal param string $aboutTitle
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
        if (file_exists($file = $this->_path . '/' . $this->_filename)) {
            $contents = file_get_contents($file);
        }

        $myts = \MyTextSanitizer::getInstance();

        return $myts->displayTarea($contents);
    }
}