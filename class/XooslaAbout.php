<?php namespace XoopsModules\Xooslacore;

/**
 * Name: class.about.php
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     :
 * @subpackage :
 * @since      : v1.0.0
 * @author     John Neill <catzwolf@xoosla.com> Neill <catzwolf@xoosla.com>
 * @copyright  : Copyright (C) 2010 Xoosla. All rights reserved.
 * @license    : GNU/LGPL, see docs/license.php
 */

use XoopsModules\Xooslacore;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_loadLanguage('about', 'xooslacore');

/**
 * XooslaAbout
 *
 * @package
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaAbout
{
    /**
     * XooslaAbout::XooslaAbout()
     */
    public function __construct()
    {
    }

    /**
     * XooslaAbout::render()
     * @return string
     */
    public function render()
    {
        $author_name = $GLOBALS['xoopsModule']->getInfo('author') ?: '';
        $ret         = '<p><img src="' . XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/' . $GLOBALS['xoopsModule']->getInfo('image') . '" align="left" title="' . $GLOBALS['xoopsModule']->getInfo('name') . '" alt="' . $GLOBALS['xoopsModule']->getInfo('name') . '" hspace="5" vspace="0"></a>
                <div style="margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 16px; line-height: 16px; font-weight: bold; display: block;">' . $GLOBALS['xoopsModule']->getInfo('name') . ' Version ' . $GLOBALS['xoopsModule']->getInfo('version') . '</div>
                <div><strong>' . _XL_AD_AB_RELEASEDATE . '</strong> ' . $GLOBALS['xoopsModule']->getInfo('releasedate') . '</div>
                <div><strong>' . _XL_AD_AB_AUTHOR . '</strong> ' . $author_name . '</div>
                <div>' . $GLOBALS['xoopsModule']->getInfo('license') . '</div><br>
            </p>';

        $ret .= $this->about_header(_XL_AD_AB_MAIN_INFO);
        $ret .= $this->about_content(_XL_AD_AB_MODULE, 'name');
        $ret .= $this->about_content(_XL_AD_AB_DESCRIPTION, 'description');
        $ret .= $this->about_content(_XL_AD_AB_AUTHOR, 'author');
        $ret .= $this->about_content(_XL_AD_AB_VERSION, 'version');
        $ret .= $this->about_content(_XL_AD_AB_STATUS, 'status');
        $ret .= $this->about_footer();
        /**
         */
        $ret .= $this->about_header(_XL_AD_AB_DEV_INFO);
        $ret .= $this->about_content(_XL_AD_AB_LEAD, 'lead');
        $ret .= $this->about_content(_XL_AD_AB_CONTRIBUTORS, 'contributors');
        $ret .= $this->about_content(_XL_AD_AB_WEBSITE_URL, 'website_url', 'website_name', 'url');
        $ret .= $this->about_content(_XL_AD_AB_EMAIL, 'email', '', 'email');
        $ret .= $this->about_content(_XL_AD_AB_CREDITS, 'credits');
        $ret .= $this->about_content(_XL_AD_AB_LICENSE, 'license');
        $ret .= $this->about_footer();
        /**
         */
        $ret .= $this->about_header(_XL_AD_AB_SUPPORT_INFO);
        $ret .= $this->about_content(_XL_AD_AB_DEMO_SITE_URL, 'demo_site_url', 'demo_site_name', 'url');
        $ret .= $this->about_content(_XL_AD_AB_SUPPORT_SITE_URL, 'support_site_url', 'support_site_name', 'url');
        $ret .= $this->about_content(_XL_AD_AB_SUBMIT_BUG, 'submit_bug_url', 'submit_bug_name', 'url');
        $ret .= $this->about_content(_XL_AD_AB_SUBMIT_FEATURE, 'submit_feature_url', 'submit_feature_name', 'url');
        $ret .= $this->about_footer();
        /**
         */
        $ret .= $this->about_header(_XL_AD_AB_DISCLAIMER);
        $ret .= $this->about_content('', 'disclaimer', null, null, 1);
        $ret .= $this->about_footer();
        /**
         */
        $ret .= $this->about_header(_XL_AD_AB_CHANGELOG);
        $ret .= $this->about_content('', 'changelog', null, null, 1);
        $ret .= $this->about_footer();

        return $ret;
    }

    /**
     * XooslaAbout::about_header()
     *
     * @param mixed $heading
     * @return string
     */
    private function about_header($heading = null)
    {
        return '<table width="100%" cellpadding="2" cellspacing="1" class="outer"><tr><th colspan="2">' . $heading . '</th></tr>';
    }

    /**
     * XooslaAbout::about_content()
     *
     * @param string $heading
     * @param string $value
     * @param string $value2
     * @param string $type
     * @param mixed  $colspan
     * @return string
     */
    private function about_content($heading = '', $value = '', $value2 = '', $type = 'normal', $colspan = null)
    {
        $myts    = \MyTextSanitizer::getInstance();
        $heading = $heading ?: '';
        switch ($type) {
            case 'normal':
            default:
                $value = empty($value) ? '' : ('changelog' === $value) ? $this->changelog() : $GLOBALS['xoopsModule']->getInfo($value);
                switch ($colspan) {
                    case 0:
                        return '<tr><td style="width: 35%;" class="head">' . $heading . '</td><td class="even">' . $value . '</td></tr>';
                        break;
                    case 1:
                        return '<tr><th colspan="2">' . $heading . '</th></tr><tr><td colspan="2" class="even">' . $myts->displayTarea($value) . '</td></tr>';
                        break;
                } // switch
                break;
            case 'url':
                $value  = $value ? $GLOBALS['xoopsModule']->getInfo($value) : '';
                $value2 = $value2 ? $GLOBALS['xoopsModule']->getInfo($value2) : '';

                return '<tr><td style="width: 35%;" class="head">' . $heading . '</td><td class="even"><a href="' . $value . '" target="_blank">' . $value2 . '</a></td></tr>';
                break;
            case 'email':
                $value = $value ? $GLOBALS['xoopsModule']->getInfo($value) : '';

                return '<tr><td style="width: 35%;" class="head">' . $heading . '</td><td class="even"><a href="mailto:' . $value . '">' . $value . '</a></td></tr>';
                break;
        } // switch
    }

    /**
     * XooslaAbout::about_footer()
     * @return string
     */
    private function about_footer()
    {
        return '</table><br>';
    }

    /**
     * XooslaAbout::changelog()
     * @return bool|string
     */
    private function changelog()
    {
        $file_name = $GLOBALS['xoops']->path('modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/docs/changelog.txt');
        if (file_exists($file_name) && !is_dir($file_name)) {
            $changelog = file_get_contents($file_name);
        } else {
            $changelog = _XL_AD_AB_NOLOG;
        }

        return $changelog;
    }
}
