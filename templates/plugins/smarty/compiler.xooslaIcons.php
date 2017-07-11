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

function smarty_compiler_xooslaIcons( $argStr, &$smarty ) {
    global $xoops, $xoTheme;

    if ( file_exists( $xoops->path( 'modules/xooslacore/media/icons/gui/index.html' ) ) ) {
        $url = $xoops->url( 'modules/xooslacore/media/icons/gui/' . $argStr );
        return "\necho '" . addslashes( $url ) . "';";
    }
    return "\necho '" . addslashes( $xoops->url( 'modules/xooslacore/media/icons/icon-16-info.png' ) ) . "';";
}

?>