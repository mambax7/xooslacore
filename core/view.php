<?php
/**
 * Name: Xoosla View Class
 * Description:
 *
 * @package : Xoosla Modules
 * @Module : Xoosla Core Module
 * @subpackage : Class
 * @since : v1.00
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: model.php 0000 23/06/2010 03:18:22 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XooslaView
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaView {
    var $xooslaTpl;
    var $template;
    var $formName = '';
    var $is_admin = true;
    var $header;
    var $subheader;
    var $breadcrumb;
    var $link;
    var $tips;

    var $intro = 0;
    var $menus = 0;
    var $toolbar = 0;
    var $content = 0;
    var $navigation = 0;
    var $legend = 0;
    var $footer = 0;
    var $select = array();

    /**
     * Constructor
     */
    function __construct() {
    }

    /**
     * XooslaView::execute()
     *
     * @return
     */
    function execute() {
        XooslaLoad( 'class.template' );

        $this->xooslaTpl = new XoopsTpl();
        $this->xooslaTpl->force_compile = 1;
        $this->xooslaTpl->caching = 0;
        $this->xooslaTpl->assign( 'dirpath', XOOPS_ROOT_PATH . '/modules/xooslacore/templates' );
        $this->xooslaTpl->plugins_dir[] = XOOPS_ROOT_PATH . '/modules/xooslacore/templates/plugins/smarty';
        $this->xooslaTpl->assign( 'securityToken', $GLOBALS['xoopsSecurity']->getTokenHTML() );
    }

    /**
     * XooslaView::assign()
     *
     * @return
     */
    function assign( $param, $arg ) {
        $this->xooslaTpl->assign( $param, $arg );
    }

    /**
     * XooslaView::assign_by_ref()
     *
     * @return
     */
    function append_by_ref( $param, $arg ) {
        $this->xooslaTpl->append_by_ref( $param, $arg );
    }

    /**
     * XooslaView::fetch()
     *
     * @return
     */
    function fetch( $param ) {
        return $this->xooslaTpl->fetch( $param );
    }

    /**
     * XooslaView::__Set()
     *
     * @param mixed $param
     * @param mixed $value
     * @return
     */
    function __Set( $param, $value ) {
        $class_vars = get_class_vars( get_class( $this ) );
        if ( in_array( $param, array_keys( $class_vars ) ) ) {
            $this->$param = $value;
        }
    }

    /**
     * XooslaView::setForm()
     *
     * @param string $param
     * @return
     */
    function setForm( $param = '' ) {
        $this->formName = $param;
    }

    /**
     * XooslaView::setTemplate()
     *
     * @param string $param
     * @return
     */
    function setTemplate( $param = '' ) {
        $this->template = $param;
    }

    /**
     * XooslaView::setHeader()
     *
     * @param string $param
     * @return
     */
    // function setHeader( $param = '' ) {
    // $this->header = $param;
    // }
    /**
     * XooslaView::setSubHeader()
     *
     * @param string $param
     * @return
     */
    // function setSubHeader( $param = '' ) {
    // $this->subheader = $param;
    // }
    /**
     * XooslaView::addBreadcrumb()
     *
     * @param mixed $link
     * @param mixed $title
     * @param mixed $home
     * @return
     */
    function addBreadcrumb( $title, $link = '', $home = false ) {
        $this->breadcrumb[] = array(
            'link' => $link,
            'title' => $title,
            'home' => $home
            );
    }

    /**
     * XooslaView::setLink()
     *
     * @return
     */
    function setLink( $param = '' ) {
        $this->subheader = $param;
    }

    /**
     * XooslaView::setTips()
     *
     * @return
     */
    function setTips( $param = '' ) {
        $this->tips = $param;
    }
    /**
     * XooslaView::setMenus()
     *
     * @param mixed $param
     * @return
     */
    function setMenus( $param ) {
        $this->menus = $param;
    }

    /**
     * XooslaView::setIntro()
     *
     * @param mixed $param
     * @return
     */
    function setIntro( $param ) {
        $this->intro = $param;
    }

    /**
     * XooslaView::setToolbar()
     *
     * @param mixed $param
     * @return
     */
    function setToolbar( $param ) {
        $this->toolbar = $param;
    }

    /**
     * XooslaView::setIntro()
     *
     * @param mixed $param
     * @return
     */
    function setContent( $param ) {
        $this->content = $param;
    }

    /**
     * XooslaView::setLegend()
     *
     * @return
     */
    function setLegend( $param ) {
        $this->legend = $param;
    }

    /**
     * XooslaView::setNavigation()
     *
     * @return
     */
    function setNavigation( $tot_num = 0, $num_dis = 10, $start = 0 ) {
        $pageNav = &XooslaLoad::getClass( 'pagenav' );
        $pageNav->setVars( $tot_num, $num_dis, $start, 'start', 'limit=' . $num_dis );
        $this->navigation = $pageNav->render();
    }

    /**
     * XooslaView::setSelect()
     *
     * @return
     */
    function setSelect( $param ) {
        if ( count( $param ) == 3 ) {
            $this->select[] = $param;
        }
    }

    /**
     * XooslaView::getMenu()
     *
     * @return
     */
    function showMenu() {
        $mHandler = &XooslaLoad::getClass( 'menu', $this->formName );
        $mHandler->loadMenu();
        $mHandler->addMenuIcons( $this->menus );

        $mHandler->addBreadcrumb( _XL_AD_MENU_INDEX, $_SERVER['PHP_SELF'], true );
        foreach ( $this->breadcrumb as $breadcrumb ) {
            $mHandler->addBreadcrumb( $breadcrumb['title'], $breadcrumb['link'], $breadcrumb['home'] );
        }
        $mHandler->addHelp( $this->formName );
        $mHandler->addTips( $this->tips );
        $mHandler->render( $this->xooslaTpl );
        unset( $mHandler );
    }

    /**
     * XooslaView::getMenu()
     *
     * @return
     */
    function showIntro() {
        if ( $this->intro ) {
            $this->assign( 'intro', $this->intro );
        }
    }

    /**
     * XooslaView::getToolbar()
     *
     * @return
     */
    function showToolbar() {
        if ( $this->toolbar ) {
            $xooslaToolbar = &XooslaLoad::getClass( 'toolbar' );
            foreach ( $this->select as $select ) {
                $xooslaToolbar->addSelection( $select );
            }
            $xooslaToolbar->render( $this->xooslaTpl );
            $this->assign( 'toolbar', $this->toolbar );
        }
    }

    /**
     * XooslaView::content()
     *
     * @return
     */
    function showContent() {
        if ( $this->content ) {
            $this->assign( 'content', $this->content );
        }
    }

    /**
     * XooslaView::ShowNavigation()
     *
     * @return
     */
    function ShowNavigation() {
        if ( $this->navigation ) {
            $this->assign( 'navigation', $this->navigation );
        }
    }

    /**
     * XooslaView::showLegend()
     *
     * @return
     */
    function showLegend() {
        if ( $this->legend ) {
            $this->assign( 'legend', $this->legend );
        }
    }

    /**
     * XooslaView::shoFooter()
     *
     * @return
     */
    function showFooter() {
        $footer['website_url'] = $GLOBALS['xoopsModule']->getInfo( 'website_url' );
        $footer['website_name'] = $GLOBALS['xoopsModule']->getInfo( 'website_name' );
        $this->assign( 'footer', $footer );
    }

    /**
     * XooslaView::display()
     *
     * @return
     */
    function display() {
        if ( $this->is_admin == true ) {
            xoops_cp_header();
        } else {
            include_once XOOPS_ROOT_PATH . '/header.php';
        }
        if ( $GLOBALS['xoopsConfig']['cpanel'] != 'oxygen' ) {
            $GLOBALS['xoTheme']->addScript( 'browse.php?Frameworks/jquery/jquery.js' );
            $GLOBALS['xoTheme']->addScript( 'modules/xooslacore/thirdparty/jquery/plugins/jquery.ui.js' );
        }
        $GLOBALS['xoTheme']->addScript( 'modules/xooslacore/thirdparty/jquery/plugins/jquery.tablesorter.js' );
        $GLOBALS['xoTheme']->addScript( 'modules/xooslacore/templates/js/xoosla.js' );
        $GLOBALS['xoTheme']->addStylesheet( 'modules/xooslacore/templates/css/module.css' );

        $this->showMenu();
        $this->showIntro();
        $this->showToolbar();
        $this->ShowNavigation();
        // $this->ShowLegend();
        $this->ShowFooter();
        $this->showContent();
        echo $this->fetch( $GLOBALS['xoops']->path( 'modules' . DS . $GLOBALS['xoopsModule']->getVar( 'dirname' ) . DS . 'templates' . DS . $this->template ) );
        if ( $this->is_admin == true ) {
            xoops_cp_footer();
        } else {
            include_once XOOPS_ROOT_PATH . '/footer.php';
        }
    }
}

?>