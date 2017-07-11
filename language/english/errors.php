<?php
/**
 * Name: errors.php
 * Description: errors.php
 *
 * @package : Xoosla Modules
 * @Module : Xoosla Core Module
 * @subpackage : Language
 * @since : v1.00
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: errors.php 0000 23/06/2010 05:28:55 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

define( '_XL_ER_', '' );

$url = '<a href="http://code.google.com/p/xoosla-modules/downloads/list" title="Download WF-Resource">download</a>';

/**
 */
define( '_XL_ER_CORE_TECHISSUES', '<h3>Techincal Issues</h3>Sorry, but we seem to be having some techincal issues with this part of our website.<br /><br />Please report this problem to the webmaster.' );

/**
 * Xoosla Object Errors
 */
define( 'XL_ER_VALUE_REQUIRED', '%s' );
define( 'XL_ER_VALUE_SHORTERTHAN', '%s' );
define( 'XL_ER_FAILED_VALIDATION', '%s' );
define( 'XL_ER_EMAIL_INVALID', '%s' );
define( 'XL_ER_URL_INVALID', '%s' );

?>