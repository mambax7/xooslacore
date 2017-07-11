<?php
/**
 * Name: class.help.php
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
 * xoolsa_Help
 *
 * @package
 * @author John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class XooslaHelp {
    var $path;
    var $filename;
    /**
     * xoolsa_Help::xoolsa_Help()
     *
     * @param string $aboutTitle
     */
    public function __Construct() {
    }

    /**
     * XoopsAbout::display()
     *
     * @return
     */
    public function render() {
        $contents = '';
        $this->_path = $GLOBALS['xoops']->path( 'modules' . DS . $GLOBALS['xoopsModule']->getVar( 'dirname' ) . DS . 'docs' );
        $this->_filename = 'help.txt';
        /**
         */
        $contents = '';
        if ( file_exists( $file = $this->_path . DS . $this->_filename ) ) {
            $contents = file_get_contents( $file );
        }

        $myts = &MyTextSanitizer::getInstance();
        return $myts->displayTarea( $contents );
    }
}

?>