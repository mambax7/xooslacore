<?php namespace XoopsModules\Xooslacore;

/**
 * Name: xoosla.php
 * Description: xoosla.php
 *
 * @package    : Xoosla Modules
 * @Module     : Xoosla Core
 * @subpackage : Class
 * @since      : v1.00
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
 * xoosla.php
 *
 * @package   : Class
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaKernel
{
    public $_module;
    public $_task;

    /**
     * Class Constructor.
     *
     * @internal param $ $
     */
    public function __construct()
    {
        global $xoopsModule;
//        $this->_module = $GLOBALS['XoopsModule']->getVar('dirname');
        $this->_module = $xoopsModule->getVar('dirname');
        $this->_task   = \Xmf\Request::getString('op', 'display');
    }

    /**
     * XooslaKernel::setTask()
     *
     * @param mixed $task
     */
    public function setTask($task = 'display')
    {
        $this->_task = $task;
    }

    /**
     * Xoosla::execute()
     *
     * @param       $param
     * @param array $args
     * @return bool|mixed
     */
    public function __C($param, $args = [])
    {
        $Controller = \XoopsModules\Xooslacore\Core\XooslaLoad::getController($param, '', $this->_module);
        if (is_object($Controller) && is_callable([$Controller, $this->_task])) {
            $Controller->setTask($param);
            $Controller->setModule($this->_module);
            $Controller->execute();

            return call_user_func_array([$Controller, $this->_task], $args);
        }

        return false;
    }

    /**
     * XooslaKernel::redirect()
     *
     */
    public function redirect()
    {
    }
}
