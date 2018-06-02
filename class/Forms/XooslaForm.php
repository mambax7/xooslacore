<?php namespace XoopsModules\Xooslacore\Forms;

/**
 * Name: Xoosla Form
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
 * XooslaForm
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaForm extends \XoopsForm
{
    public $_tabs;

    /**
     * XooslaForm::doTabs()
     *
     */
    public function doTabs()
    {
//        require_once $GLOBALS['xoops']->path('modules/xooslacore/core/class/Forms/xoosla_formtabs.php');
        $this->_tabs = new XooslaFormTabs(false);
    }
}