<?php
/**
 * Name: xoosla.php
 * Description: xoosla.php
 *
 * @package    : Xoosla Modules
 * @Module     : Xoosla Core
 * @subpackage : Class
 * @since      : v1.00
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license    : GNU/LGPL, see docs/license.php
 */

use Xmf\Request;

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * xoosla.php
 *
 * @package   : Class
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @version   $Id$
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
        $this->_module = $GLOBALS['xoopsModule']->getVar('dirname');
        $this->_task   = XooslaRequest::getString('op', 'display');
    }

    /**
     * XooslaKernel::setTask()
     *
     * @param mixed $task
     */
    public function setTask($task)
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
    public function __C($param, $args = array())
    {
        $Controller = XooslaLoad::getController($param, '', $this->_module);
        if (is_object($Controller) && is_callable(array($Controller, $this->_task))) {
            $Controller->setTask($param);
            $Controller->setModule($this->_module);
            $Controller->execute();

            return call_user_func_array(array($Controller, $this->_task), $args);
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
