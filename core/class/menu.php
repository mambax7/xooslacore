<?php
/**
 * Name: Menu Class
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     :
 * @subpackage :
 * @since      : v1.0.0
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license    : GNU/LGPL, see docs/license.php
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

xoops_loadLanguage('menu', 'xooslacore');

/**
 * XooslaMenu
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @author    Andricq Nicolas (AKA MusS)
 * @copyright Copyright (c) 2006
 * @version   $Id: addonmenu.php,v 1.1 2007/03/16 02:39:10 catzwolf Exp $
 * @access    public
 */
class XooslaMenu
{
    private $adminmenu = [];

    public $menu_helpfile;
    public $menu_breadcrumb = [];
    public $menu_help;
    public $menu_tip;

    /**
     * XooslaMenu::__construct()
     * @param $directory
     */
    public function __construct($directory)
    {
        $this->menu_helpfile = $directory;
    }

    /**
     * XooslaMenu::loadXooslaMenu()
     * @return array|callsMenu|cssholmesMenu|int|slideshowMenu|Xmf_Template_Adminmenu
     */
    public function loadXooslaMenu()
    {
        global $menu;

        $menu = [];
        include XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/admin/menu.php';

        return $menu;
    }

    /**
     * XooslaMenu::loadMenu()
     *
     * @return
     */
    /**
     * XooslaMenu::loadMenu()
     *
     */
    public function loadMenu()
    {
        static $adminObject;
        $menus = [];
        // if ( !isset( $adminObject ) ) {
        $this->loadXooslaMenu();
        foreach (array_keys($menus) as $menu) {
            foreach ($menus[$menu] as $item) {
                $adminmenu[$menu][$item[0]] = htmlspecialchars($item[1]);
            }
        }
        // }
        $this->getXoopsMenuIconsForModule();
        $this->adminmenu =& $adminObject;
    }

    /**
     * XooslaMenu::getXoopsMenuIconsForModule()
     *
     */
    public function getXoopsMenuIconsForModule()
    {
        $option = $GLOBALS['xoopsConfig']['cpanel'];
        // switch ($option) {
        // case 'oxygen':
        // $dir = 'modules/system/class/gui/oxygen/icons';
        // break;
        // case 'exm':
        // $dir = 'modules/system/class/gui/exm/icons';
        // break;
        // default:
        $dir = 'modules/xooslacore/media/icons/gui';
        // break;
        // } // switch
        $mod_options = $GLOBALS['xoopsModule']->getAdminMenu();
        foreach (array_keys($mod_options) as $item) {
            $mod_options[$item]['link'] = $GLOBALS['xoops']->url($mod_options[$item]['link']);
            $mod_options[$item]['icon'] = $GLOBALS['xoops']->url($dir . '/' . $mod_options[$item]['icon']);
        }
        $GLOBALS['xoTheme']->template->clear_assign('mod_options');
        $GLOBALS['xoTheme']->template->assign('mod_options', $mod_options);
    }

    /**
     * XooslaMenu::addLink()
     *
     * @param string $title
     * @param string $link
     * @param mixed  $home
     */
    public function addBreadcrumb($title = '', $link = '', $home = false)
    {
        $this->menu_breadcrumb[] = [
            'link'  => $link,
            'title' => $title,
            'home'  => $home
        ];
    }

    /**
     * XooslaMenu::addHelp()
     *
     * @param string $param
     * @internal param string $link
     */
    public function addHelp($param = '')
    {
        $this->menu_help = $param;
    }

    /**
     * XooslaMenu::addTips()
     *
     * @param string $param
     * @internal param mixed $value
     */
    public function addTips($param = '')
    {
        $this->menu_tip = $param;
    }

