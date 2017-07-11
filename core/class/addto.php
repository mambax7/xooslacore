<?php
/**
 * Name: class.addto.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: class.addto.php 0000 27/03/2009 20:23:01:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XooslaAddto
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaAddto {
    var $bookMarklist = array();
    var $itemTitle;
    var $itemUrl;
    var $method = 0; //
    var $layout = 0; //H or V
    var $addText = false;

    /**
     * wfp_addto::__construct()
     *
     * @param integer $layout
     * @param mixed $method
     */
    public function __construct() {
    }

    /**
     * wfp_addto::render()
     *
     * @param mixed $fetchOnly
     * @return
     */
    public function render( $title = '' ) {
        $this->itemTitle = htmlspecialchars( $title );
        $this->addText = xoops_getModuleOption( 'bookmarktextadd', _MODULE_DIR );
        $this->layout = xoops_getModuleOption( 'bookmarklayout', _MODULE_DIR );
        $this->method = 0;
        /**
         */
        xoops_load( 'xoopscache' );
        if ( xoops_getModuleOption( 'allowaddthiscode', _MODULE_DIR ) ) {
            $ret = XoopsCache::read( 'xoosla_bookmarks' . md5( 'xoosla_addthisBookmarks' ) );
            if ( !$ret ) {
                $ret = $this->addThisCode();
                XoopsCache::write( 'xoosla_bookmarks' . md5( 'xoosla_addthisBookmarks' ), $ret );
            }
        } else {
            $ret = XoopsCache::read( 'xoosla_bookmarks' . md5( 'xoosla_doBookmarks' ) );
            if ( !$ret ) {
                $ret = $this->doBookMarks();
                XoopsCache::write( 'xoosla_bookmarks' . md5( 'xoosla_doBookmarks' ), $ret );
            }
        }
        return $ret;
    }

    /**
     * XooslaAddto::bookmarklist()
     *
     * @return
     */
    public function bookMarkList() {
        $ret[] = array( 'title' => 'blinklist', 'url' => 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Description=&amp;Url=<$BlogItemPermalinkURL$>&amp;Title=<$BlogItemTitle$>' );
        $ret[] = array( 'title' => 'delicious', 'url' => 'http://del.icio.us/post?url=<$BlogItemPermalinkURL$>&amp;title=<$BlogItemTitle$>' );
        $ret[] = array( 'title' => 'digg', 'url' => 'http://digg.com/submit?phase=2&amp;url=<$BlogItemPermalinkURL$>' );
        $ret[] = array( 'title' => 'fark', 'url' => 'http://cgi.fark.com/cgi/fark/edit.pl?new_url=<$BlogItemPermalinkURL$>&amp;new_comment=<$BlogItemTitle$>&amp;new_link_other=<$BlogItemTitle$>&amp;linktype=Misc' );
        $ret[] = array( 'title' => 'furl', 'url' => 'http://www.furl.net/storeIt.jsp?t=<$BlogItemTitle$>&amp;u=<$BlogItemPermalinkURL$>' );
        $ret[] = array( 'title' => 'newsvine', 'url' => 'http://www.newsvine.com/_tools/seed&amp;save?u=<$BlogItemPermalinkURL$>&amp;h=<$BlogItemTitle$>' );
        $ret[] = array( 'title' => 'reddit', 'url' => 'http://reddit.com/submit?url=<$BlogItemPermalinkURL$>&amp;title=<$BlogItemTitle$>' );
        $ret[] = array( 'title' => 'simpy', 'url' => 'http://www.simpy.com/simpy/LinkAdd.do?href=<$BlogItemPermalinkURL$>&amp;title=<$BlogItemTitle$>' );
        $ret[] = array( 'title' => 'spurl', 'url' => 'http://www.spurl.net/spurl.php?title=<$BlogItemTitle$>&amp;url=<$BlogItemPermalinkURL$>' );
        $ret[] = array( 'title' => 'yahoomyweb', 'url' => 'http://myweb2.search.yahoo.com/myresults/bookmarklet?t=<$BlogItemTitle$>&amp;u=<$BlogItemPermalinkURL$>' );
        $ret[] = array( 'title' => 'facebook', 'url' => 'http://www.facebook.com/sharer.php?u=<$BlogItemPermalinkURL$>&amp;t=<$BlogItemTitle$>' );
        return $ret;
    }

    /**
     * XooslaAddto::addThisCode()
     *
     * @return
     */
    private function addThisCode() {
        $code = xoops_getModuleOption( 'addthiscode', _MODULE_DIR );
        if ( empty( $code ) ) {
            return $this->doBookMarks();
        }
        return $code;
    }

    /**
     * XooslaAddto::doBookMarks()
     *
     * @return
     */
    private function doBookMarks() {
        $ret = '<div>';
        foreach( $this->bookMarkList() as $b_marks ) {
            $ret .= '<a rel="nofollow" href="' . $this->getBookMarkUrl( $b_marks['url'] ) . '" title="' . $this->getBookMarkName( $b_marks['title'] ) . '" target="' . $this->method() . '">';
            $ret .= $this->getBookMarkImage( $b_marks['title'] );
            if ( $this->addText == true ) {
                $ret .= '&nbsp;' . $this->getBookMarkName( $b_marks['title'] );
            }
            $ret .= '</a>';
            $ret .= $this->layout();
        }
        $ret .= '</div>';
        return $ret;
    }

    /**
     * XooslaAddto::replace()
     *
     * @param mixed $text
     * @return
     */
    private function replace( &$text ) {
        $patterns = array();
        $replacements = array();
        $patterns[] = '<$BlogItemPermalinkURL$>';
        $replacements[] = $this->getItemUrl();
        $patterns[] = '<$BlogItemTitle$>';
        $replacements[] = $this->getItemTitle();
        $this->text = $text;
        $this->patterns = $patterns;
        $this->replacements = $replacements;
        $text = str_replace( $this->patterns, $this->replacements, $this->text );
        return $text;
    }

    /**
     * XooslaAddto::getItemTitle()
     *
     * @return
     */
    private function getItemTitle() {
        return rawurlencode( $this->itemTitle );
    }

    /**
     * XooslaAddto::getUrl()
     *
     * @return
     */
    private function getItemUrl() {
        return $GLOBALS['xoops']->url( 'modules/' . $GLOBALS['xoopsModule']->getVar( 'dirname' ) . '/' . basename( $_SERVER['SCRIPT_NAME'] ) . '?' . $_SERVER['QUERY_STRING'] );
    }

    /**
     * XooslaAddto::getBookMarkUrl()
     *
     * @return
     */
    private function getBookMarkUrl( $value = '' ) {
        return ( !empty( $value ) ) ? $this->replace( $value ) : '';
    }

    /**
     * XooslaAddto::getBookMarkImage()
     *
     * @param string $value
     * @return
     */
    private function getBookMarkImage( $value = '' ) {
        $url = $GLOBALS['xoops']->url( 'modules/xooslacore/images/icon/bookmark/' . $value . '.png' );
        return ( !empty( $value ) ) ? '<img style="vertical-align: middle;" src="' . $url . '" border="0" title="' . $this->getBookMarkName( $value ) . '" alt="' . $this->getBookMarkName( $value ) . '"  />' : '';
    }

    /**
     * XooslaAddto::getBookMarkName()
     *
     * @return
     */
    private function getBookMarkName( $value ) {
        return ( !empty( $value ) ) ? _MD_WFP_BOOKMARKTO . ucfirst( ( string )$value ) : '';
    }

    /**
     * XooslaAddto::method()
     *
     * @return
     */
    private function method() {
        return ( $this->method ) ? '_blank' : '_self';
    }

    /**
     * XooslaAddto::layout()
     *
     * @return
     */
    private function layout() {
        return ( $this->layout == 0 ) ? '&nbsp;' : '</div><div>';
    }
}

?>