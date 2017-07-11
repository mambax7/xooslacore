<?php
/**
 * Name: class.print.php
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: class.print.php 0000 29/03/2009 21:28:08:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

xoops_loadLanguage( 'print', 'xooslacore' );
/**
 * XooslaDoprint
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaDoprint {
    var $options = array();
    var $compression = false;
    var $font = 'helvetica';
    var $fontsize = '12';

    /**
     * XooslaDoprint::__construct()
     */
    function __construct() {
    }

    /**
     * XooslaDoprint::setOptions()
     *
     * @param array $opt
     * @return
     */
    function setOptions( $opt = array() ) {
        if ( !is_array( $opt ) || empty( $opt ) ) {
            return false;
        }
        $this->options = $opt;
    }

    /**
     * XooslaDoprint::doRender()
     *
     * @return
     */
    function doRender() {
        $ret = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $ret .= "\n";
        $ret .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . _LANGCODE . '" lang="' . _LANGCODE . '">';
        $ret .= "<head>\n";
        $ret .= "<title>" . _XL_AD_ADM_PRINTER . " - " . $this->options['title'] . " - " . $this->options['sitename'] . "</title>\n";
        $ret .= "<meta http-equiv='Content-Type' content='text/html; charset=" . _CHARSET . "' />\n";
        $ret .= "<meta name='author' content='" . $this->options['sitename'] . "' />\n";
        $ret .= "<meta name='keywords' content='" . @$this->options['keywords'] . "' />\n";
        $ret .= "<meta name='copyright' content='Copyright (c) 2006 by " . $this->options['sitename'] . "' />\n";
        $ret .= "<meta name='description' content='" . @$this->options['meta'] . "' />\n";
        $ret .= "<meta name='generator' content='Xoops' />\n";
        $ret .= "<style type=\"text/css\">
            body { margin: 10px; font-family: {$this->font}; font-size: {$this->fontsize}px; }
            div { font-family: inherit; }
            a:link { color: #000000; }
            a:visited { color: #000000; }
            a:active { color: #000000; }
            a:hover { color: #ff0000; }
            </style>";
        $ret .= "</head>\n";
        $ret .= "<body bgcolor='#ffffff' text='#000000' onload=''>\n
                 <div>
                       <table border=\"0\" width=\"100%\"  cellpadding=\"2\" cellspacing=\"1\" bgcolor=\"#ffffff\">
                        <thead>
                         <tr>
                          <th colspan=\"3\" width=100% style='text-align: left;'>" . $this->options['slogan'] . "<th>
                         </tr>
                        </thead>
                        <tfoot>
                         <tr>
                          <td width=30% style='text-align: left;'>" . XOOPS_URL . "</td>
                          <td width=40% style='text-align: center;'>" . $this->options['creator'] . "</td>
                          <td width=30% style='text-align: right;'>" . _CONTENT_RENDERED . ' ' . $this->options['renderdate'] . "</td>
                         </tr>
                        </tfoot>
                        <tr>
                         <td colspan=\"3\" align=\"left\">
                           <hr />
                           <h2>" . $this->options['title'] . "</h2>\n
                           <div>" . _CONTENT_AUTHOR . " " . @$this->options['author'] . "</div>
                           <div>" . _CONTENT_PUBLISHED . " " . @$this->options['pdate'] . "</div>";
        if ( isset( $this->options['pdate'] ) ) {
            $ret .= "<div>" . _CONTENT_UPDATED . " " . @$this->options['udate'] . "</div>";
        }

        if ( isset( $this->options['itemurl'] ) ) {
            $ret .= "<br /><br />" . _CONTENT_URL_TOITEM . " " . $this->options['itemurl'] . "<br /><br />";
        }
        $ret .= "<br /><div><strong>{$this->options['subtitle']}</strong></div><br />
                        </td>\n
                       </tr>\n
                       <tr colspan=\"3\" valign='top' style='font:12px;'>
                        <td colspan=\"3\">" . $this->options['content'] . "<br /><br />";
        $ret .= "<hr /></td>
                       </tr>
                      </table>
                     </div>
                    <br />
                <div style='text-align: center;'><input type=button value='" . _XL_AD_ADM_PRINT_PAGE . "' onclick='window.print();'></div><br />
                </body></html>\n";
        echo $ret;
    }

    /**
     * XooslaDoprint::setTitle()
     *
     * @param string $value
     * @return
     */
    function setTitle( $value = '' ) {
        $this->options['title'] = $value;
    }

    /**
     * XooslaDoprint::setSubTitle()
     *
     * @param string $value
     * @return
     */
    function setSubTitle( $value = '' ) {
        $this->options['subtitle'] = $value;
    }

    /**
     * XooslaDoprint::setCreater()
     *
     * @param string $value
     * @return
     */
    function setCreater( $value = '' ) {
        $this->options['creator'] = $value;
    }

    /**
     * XooslaDoprint::setSlogan()
     *
     * @param string $value
     * @return
     */
    function setSlogan( $value = '' ) {
        $this->options['slogan'] = $value;
    }

    /**
     * XooslaDoprint::setAuthor()
     *
     * @param string $value
     * @return
     */
    function setAuthor( $value = '' ) {
        $this->options['author'] = $value;
    }

    /**
     * XooslaDoprint::setContent()
     *
     * @param string $value
     * @return
     */
    function setContent( $value = '' ) {
        $this->options['content'] = $value;
    }

    /**
     * XooslaDoprint::setPDate()
     *
     * @param string $value
     * @return
     */
    function setPDate( $value = '' ) {
        $this->options['pdate'] = $value;
    }

    /**
     * XooslaDoprint::setUDate()
     *
     * @param string $value
     * @return
     */
    function setUDate( $value = '' ) {
        $this->options['udate'] = $value;
    }

    /**
     * XooslaDoprint::setUrul()
     *
     * @param string $value
     * @return
     */
    function setUrul( $value = '' ) {
        $this->options['itemurl'] = $value;
    }

    /**
     * XooslaDoprint::setFont()
     *
     * @param string $value
     * @return
     */
    function setFont( $value = '' ) {
        $this->font = $value;
    }

    /**
     * XooslaDoprint::setFontSize()
     *
     * @param string $value
     * @return
     */
    function setFontSize( $value = 5 ) {
        $this->fontsize = (int)$value;
    }
}

?>