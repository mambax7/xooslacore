<?php
/**
 * Name: xoopsformloader.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: xoopsformloader.php 0000 08/04/2009 22:02:14:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

XooslaLoad( 'class.xoopsformloader' );
/**
 * Start of Xoosla Forms includes
 */
include_once XOOPS_ROOT_PATH . '/modules/xooslacore/core/class/xooslaforms/themeform.php';
include_once XOOPS_ROOT_PATH . '/modules/xooslacore/core/class/xooslaforms/themetabform.php';
include_once XOOPS_ROOT_PATH . '/modules/xooslacore/core/class/xooslaforms/xoosla_formselectimage.php';
include_once XOOPS_ROOT_PATH . '/modules/xooslacore/core/class/xooslaforms/xoosla_formsides.php';
include_once XOOPS_ROOT_PATH . '/modules/xooslacore/core/class/xooslaforms/xoosla_formtextdateselect.php';

?>