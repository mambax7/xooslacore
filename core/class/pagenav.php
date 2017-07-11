<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Xoops Editor usage guide
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package kernel
 * @subpackage core
 * @since 2.0.0
 * @author Kazumi Ono <onokazu@xoops.org>
 * @author John Neill <catzwolf@xoops.org>
 * @version $Id: pagenav.php 3437 2009-08-12 13:52:28Z trabis $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * Class to facilitate navigation in a multi page document/list
 *
 * @package kernel
 * @subpackage util
 * @author Kazumi Ono <onokazu@xoops.org>
 * @author John Neill <catzwolf@xoops.org>
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XooslaPageNav {
    /**
     * *#@+
     *
     * @access private
     */
    var $total;
    var $perpage;
    var $current;
    var $url;
    var $records;
    var $from_result;
    var $to_result;
    /**
     * *#@-
     */

    /**
     * Constructor
     *
     * @param int $total_items Total number of items
     * @param int $items_perpage Number of items per page
     * @param int $current_start First item on the current page
     * @param string $start_name Name for "start" or "offset"
     * @param string $extra_arg Additional arguments to pass in the URL
     */
    function XooslaPageNav() {
    }

    /**
     * XooslaPageNav::setVars()
     *
     * @return
     */
    function setVars( $total_items, $items_perpage, $current_start, $start_name = 'start' ) {
        $this->total = (int)$total_items;
        $this->perpage = (int)$items_perpage;
        $this->current = (int)$current_start;
        $this->start_name = $start_name;
    }

    /**
     * Create text navigation
     *
     * @param integer $offset
     * @return string
     */
    function pageNav( $offset = 3 ) {
        $ret = '';
        if ( $this->total <= $this->perpage ) {
            return $ret;
        }
        $total_pages = ceil( $this->total / $this->perpage );

        if ( $total_pages > 1 ) {
            $ret .= '<div>';
            $prev = $this->current - $this->perpage;
            if ( $prev >= 0 ) {
                $ret .= '<a href="#" title="' . _XL_AD_PNA_START . '" onclick="javascript:document.adminform.start.value=0; submitform();return false;">' . _XL_AD_PNA_START . '</a> ';
                $ret .= '<a href="#" title="' . _XL_AD_PNA_PREVIOUS . '" onclick="javascript:document.adminform.start.value=' . $prev . '; submitform();return false;">' . _XL_AD_PNA_PREVIOUS . '</a> ';
            } else {
                $ret .= '<span>' . _XL_AD_PNA_START . '</span> ';
                $ret .= '<span>' . _XL_AD_PNA_PREVIOUS . '</span> ';
            }

            $counter = 1;
            $current_page = intval( floor( ( $this->current + $this->perpage ) / $this->perpage ) );
            while ( $counter <= $total_pages ) {
                if ( $counter == $current_page ) {
                    $ret .= '<span>(' . $counter . ')</span> ';
                } elseif ( ( $counter > $current_page - $offset && $counter < $current_page + $offset ) || $counter == 1 || $counter == $total_pages ) {
                    if ( $counter == $total_pages && $current_page < $total_pages - $offset ) {
                        $ret .= '... ';
                    }
                    $ret .= '<a href="#" title="3" onclick="javascript:document.adminform.start.value=' . ( ( $counter - 1 ) * $this->perpage ) . '; submitform();return false;">' . $counter . ' </a>';
                    if ( $counter == 1 && $current_page > 1 + $offset ) {
                        $ret .= '... ';
                    }
                }
                $counter ++;
            }
            $next = $this->current + $this->perpage;
            $end = ( ( $counter - 1 ) * $this->perpage ) - $this->perpage ;
            if ( $this->total > $next ) {
                $ret .= '<a href="#" title="' . _XL_AD_PNA_NEXT . '" onclick="javascript:document.adminform.start.value=' . $next . '; submitform();return false;">' . _XL_AD_PNA_NEXT . ' </a>';
                $ret .= '<a href="#" title="' . _XL_AD_PNA_END . '" onclick="javascript:document.adminform.start.value=' . $end . '; submitform();return false;">' . _XL_AD_PNA_END . '</a>';
            } else {
                $ret .= '<span>' . _XL_AD_PNA_NEXT . '</span> ';
                $ret .= '<span>' . _XL_AD_PNA_END . '</span> ';
            }
            $ret .= '</div>';
        }
        return $ret;
    }

    /**
     * XooslaPageNav::render()
     *
     * @return
     */
    function render() {
        $this->from_result = $this->current + 1;
        if ( $this->perpage == 0 ) {
            $this->perpage = $this->total;
        }

        $this->to_result = ( $this->current + $this->perpage < $this->total ) ? $this->current + $this->perpage : $this->total;
        if ( $this->from_result > $this->total ) {
            $this->from_result = 1;
            $this->current = 0;
        }

        $ret['records'] = ( $this->total > 0 ) ? sprintf( _XL_AD_PDN_RECORDSFOUND, $this->from_result, $this->to_result, $this->total ): _XL_AD_PDN_NORECORDS;
        $ret['links'] = $this->pageNav();
        return $ret;
    }
}

?>
