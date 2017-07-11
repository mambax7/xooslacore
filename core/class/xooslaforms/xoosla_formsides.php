<?php
/**
 * Name: Xoosla Form Sides
 * Description:
 *
 * @package Xoosla Core Module
 * @subpackage
 * @since v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@users.sourceforge.net>
 * @copyright Copyright (C) 2010 Xoosla. All rights reserved.
 * @license GNU/LGPL, see docs/license.php
 * @version $Id: formimageside.php 0000 17/01/2009 17:37:05:000 Catzwolf $
 */
xoops_load( 'XoopsFormSelect' );

/**
 * xo_FormImageSide
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XoopsFormSides extends XoopsFormSelect {
    /**
     * XoopsFormSides::XoopsFormSides()
     *
     * @param mixed $caption
     * @param mixed $name
     * @param mixed $value
     * @param mixed $size
     */
    function XoopsFormSides( $caption, $name, $value = 0, $size = 1 ) {
        $this->XoopsFormSelect( $caption, $name, $value, $size, 0 );
        $this->addOptionArray( array( 0 => 'Left', 1 => 'Center', 2 => 'Right' ) );
    }
}

?>