    /**
     * XooslaMenu::addMenuTopArray()
     *
     * @param mixed $options
     * @internal param mixed $multi
     */
    public function addMenuLinks($options)
    {
        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $this->adminmenu['link'][$k] = $v;
            }
        }
    }

    /**
     * XooslaMenu::addMenuTabsArray()
     *
     * @param mixed $options
     * @internal param mixed $multi
     */
    public function addMenuTabs($options)
    {
        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $this->adminmenu['tab'][$k] = $v;
            }
        }
    }

    /**
     * XooslaMenu::addMenuIconsArray()
     *
     * @param mixed $options
     * @param mixed $multi
     */
    public function addMenuIcons($options, $multi = true)
    {
        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $this->adminmenu['icon'][$k] = $v;
            }
        }
    }

    /**
     * XooslaMenu::getMenuLinks()
     * @return mixed|string
     */
    private function getMenuLinks()
    {
        /**
         * Menu Top Links
         */

        return isset($this->adminmenu['link']) ? $this->adminmenu['link'] : '';
    }

    /**
     * XooslaMenu::getMenuTabs()
     * @return string
     * @internal param mixed $currentoption
     */
    private function getMenuTabs()
    {
        $this->menuId = 0;
        /**
         * Menu Items
         */
        $menuBottomTabs = '';
        if (isset($this->adminmenu['tab'])) {
            foreach ($this->adminmenu['tab'] as $k => $v) {
                $menuItems[] = $v;
            }
            $breadcrumb               = $menuItems[$this->menuId];
            $menuItems[$this->menuId] = 'current';
            $i                        = 0;
            $menuBottomTabs           = '';
            if (isset($this->adminmenu['tab'])) {
                foreach ($this->adminmenu['tab'] as $k => $v) {
                    $menuBottomTabs .= '<li id="' . strtolower(str_replace(' ', '_', $menuItems[$i])) . '"><a href="' . htmlentities($k) . '"><span>' . $v . '</span></a></li>';
                    ++$i;
                }
            }
        }

        return $menuBottomTabs;
    }

    /**
     * XooslaMenu::getNavMenuIcons()
     * @return array
     */
    private function getNavMenuIcons()
    {
        $menu = [];
        if (isset($this->adminmenu['icon'])) {
            foreach ($this->adminmenu['icon'] as $k => $v) {
                switch ($k) {
                    case 'cancel':
                        $menu[$k]['key']   = $k;
                        $menu[$k]['title'] = $v;

                        // $menu[$k]['link'] = "javascript:history.go(-1);return true";
                        $menu[$k]['link']  = "location.href='" . xoops_getenv('PHP_SELF') . "';return false";
                        $menu[$k]['image'] = "<span class=\"icon-32-$k\" title=\"$v\">&nbsp;</span>";
                        break;

                    case 'apply':
                    case 'save':
                        $menu[$k]['key']   = $k;
                        $menu[$k]['title'] = $v;
                        $menu[$k]['link']  = "submitValidateForm('$k');return false";
                        $menu[$k]['image'] = "<span class=\"icon-32-$k\" title=\"$v\">&nbsp;</span>";
                        break;

                    case 'about':
                    case 'help':
                    case 'create':
                        $menu[$k]['key']   = $k;
                        $menu[$k]['title'] = $v;
                        $menu[$k]['link']  = "javascript:submitform('$k');return false";
                        $menu[$k]['image'] = "<span class=\"icon-32-$k\" title=\"$v\">&nbsp;</span>";
                        break;
                    default:
                        $menu[$k]['key']   = $k;
                        $menu[$k]['title'] = $v;
                        $menu[$k]['link']  = "javascript:if (document.adminform.boxchecked.value==0) {alert('" . sprintf(_XL_AD_ERR_NOITEMSELECTED, $k) . "');return false} else { submitform('$k');return false}";
                        $menu[$k]['image'] = "<span class=\"icon-32-$k\" title=\"$v\">&nbsp;</span>";
                        break;
                } // switch
            }
        }

        return array_reverse($menu);
    }

    /**
     * XooslaMenu::render()
     *
     * @param $tpl
     * @internal param int $currentoption
     * @internal param mixed $display
     */
    public function render(&$tpl)
    {
        $this->getMenuTabs();

        $tpl->assign('xo_menu_breadcrumb', $this->menu_breadcrumb);
        $tpl->assign('xo_menu_help', $this->menu_help);
        if ($this->menu_tip) {
            $tpl->assign('xo_menu_tips', $this->menu_tip);
        }
        if (file_exists($file = $GLOBALS['xoops']->path('/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/help/' . $this->menu_helpfile . '.html'))) {
            $tpl->assign('xo_menu_helpfile', $file);
        }
        $tpl->assign('xo_menu_tabs', $this->getMenuTabs());
        $tpl->assign('menu_icons', $this->getNavMenuIcons());
        $tpl->assign('menu_links', $this->getMenuLinks());
    }
}
