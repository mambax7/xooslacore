<?php
/**
 * Name: admin.php
 * Description: admin.php
 *
 * @package : Xoosla Modules
 * @Module : Xoosla Core Module
 * @subpackage : Language
 * @since : v1.00
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: admin.php 0000 23/06/2010 05:28:03 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

define( 'XL_AD_', '' );

/* Breadcrumb System */
define( '_XL_AD_MENU_INDEX', 'Index' );
define( '_XL_AD_NAV_EDIT', 'Edit' );
define( '_XL_AD_NAV_LISTING', 'Listing' );
define( '_XL_AD_NAV_HELP_VIEW', 'Listing' );
define( '_XL_AD_NAV_HELP_HIDE', 'Listing' );
define( '_XL_AD_NAV_TIPS', 'Hints and tips' );

/**
 * Toolbars
 */
define( '_XL_AD_TOOBAR_FILTER', 'Filter' );
define( '_XL_AD_TOOBAR_ANY', 'Any (OR)' );
define( '_XL_AD_TOOBAR_ALL', 'All (AND)' );
define( '_XL_AD_TOOBAR_EXACT', 'Exact Match' );
define( '_XL_AD_TOOBAR_SUBMIT', 'Go' );
define( '_XL_AD_TOOBAR_RESET', 'Reset' );
define( '_XL_AD_TOOBAR_ALLPD', 'All' );
define( '_XL_AD_TOOBAR_ASC', 'ASC' );
define( '_XL_AD_TOOBAR_DESC', 'DESC' );
/**
 * Icons
 */
define( '_XL_AD_ICO_NEW', 'New' );
define( '_XL_AD_ICO_EDIT', 'Edit' );
define( '_XL_AD_ICO_DELETE', 'Delete' );
define( '_XL_AD_ICO_DUPLICATE', 'Clone' );
define( '_XL_AD_ICO_UPDATE', 'Update' );
define( '_XL_AD_ICO_SAVE', 'Save' );
define( '_XL_AD_ICO_CANCEL', 'Cancel' );
define( '_XL_AD_ICO_APPLY', 'Apply' );
// define( '_XL_AD_ICO_APPROVE', 'Approve' );
// define( '_XL_AD_ICO_ABOUT', 'About' );
// define( '_XL_AD_ICO_HELP', 'Help' );
/**
 * Navigation Menus
 */
define( '_XL_AD_PDN_NORECORDS', 'No items available to list' );
define( '_XL_AD_PDN_RECORDSFOUND', 'Displaying Results %s - %s of %s entries' );

/**
 * Page Nagigation
 */
define( '_XL_AD_PNA_PREVIOUS', 'Previous' );
define( '_XL_AD_PNA_END', 'End' );
define( '_XL_AD_PNA_START', 'Start' );
define( '_XL_AD_PNA_NEXT', 'Next' );

/**
 * System defines
 */
define( '_XL_AD_SYS_LOADING', 'Loading' );
define( '_XL_AD_SYS_TURNON', 'Click to toggle this item on.' );
define( '_XL_AD_SYS_TURNOFF', 'Click to toggle this item off.' );
define( '_XL_AD_SYS_ON', 'On' );
define( '_XL_AD_SYS_OFF', 'Off' );
define( '_XL_AD_SYS_YES', 'Yes' );
define( '_XL_AD_SYS_NO', 'No' );

/**
 * System Menus
 */
define( '_XL_AD_ADM_INDEX', 'Index' );
define( '_XL_AD_ADM_MODULEPREFS', 'Preferences' );
define( '_XL_AD_ADM_MODULEHOME', 'Module Home' );
define( '_XL_AD_ADM_MODULEBLOCKS', 'Blocks' );
define( '_XL_AD_ADM_MODULECOMMENTS', 'Comments' );
define( '_XL_AD_ADM_MODULETEMPLATES', 'Templates' );
define( '_XL_AD_ADM_MODULEUPDATE', 'Update' );
define( '_XL_AD_ADM_ICONHELP', 'Help' );
define( '_XL_AD_ADM_ICONABOUT', 'About' );

/**
 * Database
 */
define( '_XL_AD_ADM_DBCTREATED', 'New item created and database updated' );
define( '_XL_AD_ADM_DBUPDATED', 'Item modified and database updated' );
define( '_XL_AD_ADM_DBITEMDUPLICATED', 'Item duplicated and database updated' );
define( '_XL_AD_ADM_DBERROR', 'Database was not updated due to an error!' );
define( '_XL_AD_ADM_DBSELECTEDITEMSUPTATED', 'Selected items modified and database updated' );
define( '_XL_AD_ADM_DBNOTUPDATED', 'Nothing Selected, Database not updated' );
define( '_XL_AD_ADM_DBUPDATEDDELETED', 'Item deleted and database updated' );
define( '_XL_AD_ADM_DBITEMSDUPLICATED', 'Selected items duplicated and database updated' );
define( '_XL_AD_ADM_DBITEMSUPDATED', 'Selected items updated and database updated' );
define( '_XL_AD_ADM_DBITEMSDELETED', 'Selected item deleted and database updated' );

/**
 * Listing Error
 */
define( '_XL_AD_ERR_NOITEMSELECTED', 'Please select an item from the list to %s' )
?>