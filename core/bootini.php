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
 * @copyright  : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license    : GNU/LGPL, see docs/license.php
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

require_once __DIR__ . DS . 'loader.php';

XooslaLoad('modules.xooslacore.core.libraries.functions');
XooslaLoad('modules.xooslacore.core.class.error');
XooslaLoad('modules.xooslacore.core.class.request');

XooslaLoad('modules.xooslacore.core.model');
XooslaLoad('modules.xooslacore.core.controller');
XooslaLoad('modules.xooslacore.core.view');
XooslaLoad('modules.xooslacore.core.helper');
