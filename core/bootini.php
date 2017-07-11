<?php
/**
 * Name: bootini.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module : Xoosla Core Module
 * @subpackage : Boot
 * @since : v1.00
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: bootini.php 0000 23/06/2010 10:57:03 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

defined( 'DS' ) or define( 'DS', DIRECTORY_SEPARATOR );

require_once( dirname( __FILE__ ) . DS . 'loader.php' );

XooslaLoad( 'modules.xooslacore.core.libraries.functions' );
XooslaLoad( 'modules.xooslacore.core.class.error' );
XooslaLoad( 'modules.xooslacore.core.class.request' );

XooslaLoad( 'modules.xooslacore.core.model' );
XooslaLoad( 'modules.xooslacore.core.controller' );
XooslaLoad( 'modules.xooslacore.core.view' );
XooslaLoad( 'modules.xooslacore.core.helper' );
?>