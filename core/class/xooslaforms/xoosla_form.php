<?php
/**
 * Name: Xoosla Form
 * Description:
 *
 * @package : Xoosla Modules
 * @Module :
 * @subpackage :
 * @since : v1.0.0
 * @author John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license : GNU/LGPL, see docs/license.php
 * @version : $Id: form.php 0000 25/03/2009 12:59:34:000 Catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XooslaForm
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaForm extends XoopsForm {
    var $_tabs;

    /**
     * XooslaForm::doTabs()
     *
     * @return
     */
    function doTabs() {
        require_once $GLOBALS['xoops']->path( 'modules/xooslacore/core/class/xooslaforms/xoosla_formtabs.php' );
        $this->_tabs = new XooslaFormTabs( false );
    }
}

?>