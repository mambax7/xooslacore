<?php namespace XoopsModules\Xooslacore;

/**
 * Name: class.pdf.php
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
use XoopsModules\Xooslacore\Core;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_loadLanguage('print', 'xooslacore');

/**
 * XooslaDopdf
 *
 * @package
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  Copyright (C) 2010 Xoosla. All rights reserved.
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @access    public
 */
class XooslaDopdf
{
    public $options     = [];
    public $compression = false;
    public $font        = 'Helvetica.afm';
    public $cachekey    = null;
    public $cachedir;

    /**
     * XooslaDopdf::__construct()
     */
    public function __construct()
    {
    }

    /**
     * XooslaDopdf::setOptions()
     *
     * @param array $opt
     * @return bool
     */
    public function setOptions($opt = [])
    {
        if (!is_array($opt) || empty($opt)) {
            return false;
        }
        $this->cachedir = XOOPS_ROOT_PATH . '/cache/';
        $this->options  = $opt;
    }

    /**
     * XooslaDopdf::doRender()
     *
     */
    public function doRender()
    {
        error_reporting(0);
        $this->stdoutput = $this->getCache($this->options['id'], $this->options['title']);
        if (!$this->stdoutput) {
            /**
             */
            require_once XOOSLACORE_ROOT_PATH . '/thirdparty/pdf/class.ezpdf.php';
            $pdf                         = new \Cezpdf('a4', 'P'); //A4 Portrait
            $pdf->options['compression'] = $this->compression;
            $pdf->ezSetCmMargins(2, 1.5, 1, 1);
            // select font
            $pdf->selectFont(XOOSLACORE_ROOT_PATH . '/thirdparty/pdf/fonts/' . $this->font, _CHARSET); //choose font
            $all = $pdf->openObject();
            $pdf->saveState();
            $pdf->setStrokeColor(0, 0, 0, 1);
            // footer
            $pdf->addText(30, 822, 6, $this->options['slogan']);
            $pdf->line(10, 40, 578, 40);
            $pdf->line(10, 818, 578, 818);
            // add url to footer
            $pdf->addText(30, 34, 6, XOOPS_URL);
            // add pdf creater
            $pdf->addText(250, 34, 6, $this->options['creator']);
            // add render date to footer
            $pdf->addText(450, 34, 6, _CONTENT_RENDERED . ' ' . $this->options['renderdate']);
            $pdf->restoreState();
            $pdf->closeObject();
            $pdf->addObject($all, 'all');
            $pdf->ezSetDy(30);
            // title
            $pdf->ezText(strip_tags($this->options['title']), 16);
            $pdf->ezText("\n", 6);
            if (!empty($this->options['author'])) {
                $pdf->ezText(_CONTENT_AUTHOR . $this->options['author'], 8);
            }
            if (!empty($this->options['pdate'])) {
                $pdf->ezText(_CONTENT_PUBLISHED . $this->options['pdate'], 8);
            }
            if (!empty($this->options['udate'])) {
                $pdf->ezText(_CONTENT_UPDATED . $this->options['udate'], 8);
            }
            $pdf->ezText("\n", 6);
            if ($this->options['itemurl']) {
                $pdf->ezText(_CONTENT_URL_TOITEM . $this->options['itemurl'], 8);
                $pdf->ezText("\n", 6);
            }

            if ($this->options['subtitle']) {
                $pdf->ezText($this->options['subtitle'], 14);
                $pdf->ezText("\n", 6);
            }
            $pdf->ezText($this->getContent(), 10);
            if ('file' === $this->options['stdoutput']) {
                $this->stdoutput = $pdf->ezOutput(0);
                $this->createCache($this->options['id'], $this->options['title']);
            } else {
                $pdf->ezStream(1);
                exit();
            }
        }
        $this->doDisplay();
    }

    /**
     * XooslaDopdf::doDisplay()
     *
     */
    public function doDisplay()
    {
        $fileName = (isset($this->options['title']) ? $this->options['title'] . '.pdf' : 'file.pdf');
        header('Content-type: application/pdf');
        header('Content-Length: ' . strlen(ltrim($fileName)));
        header('Content-Disposition: inline; filename=' . $fileName);
        if (isset($options['Accept-Ranges']) && 1 == $options['Accept-Ranges']) {
            header('Accept-Ranges: ' . strlen(ltrim($tmp)));
        }
        echo $this->stdoutput;
        exit();
    }

    /**
     * XooslaDopdf::setTitle()
     *
     * @param string $value
     */
    public function setTitle($value = '')
    {
        $this->options['title'] = $value;
    }

    /**
     * XooslaDopdf::setSubTitle()
     *
     * @param string $value
     */
    public function setSubTitle($value = '')
    {
        $this->options['subtitle'] = $value;
    }

