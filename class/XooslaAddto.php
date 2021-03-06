<?php namespace XoopsModules\Xooslacore;

/**
 * Name: class.addto.php
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     :
 * @subpackage :
 * @since      : v1.0.0
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

use XoopsModules\Xooslacore;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * XooslaAddto
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaAddto
{
    public $bookMarklist = [];
    public $itemTitle;
    public $itemUrl;
    public $method       = 0; //
    public $layout       = 0; //H or V
    public $addText      = false;

    /**
     * wfp_addto::__construct()
     *
     * @internal param int $layout
     * @internal param mixed $method
     */
    public function __construct()
    {
        define('_MODULE_DIR', \XoopsModules\Xooslacore\Helper::getInstance()->getDirname());
    }

    /**
     * wfp_addto::render()
     *
     * @param string $title
     * @return bool|mixed|string
     * @internal param mixed $fetchOnly
     */
    public function render($title = '')
    {
        $this->itemTitle = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
        $this->addText   = xoops_getModuleOption('bookmarktextadd', _MODULE_DIR);
        $this->layout    = xoops_getModuleOption('bookmarklayout', _MODULE_DIR);
        $this->method    = 0;
        /**
         */
        xoops_load('xoopscache');
        if (xoops_getModuleOption('allowaddthiscode', _MODULE_DIR)) {
            $ret = \XoopsCache::read('xoosla_bookmarks' . md5('xoosla_addthisBookmarks'));
            if (!$ret) {
                $ret = $this->addThisCode();
                \XoopsCache::write('xoosla_bookmarks' . md5('xoosla_addthisBookmarks'), $ret);
            }
        } else {
            $ret = \XoopsCache::read('xoosla_bookmarks' . md5('xoosla_doBookmarks'));
            if (!$ret) {
                $ret = $this->doBookMarks();
                \XoopsCache::write('xoosla_bookmarks' . md5('xoosla_doBookmarks'), $ret);
            }
        }

        return $ret;
    }

    /**
     * XooslaAddto::bookmarklist()
     * @return array
     */
    public function bookMarkList()
    {
        $ret[] = [
            'title' => 'blinklist',
            'url'   => 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Description=&amp;Url=<$BlogItemPermalinkURL$>&amp;Title=<$BlogItemTitle$>'
        ];
        $ret[] = [
            'title' => 'delicious',
            'url'   => 'http://del.icio.us/post?url=<$BlogItemPermalinkURL$>&amp;title=<$BlogItemTitle$>'
        ];
        $ret[] = ['title' => 'digg', 'url' => 'http://digg.com/submit?phase=2&amp;url=<$BlogItemPermalinkURL$>'];
        $ret[] = [
            'title' => 'fark',
            'url'   => 'http://cgi.fark.com/cgi/fark/edit.pl?new_url=<$BlogItemPermalinkURL$>&amp;new_comment=<$BlogItemTitle$>&amp;new_link_other=<$BlogItemTitle$>&amp;linktype=Misc'
        ];
        $ret[] = [
            'title' => 'furl',
            'url'   => 'http://www.furl.net/storeIt.jsp?t=<$BlogItemTitle$>&amp;u=<$BlogItemPermalinkURL$>'
        ];
        $ret[] = [
            'title' => 'newsvine',
            'url'   => 'http://www.newsvine.com/_tools/seed&amp;save?u=<$BlogItemPermalinkURL$>&amp;h=<$BlogItemTitle$>'
        ];
        $ret[] = [
            'title' => 'reddit',
            'url'   => 'http://reddit.com/submit?url=<$BlogItemPermalinkURL$>&amp;title=<$BlogItemTitle$>'
        ];
        $ret[] = [
            'title' => 'simpy',
            'url'   => 'http://www.simpy.com/simpy/LinkAdd.do?href=<$BlogItemPermalinkURL$>&amp;title=<$BlogItemTitle$>'
        ];
        $ret[] = [
            'title' => 'spurl',
            'url'   => 'http://www.spurl.net/spurl.php?title=<$BlogItemTitle$>&amp;url=<$BlogItemPermalinkURL$>'
        ];
        $ret[] = [
            'title' => 'yahoomyweb',
            'url'   => 'http://myweb2.search.yahoo.com/myresults/bookmarklet?t=<$BlogItemTitle$>&amp;u=<$BlogItemPermalinkURL$>'
        ];
        $ret[] = [
            'title' => 'facebook',
            'url'   => 'http://www.facebook.com/sharer.php?u=<$BlogItemPermalinkURL$>&amp;t=<$BlogItemTitle$>'
        ];

        return $ret;
    }

    /**
     * XooslaAddto::addThisCode()
     * @return bool|string
     */
    private function addThisCode()
    {
        $code = xoops_getModuleOption('addthiscode', _MODULE_DIR);
        if (empty($code)) {
            return $this->doBookMarks();
        }

        return $code;
    }

    /**
     * XooslaAddto::doBookMarks()
     * @return string
     */
    private function doBookMarks()
    {
        $ret = '<div>';
        foreach ($this->bookMarkList() as $b_marks) {
            $ret .= '<a rel="nofollow" href="' . $this->getBookMarkUrl($b_marks['url']) . '" title="' . $this->getBookMarkName($b_marks['title']) . '" target="' . $this->method() . '">';
            $ret .= $this->getBookMarkImage($b_marks['title']);
            if (true === $this->addText) {
                $ret .= '&nbsp;' . $this->getBookMarkName($b_marks['title']);
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
     * @return mixed
     */
    private function replace(&$text)
    {
        $patterns           = [];
        $replacements       = [];
        $patterns[]         = '<$BlogItemPermalinkURL$>';
        $replacements[]     = $this->getItemUrl();
        $patterns[]         = '<$BlogItemTitle$>';
        $replacements[]     = $this->getItemTitle();
        $this->text         = $text;
        $this->patterns     = $patterns;
        $this->replacements = $replacements;
        $text               = str_replace($this->patterns, $this->replacements, $this->text);

        return $text;
    }

    /**
     * XooslaAddto::getItemTitle()
     * @return string
     */
    private function getItemTitle()
    {
        return rawurlencode($this->itemTitle);
    }

    /**
     * XooslaAddto::getUrl()
     *
     * @return
     */
    private function getItemUrl()
    {
        return $GLOBALS['xoops']->url('modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/' . basename($_SERVER['SCRIPT_NAME']) . '?' . $_SERVER['QUERY_STRING']);
    }

    /**
     * XooslaAddto::getBookMarkUrl()
     *
     * @param string $value
     * @return mixed|string
     */
    private function getBookMarkUrl($value = '')
    {
        return !empty($value) ? $this->replace($value) : '';
    }

    /**
     * XooslaAddto::getBookMarkImage()
     *
     * @param string $value
     * @return string
     */
    private function getBookMarkImage($value = '')
    {
        $url = $GLOBALS['xoops']->url('modules/xooslacore/images/icon/bookmark/' . $value . '.png');

        return !empty($value) ? '<img style="vertical-align: middle;" src="' . $url . '" border="0" title="' . $this->getBookMarkName($value) . '" alt="' . $this->getBookMarkName($value) . '" >' : '';
    }

    /**
     * XooslaAddto::getBookMarkName()
     *
     * @param $value
     * @return string
     */
    private function getBookMarkName($value)
    {
        return !empty($value) ? XL_MA_BOOKMARKTO . ucfirst((string)$value) : '';
    }

    /**
     * XooslaAddto::method()
     * @return string
     */
    private function method()
    {
        return $this->method ? '_blank' : '_self';
    }

    /**
     * XooslaAddto::layout()
     * @return string
     */
    private function layout()
    {
        return (0 == $this->layout) ? '&nbsp;' : '</div><div>';
    }
}
