<?php
/**
 * Name: bootini.php
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     : Xoosla Core Module
 * @subpackage : Boot
 * @since      : v1.00
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

use XoopsModules\Xooslacore;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

defined('DS') || define('DS', '/');

//require_once __DIR__ .  '/loader.php';
//require_once __DIR__ .  '/XooslaLoad.php';

//Xooslacore\Core\XooslaLoad('modules.xooslacore.core.libraries.functions');
\XoopsModules\Xooslacore\Core\XooslaLoad::getInclude('modules.xooslacore.libraries.functions');
//Xooslacore\XooslaLoad('modules.xooslacore.core.class.error');
//Xooslacore\XooslaLoad('modules.xooslacore.core.class.request');
//
//Xooslacore\XooslaLoad('modules.xooslacore.core.model');
//Xooslacore\XooslaLoad('modules.xooslacore.core.controller');
//Xooslacore\XooslaLoad('modules.xooslacore.core.view');
//Xooslacore\XooslaLoad('modules.xooslacore.core.helper');
