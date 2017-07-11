<?php
/**
 * Name: class.pdf.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: class.pdf.php 0000 25/03/2009 21:17:42:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

xoops_loadLanguage( 'print', 'xooslacore' );
/**
 * XooslaDopdf
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaDopdf {
    var $options = array();
    var $compression = false;
    var $font = 'Helvetica.afm';
    var $cachekey = null;

    /**
     * XooslaDopdf::__construct()
     */
    function __construct() {
    }

    /**
     * XooslaDopdf::setOptions()
     *
     * @param array $opt
     * @return
     */
    function setOptions( $opt = array() ) {
        if ( !is_array( $opt ) || empty( $opt ) ) {
            return false;
        }
        $this->cachedir = XOOPS_ROOT_PATH . '/cache/';
        $this->options = $opt;
    }

    /**
     * XooslaDopdf::doRender()
     *
     * @return
     */
    function doRender() {
        error_reporting( 0 );
        $this->stdoutput = self::getCache( $this->options['id'], $this->options['title'] );
        if ( !$this->stdoutput ) {
            /**
             */
            require_once _WFP_RESOURCE_PATH . '/class/pdf/class.ezpdf.php';
            $pdf = new Cezpdf( 'a4', 'P' ); //A4 Portrait
            $pdf->options['compression'] = $this->compression;
            $pdf->ezSetCmMargins( 2, 1.5, 1, 1 );
            // select font
            $pdf->selectFont( _WFP_RESOURCE_PATH . '/class/pdf/fonts/' . $this->font, _CHARSET ); //choose font
            $all = $pdf->openObject();
            $pdf->saveState();
            $pdf->setStrokeColor( 0, 0, 0, 1 );
            // footer
            $pdf->addText( 30, 822, 6, $this->options['slogan'] );
            $pdf->line( 10, 40, 578, 40 );
            $pdf->line( 10, 818, 578, 818 );
            // add url to footer
            $pdf->addText( 30, 34, 6, XOOPS_URL );
            // add pdf creater
            $pdf->addText( 250, 34, 6, $this->options['creator'] );
            // add render date to footer
            $pdf->addText( 450, 34, 6, _CONTENT_RENDERED . ' ' . $this->options['renderdate'] );
            $pdf->restoreState();
            $pdf->closeObject();
            $pdf->addObject( $all, 'all' );
            $pdf->ezSetDy( 30 );
            // title
            $pdf->ezText( strip_tags( $this->options['title'] ) , 16 );
            $pdf->ezText( "\n", 6 );
            if ( !empty( $this->options['author'] ) ) {
                $pdf->ezText( _CONTENT_AUTHOR . $this->options['author'], 8 );
            }
            if ( !empty( $this->options['pdate'] ) ) {
                $pdf->ezText( _CONTENT_PUBLISHED . $this->options['pdate'], 8 );
            }
            if ( !empty( $this->options['udate'] ) ) {
                $pdf->ezText( _CONTENT_UPDATED . $this->options['udate'], 8 );
            }
            $pdf->ezText( "\n", 6 );
            if ( $this->options['itemurl'] ) {
                $pdf->ezText( _CONTENT_URL_TOITEM . $this->options['itemurl'], 8 );
                $pdf->ezText( "\n", 6 );
            }

            if ( $this->options['subtitle'] ) {
                $pdf->ezText( $this->options['subtitle'], 14 );
                $pdf->ezText( "\n", 6 );
            }
            $pdf->ezText( $this->getContent(), 10 );
            if ( $this->options['stdoutput'] == 'file' ) {
                $this->stdoutput = $pdf->ezOutput( 0 );
                self::createCache( $this->options['id'], $this->options['title'] );
            } else {
                $pdf->ezStream( 1 );
                exit();
            }
        }
        self::doDisplay();
    }

    /**
     * XooslaDopdf::doDisplay()
     *
     * @return
     */
    function doDisplay() {
        $fileName = ( isset( $this->options['title'] ) ? $this->options['title'] . '.pdf' : 'file.pdf' );
        header( 'Content-type: application/pdf' );
        header( "Content-Length: " . strlen( ltrim( $fileName ) ) );
        header( "Content-Disposition: inline; filename=" . $fileName );
        if ( isset( $options['Accept-Ranges'] ) && $options['Accept-Ranges'] == 1 ) {
            header( "Accept-Ranges: " . strlen( ltrim( $tmp ) ) );
        }
        echo $this->stdoutput;
        exit();
    }

    /**
     * XooslaDopdf::setTitle()
     *
     * @param string $value
     * @return
     */
    function setTitle( $value = '' ) {
        $this->options['title'] = $value;
    }

    /**
     * XooslaDopdf::setSubTitle()
     *
     * @param string $value
     * @return
     */
    function setSubTitle( $value = '' ) {
        $this->options['subtitle'] = $value;
    }

    /**
     * XooslaDopdf::setCreater()
     *
     * @param string $value
     * @return
     */
    function setCreater( $value = '' ) {
        $this->options['creator'] = $value;
    }

    /**
     * XooslaDopdf::setSlogan()
     *
     * @param string $value
     * @return
     */
    function setSlogan( $value = '' ) {
        $this->options['slogan'] = $value;
    }

    /**
     * XooslaDopdf::setAuthor()
     *
     * @param string $value
     * @return
     */
    function setAuthor( $value = '' ) {
        $this->options['author'] = $value;
    }

    /**
     * XooslaDopdf::setContent()
     *
     * @param string $value
     * @return
     */
    function setContent( $value = '' ) {
        $this->options['content'] = $value;
    }

    /**
     * XooslaDopdf::setPDate()
     *
     * @param string $value
     * @return
     */
    function setPDate( $value = '' ) {
        $this->options['pdate'] = $value;
    }

    /**
     * XooslaDopdf::setUDate()
     *
     * @param string $value
     * @return
     */
    function setUDate( $value = '' ) {
        $this->options['udate'] = $value;
    }

    /**
     * XooslaDopdf::setFont()
     *
     * @param string $value
     * @return
     */
    function setFont( $value = '' ) {
        $this->font = strval( trim( $value ) );
    }

    /**
     * XooslaDopdf::useCompression()
     *
     * @param mixed $value
     * @return
     */
    function useCompression( $value = false ) {
        $this->compression = ( $value == true ) ? true : false;
    }

    /**
     * XooslaDopdf::getContent()
     *
     * @return
     */
    function getContent() {
        return self::cleanPDF( $this->options['content'] );
    }

    /**
     * XooslaClean::cleanPDF()
     *
     * @return
     */
    function cleanPDF( $text ) {
        $myts = &MyTextSanitizer::getInstance();
        $text = $myts->undoHtmlSpecialChars( $text );
        $text = preg_replace( '/\<style[\w\W]*?\<\/style\>/i', '', $text );
        $text = preg_replace( "/<img[^>]+\>/i", '', $text );
        $text = str_replace( '[pagebreak]', '<br /><br />', $text );

        $htmltidy = &XooslaLoad::getClass( 'htmltidy', '', _RESOURCE_DIR, _RESOURCE_CLASS );
        if ( $htmltidy ) {
            $htmltidy->Options['UseTidy'] = false;
            $htmltidy->Options['OutputXHTML'] = true;
            $htmltidy->Options['Optimize'] = true;
            $htmltidy->Options['Compress'] = true;
            $htmltidy->html = $text;
            $text = $htmltidy->cleanUp();
        }

        $text = str_replace( array( '<p>', '</p>' ), "\n", $text );
        $text = str_replace( '<P>', "\n", $text );
        $text = str_replace( '<br />', "\n", $text );
        $text = str_replace( '<br>', "\n", $text );
        $text = str_replace( '<BR />', "\n", $text );
        $text = str_replace( '<BR>', "\n", $text );
        $text = str_replace( '<li>', "\n - ", $text );
        $text = str_replace( '<LI>', "\n - ", $text );
        $text = str_replace( '[pagebreak]', '', $text );
        $text = strip_tags( ltrim( $text ) );
        $text = htmlspecialchars_decode( ltrim( $text ) );
        return $text;
    }

    /**
     * XooslaDopdf::setFilename()
     *
     * @param mixed $id
     * @param mixed $title
     * @return
     */
    function setFilename( $id, $title ) {
        $id = md5( ( int )$id );
        $title = str_replace( ' ', '_', strtolower( $title ) );
        return 'xoosla_pdffile' . md5( $GLOBALS['xoopsModule']->getVar( 'dirname' ) . $id . $title ) . '.pdf';
    }

    /**
     * XooslaDopdf::getCache()
     *
     * @param mixed $id
     * @param mixed $title
     * @return
     */
    function getCache( $id, $title ) {
        xoops_load( 'xoopscache' );
        $this->stdoutput = XoopsCache::read( self::setFilename( $id, $title ) );
        if ( $this->stdoutput ) {
            self::doDisplay();
            exit();
        }
        return false;
    }

    /**
     * XooslaDopdf::deleteCache()
     *
     * @param mixed $id
     * @param mixed $title
     * @return
     */
    function deleteCache( $id, $title ) {
        $loaded = xoops_load( 'xoopscache' );
        XoopsCache::delete( self::setFilename( $id, $title ) );
    }

    /**
     * XooslaDopdf::createCache()
     *
     * @param mixed $id
     * @param mixed $title
     * @return
     */
    function createCache( $id, $title ) {
        xoops_load( 'xoopscache' );
        XoopsCache::write( self::setFilename( $id, $title ), $this->stdoutput );
    }
}

?>