    /**
     * XooslaDopdf::setCreater()
     *
     * @param string $value
     */
    public function setCreater($value = '')
    {
        $this->options['creator'] = $value;
    }

    /**
     * XooslaDopdf::setSlogan()
     *
     * @param string $value
     */
    public function setSlogan($value = '')
    {
        $this->options['slogan'] = $value;
    }

    /**
     * XooslaDopdf::setAuthor()
     *
     * @param string $value
     */
    public function setAuthor($value = '')
    {
        $this->options['author'] = $value;
    }

    /**
     * XooslaDopdf::setContent()
     *
     * @param string $value
     */
    public function setContent($value = '')
    {
        $this->options['content'] = $value;
    }

    /**
     * XooslaDopdf::setPDate()
     *
     * @param string $value
     */
    public function setPDate($value = '')
    {
        $this->options['pdate'] = $value;
    }

    /**
     * XooslaDopdf::setUDate()
     *
     * @param string $value
     */
    public function setUDate($value = '')
    {
        $this->options['udate'] = $value;
    }

    /**
     * XooslaDopdf::setFont()
     *
     * @param string $value
     */
    public function setFont($value = '')
    {
        $this->font = trim($value);
    }

    /**
     * XooslaDopdf::useCompression()
     *
     * @param mixed $value
     */
    public function useCompression($value = false)
    {
        $this->compression = true === $value;
    }

    /**
     * XooslaDopdf::getContent()
     * @return mixed
     */
    public function getContent()
    {
        return $this->cleanPDF($this->options['content']);
    }

    /**
     * XooslaClean::cleanPDF()
     *
     * @param $text
     * @return mixed
     */
    public function cleanPDF($text)
    {
        $myts = \MyTextSanitizer::getInstance();
        $text = $myts->undoHtmlSpecialChars($text);
        $text = preg_replace('/\<style[\w\W]*?\<\/style\>/i', '', $text);
        $text = preg_replace("/<img[^>]+\>/i", '', $text);
        $text = str_replace('[pagebreak]', '<br><br>', $text);

//        $htmlcleaner = Xooslacore\Core\XooslaLoad::getClass('XooslaHtmlCleaner', '', _RESOURCE_DIR, _RESOURCE_CLASS);
        $htmlcleaner = new \XoopsModules\Xooslacore\XooslaHtmlCleaner();
        if ($htmlcleaner) {
            $htmlcleaner->Options['UseTidy']     = false;
            $htmlcleaner->Options['OutputXHTML'] = true;
            $htmlcleaner->Options['Optimize']    = true;
            $htmlcleaner->Options['Compress']    = true;
            $htmlcleaner->html                   = $text;
            $text                             = $htmlcleaner->cleanUp();
        }

        $text = str_replace(['<p>', '</p>'], "\n", $text);
        $text = str_replace('<P>', "\n", $text);
        $text = str_replace('<br>', "\n", $text);
        $text = str_replace('<br>', "\n", $text);
        $text = str_replace('<b>', "\n", $text);
        $text = str_replace('<br>', "\n", $text);
        $text = str_replace('<li>', "\n - ", $text);
        $text = str_replace('<LI>', "\n - ", $text);
        $text = str_replace('[pagebreak]', '', $text);
        $text = strip_tags(ltrim($text));
        $text = htmlspecialchars_decode(ltrim($text));

        return $text;
    }

    /**
     * XooslaDopdf::setFilename()
     *
     * @param mixed $id
     * @param mixed $title
     * @return string
     */
    public function setFilename($id, $title)
    {
        $id    = md5((int)$id);
        $title = str_replace(' ', '_', strtolower($title));

        return 'xoosla_pdffile' . md5($GLOBALS['xoopsModule']->getVar('dirname') . $id . $title) . '.pdf';
    }

    /**
     * XooslaDopdf::getCache()
     *
     * @param mixed $id
     * @param mixed $title
     * @return bool
     */
    public function getCache($id, $title)
    {
        xoops_load('xoopscache');
        $this->stdoutput = \XoopsCache::read($this->setFilename($id, $title));
        if ($this->stdoutput) {
            $this->doDisplay();
            exit();
        }

        return false;
    }

    /**
     * XooslaDopdf::deleteCache()
     *
     * @param mixed $id
     * @param mixed $title
     */
    public function deleteCache($id, $title)
    {
        $loaded = xoops_load('xoopscache');
        \XoopsCache::delete($this->setFilename($id, $title));
    }

    /**
     * XooslaDopdf::createCache()
     *
     * @param mixed $id
     * @param mixed $title
     */
    public function createCache($id, $title)
    {
        xoops_load('xoopscache');
        \XoopsCache::write($this->setFilename($id, $title), $this->stdoutput);
    }
}
