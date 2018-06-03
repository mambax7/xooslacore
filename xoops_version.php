<?php
/**
 * Name: Xoosla Core Module
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     : Xoosla Core Module
 * @subpackage :
 * @since      : v1.00
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName = basename(__DIR__);

/**
 * Module Details
 */
$modversion['version']             = 1.00;
$modversion['requires']            = 1.00;
$modversion['module_status']       = 'Beta 1';
$modversion['release_date']        = '2017/05/14';
$modversion['name']                = XL_MI_XOOSLACORE;
$modversion['description']         = XL_MI_XOOSLACORE_DSC;
$modversion['author']              = XL_MI_XOOSLA_AUTHOR;
$modversion['credits']             = '';
$modversion['status']              = XL_MI_XOOSLA_STATUS;
$modversion['lead']                = XL_MI_XOOSLA_LEAD;
$modversion['contributors']        = XL_MI_XOOSLA_CONTRIBUTORS;
$modversion['website_url']         = 'https://xoops.org';
$modversion['website_name']        = 'XOOPS Project';
$modversion['email']               = '';
$modversion['demo_site_url']       = '';
$modversion['demo_site_name']      = '';
$modversion['support_site_url']    = '';
$modversion['support_site_name']   = '';
$modversion['submit_bug_url']      = '';
$modversion['submit_bug_name']     = '';
$modversion['submit_feature_url']  = '';
$modversion['submit_feature_name'] = '';
$modversion['disclaimer']          = '';
$modversion['license']             = '';
$modversion['official']            = 0;
$modversion['image']               = 'assets/images/logoModule.png';
$modversion['dirname']             = $moduleDirName;

/**
 * SQL
 */
// $modversion['sqlfile']['mysql'] = 'media/sql/mysql.sql';
/**
 * Mysql Tables
 */
// $modversion['tables'][] = '';
/**
 * Admin things
 */
$modversion['hasAdmin']   = 0;
//$modversion['adminindex'] = 'admin/index.php';
//$modversion['adminmenu']  = 'admin/menu.php';

/**
 * Additionnal script executed during install update
 */
// $modversion['onInstall'] = 'include/oninstall.php';
// $modversion['onUpdate'] = 'include/onupdate.php';
// $modversion['onUninstall'] = 'include/onuninstall.php';
/**
 * Frontend
 */
// $modversion['hasMain'] = 0;
/**
 * Frontend
 */
// $modversion['hasSearch'] = 0;
/**
 * Comments
 */
// $modversion['hasComments'] = 0;
/**
 * Notifications
 */
// $modversion['hasNotification'] = 0;
/**
 * Blocks
 */
// $modversion['blocks'][] = array( 'file' => '',
// 'name' => '',
// 'description' => '',
// 'show_func' => 'textbox',
// 'edit_func' => 'text',
// 'options' => '',
// 'template' => ''
// );
/**
 * Templates
 */
// $modversion['templates'][] = array( 'file' => '',
// 'description' => '' );
/**
 * Module Configuration
 */
// $modversion['config'][] = array( 'name' => '',
// 'title' => '',
// 'description' => '',
// 'formtype' => 'textbox',
// 'valuetype' => 'text',
// 'default' => ''
// );
