<?php
/**
 * Name: xoosla.php
 * Description: xoosla.php
 *
 * @package : Xoosla Modules
 * @Module : Xoosla Core
 * @subpackage : Class
 * @since : v1.00
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: xoosla.php 0000 26/03/2009 23/06/2010 06:52:22 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * xoosla.php
 *
 * @package : Class
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @version $Id$
 * @access public
 */
class XooslaKernel {
    var $_module;
    var $_task;
    /**
     * Class Constructor.
     *
     * @param  $
     */
    function __Construct() {
        $this->_module = $GLOBALS['xoopsModule']->getVar( 'dirname' );
        $this->_task = XooslaRequest::getString( 'op', 'display' );
    }

    /**
     * XooslaKernel::setTask()
     *
     * @param mixed $task
     * @return
     */
    function setTask( $task ) {
        $this->_task = $task;
    }

    /**
     * Xoosla::execute()
     *
     * @return
     */
    function __C( $param, $args = array() ) {
        $Controller = XooslaLoad::getController( $param, '', $this->_module );
        if ( is_object( $Controller ) && is_callable( array( $Controller , $this->_task ) ) ) {
            $Controller->setTask( $param );
            $Controller->setModule( $this->_module );
            $Controller->execute();
            return call_user_func_array( array( $Controller , $this->_task ), $args );
        }
        return false;
    }

    /**
     * XooslaKernel::redirect()
     *
     * @return
     */
    function redirect() {
    }
}

?>