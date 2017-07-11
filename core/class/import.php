<?php
/**
 * Name: class.import.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: class.help.php 0000 25/03/2009 21:18:26:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XooslaImport
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaImport {
    var $content = array();
    var $temp = '';
    var $htmlpath = 'uploads/import';
    var $htmlfile;
    var $imagepath = '';
    var $cleanLevel = 0;

    /**
     * XooslaImport::XooslaImport()
     *
     * @param string $aboutTitle
     */
    function __construct() {
    }

    /**
     * XooslaImport::setHtmlPath()
     *
     * @return
     */
    function setHtmlPath( $value = '' ) {
        $this->htmlpath = preg_replace( '|/$|', '', $value );
    }

    /**
     * XooslaImport::setFile()
     *
     * @param string $value
     * @return
     */
    function setHtmlFile( $value = '' ) {
        $this->htmlfile = $value;
    }

    /**
     * XooslaImport::setImageDir()
     *
     * @param string $value
     * @return
     */
    function setImagePath( $value = '' ) {
        $this->imagepath = preg_replace( '|/$|', '', $value );
    }

    /**
     * XooslaImport::setImageDir()
     *
     * @param string $value
     * @return
     */
    function setClean( $value = 0 ) {
        $this->cleanLevel = (int)$value;
    }

    /**
     * XooslaImport::fileExists()
     *
     * @param mixed $file
     * @return
     */
    function getContentsCallback() {
        if ( file_exists( $this->buildPath() ) && is_readable( $this->buildPath() ) ) {
            $this->temp = file_get_contents( $this->buildPath() );
        }
    }

    /**
     * XooslaImport::importHtml()
     *
     * @return
     */
    function importHtml() {
        if ( !$this->fileExists() ) {
            return false;
        }
        $this->getContentsCallback();
        $this->getTitle();
        $this->getContent();
        return $this->content;
    }

    /**
     * XooslaImport::getTitle()
     *
     * @return
     */
    function getTitle() {
        if ( preg_match( '|<\s*title\s?.*?>(.*)<\s*/\s*title\s*>|is', $this->temp, $match ) ) {
            $this->content['title'] = ucwords( $match[1] );
        }
        if ( empty( $this->content['title'] ) ) {
            $this->content['title'] = str_replace( '.html', '' , $this->htmlfile );
        }
    }

    /**
     * XooslaImport::getContent()
     *
     * @return
     */
    function getContent() {
        if ( preg_match( '|<\s*body\s?.*?>(.*)<\s*/\s*body\s*>|is', $this->temp, $match ) ) {
            $this->content['content'] = $match[1];
        } else {
            $this->content['content'] = $this->temp;
        }

        if ( $this->imagepath ) {
            $dir = $GLOBALS['xoops']->url( $this->imagepath );
            $this->content['content'] = preg_replace( '|<\s*img\s?src=[\"\'](?!/)(.*?)[\"\']\s*(.*?)\s*>|is', "<img src=\"$dir/$1\" $2>", $this->content['content'] );
        }
        $this->content['content'] = &$this->cleanUpHTML( $this->content['content'], $this->cleanLevel );
    }

    /**
     * XooslaImport::doImageCopy()
     *
     * @return
     */
    function doImageCopy() {
        xoops_load( 'xoopslists' );
        $imageList = XoopsLists::getImgListAsArray( $GLOBALS['xoops']->path( $this->htmlpath ) );
        if ( !file_exists( $GLOBALS['xoops']->path( $this->imagepath ) ) ) {
            mkdir( $GLOBALS['xoops']->path( $this->imagepath ), 0707 );
        }
        foreach ( $imageList as $image ) {
            $file = $GLOBALS['xoops']->path( $this->htmlpath . DS . $image );
            $file_dest = $GLOBALS['xoops']->path( $this->imagepath . DS . $image );
            copy( $file, $file_dest );
        }
    }

    /**
     * XooslaImport::cleanUpHTML()
     *
     * @param mixed $text
     * @param mixed $cleanlevel
     * @return
     */
    function &cleanUpHTML( $text, $cleanlevel = 0 ) {
        // $text = stripslashes( $text );
        $htmltidy = &XooslaLoad::getClass( 'htmltidy', '', _RESOURCE_DIR, _RESOURCE_CLASS );
        $htmltidy->Options['UseTidy'] = false;
        $htmltidy->Options['OutputXHTML'] = false;
        $htmltidy->Options['Optimize'] = true;
        $htmltidy->Options['Compress'] = true;
        switch ( $cleanlevel ) {
            case 1:
                $htmltidy->html = $text;
                $text = &$htmltidy->cleanUp();
                break;
            case 2:
                $text = preg_replace( '/\<style[\w\W]*?\<\/style\>/i', '', $text );
                $htmltidy->Options['IsWord'] = true;
                $htmltidy->html = $text;
                $text = &$htmltidy->cleanUp();
                break;
            case 3:
                $text = preg_replace( '/\<style[\w\W]*?\<\/style\>/i', '', $text );
                $htmltidy->Options['IsWord'] = true;
                $htmltidy->html = $text;
                $text = &$htmltidy->cleanUp();
                $text = strip_tags( $text, '<br /><br /><p>' );
                break;
            default:
        } // switch
        return $text;
    }

    /**
     * XooslaImport::buildPath()
     *
     * @return
     */
    function buildPath() {
        return $GLOBALS['xoops']->path( $this->htmlpath . DS . $this->htmlfile );
    }

    /**
     * XooslaImport::fileExists()
     *
     * @return
     */
    function fileExists() {
        if ( preg_match( "/^[\.]{1,2}$/", $this->htmlfile ) ) {
            return false;
        }
        /**
         * Make sure file is not in the root
         */
        if ( XOOPS_ROOT_PATH == $this->buildPath() ) {
            return false;
        }

        if ( !file_exists( $this->buildPath() ) ) {
            return false;
        }
        return true;
    }
}

?>