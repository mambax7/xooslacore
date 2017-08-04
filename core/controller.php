<?php
/**
 * Name: Xoosla Controller Class
 * Description:
 *
 * @package    : Xoosla Modules
 * @Module     : Xoosla Core Module
 * @subpackage :
 * @since      : v1.00
 * @author     John Neill <catzwolf@xoosla.com>
 * @copyright  : Copyright (C) 2010 Xoosla Modules. All rights reserved.
 * @license    : GNU/LGPL, see docs/license.php
 */

use Xmf\Request;

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * XooslaObject
 *
 * @package   Xoosla Core Module
 * @author    John Neill <catzwolf@xoosla.com>
 * @copyright Copyright (c) 2010
 * @version   $Id$
 * @access    public
 */
class XooslaController
{
    public $_model;
    public $_redirect;
    public $_message;
    public $_task;
    public $_module;

    public $handler;
    public $mHandler  = null;
    public $id        = array();
    public $cRedirect = 0;
    public $notifyType;
    public $value     = array();
    public $groups    = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_redirect = null;
        $this->_message  = null;
    }

    /**
     * XooslaController::execute()
     *
     */
    public function execute()
    {
        $this->_model  = XooslaLoad::getModel($this->_task, $this->_module);
        $this->_helper = XooslaLoad::getHelper($this->_task, '', $this->_module);
        $this->_view   = XooslaLoad::getView($this->_task, '', $this->_module);
        $this->_view->execute();
        $this->_view->setForm($this->_task);
        $this->setId();
        $this->setRedirect();
    }

    public function setId()
    {
        if (isset($_REQUEST[$this->_model->keyName])) {
            $this->id[] = XooslaRequest::doRequest($_REQUEST, 0, $_REQUEST[$this->_model->keyName], 'int');
        } elseif (isset($_REQUEST['checkbox'])) {
            $this->id = XooslaRequest::doRequest($_REQUEST['checkbox'], 0, 'checkbox', 'array');
        } else {
            $this->id = 0;
        }
    }

    /**
     * XooslaObjectCallback::setRedirect()
     *
     * @param string $value
     */
    public function setRedirect($value = '')
    {
        $this->cRedirect = xoops_getenv('PHP_SELF');
    }

    /**
     * XooslaObjectCallback::setNotificationType()
     *
     * @param string $value
     * @internal param string $type
     */
    public function setNotificationType($value = '')
    {
        $this->notifyType = $value;
    }

    /**
     * XooslaController::setTask()
     *
     * @param $params
     */
    public function setTask($params)
    {
        $this->_task = $params;
    }

    /**
     * XooslaController::setModule()
     *
     * @param $params
     */
    public function setModule($params)
    {
        $this->_module = $params;
    }

    /**
     * XooslaObjectCallback::help()
     *
     * @return
     */
    // function help() {
    // $help = XooslaLoad::getClass( 'help' );
    // $this->_view->setTemplate( 'wfx_candycontent.html' );
    // if ($help) { ;
    // $this->_view->setContent( $help->render() );
    // }
    // $this->_view->display();
    // }
    /**
     * XooslaObjectCallback::about()
     *
     */

    public function about()
    {
        $about = XooslaLoad::getClass('about');
        $this->_view->setTemplate('xoosla_content.tpl');
        $this->_view->addBreadcrumb(_XL_AD_XCA_MAINAREA, $_SERVER['PHP_SELF']);
        $this->_view->addBreadcrumb(_XL_AD_ADM_ICONABOUT);
        if ($about) {
            $this->_view->setContent($about->render());
        }
        $this->_view->display();
    }

    /**
     * XooslaController::getIds()
     *
     */
    public function getIds()
    {
        $this->id = XooslaRequest::doRequest($_REQUEST['checkbox'], 0, 'checkbox', 'array');
    }

    /**
     * XooslaController::create()
     *
     */
    public function create()
    {
        $this->edit();
    }

    /**
     * XooslaObjectCallback::edit()
     *
     * @internal param mixed $var
     */
    public function edit()
    {
        if (isset($this->id[0]) && $this->id[0] > 0) {
            $obj = $this->_model->get($this->id[0]);
        } else {
            $obj = $this->_model->create();
        }
        if (is_object($obj)) {
            $ret = $obj->formEdit($this->_view->formName);
            if (is_object($ret)) {
                $this->_view->setContent($ret->render());
                $this->_view->display();
            }
        }
    }

    /**
     * XooslaController::apply()
     *
     */
    public function apply()
    {
        $this->save();
    }

    /**
     * XooslaObjectCallback::save()
     *
     */
    public function save()
    {
        xoosla_securityCheck();
        $this->id = XooslaRequest::doRequest($_REQUEST, $this->_model->keyName, 0, 'int');
        if ($this->id > 0) {
            $obj = $this->_model->get($this->id);
        } else {
            $obj = $this->_model->create();
        }
        $class_vars = $obj->getVars();
        foreach (array_keys($class_vars) as $key) {
            $obj->setVar($key, isset($_REQUEST[$key]) ? $_REQUEST[$key] : false);
        }
        if (is_object($obj)) {
            $obj->setVars($_REQUEST);
            if ($this->_model->insert($obj, false)) {
                $this->setPermissions($obj->getVar($this->_model->keyName));
                $this->setNotifications($obj);
                $this->setTags($obj);
            }
        }
        switch ($_REQUEST['op']) {
            case 'apply':
                $this->cRedirect = xoops_getenv('PHP_SELF') . "?op=edit&amp;{$this->_model->keyName}=" . $obj->getVar($this->_model->keyName);
                break;
            default:
                break;
        } // switch
        redirect_header($this->cRedirect, 1, ($obj->isNew() ? _XL_AD_ADM_DBCTREATED : _XL_AD_ADM_DBUPDATED));
    }

    /**
     * XooslaController::deleteall()
     *
     * @param array $options
     */
    public function delete($options = array())
    {
        xoosla_securityCheck();
        if (count($this->id) > 0) {
            foreach ($this->id as $id) {
                $obj = $this->_model->get($id);
                if (is_object($obj)) {
                    if ($this->_model->delete($obj, false)) {
                        // $this->deletePermissions( $obj->getVar( $this->_model->keyName ) );
                        $this->deleteComments($GLOBALS['xoopsModule']->getVar('mid'), $obj->getVar($this->_model->keyName));
                    }
                }
            }
        }
        redirect_header($this->cRedirect, 1, (count($this->id) > 0) ? _XL_AD_ADM_DBITEMSDELETED : _XL_AD_ADM_DBNOTUPDATED);
    }

    /**
     * XooslaController::duplicateAll()
     *
     * @param array $options
     */
    public function duplicate($options = array())
    {
        xoosla_securityCheck();
        $this->id = array_reverse($this->id);
        if (count($this->id) > 0) {
            foreach ($this->id as $old_id) {
                $obj = $this->_model->get($old_id);
                if (is_object($obj)) {
                    $objClone = $obj->xoopsClone();
                    if ($this->_model->insert($objClone, false, null, true)) {
                        $new_id = $objClone->getVar($this->_model->keyName);
                        // $this->setNotifications( $newObj );
                        $this->clonePermissions($old_id, $new_id);
                    }
                }
            }
        }
        redirect_header($this->cRedirect, 1, (count($this->id) > 0) ? _XL_AD_ADM_DBITEMSDUPLICATED : _XL_AD_ADM_DBNOTUPDATED);
    }

    /**
     * XooslaController::updateall()
     *
     * @internal param array $options
     */
    public function update()
    {
        xoosla_securityCheck();
        // These are the checkboxes
        $keys = $this->unsetRequest();
        /**
         */
        if (count($this->id) > 0) {
            foreach ($this->id as $id) {
                $obj = $this->_model->get($id);
                /**
                 */
                if (is_object($obj)) {
                    $obj->_isDirty = true;
                    $keys          = array_intersect($keys, array_keys($obj->getVars()));
                    foreach ($keys as $key) {
                        if (isset($_REQUEST[$key][$id])) {
                            $obj->setVar($key, $_REQUEST[$key][$id]);
                        }
                    }
                    $this->_model->insert($obj, true, null, true);
                }
            }
        }
        redirect_header($this->cRedirect, 1, (count($this->id) > 0) ? _XL_AD_ADM_DBSELECTEDITEMSUPTATED : _XL_AD_ADM_DBNOTUPDATED);
    }

    /**
     * XooslaController::unsetRequest()
     * @return array
     */
    public function unsetRequest()
    {
        $array = array(
            'checkbox',
            'search',
            'andor',
            'limit',
            'checkall',
            'op',
            'start',
            'boxchecked',
            'XOLOGGERVIEW',
            'PHPSESSID',
            'xoops_user'
        );
        foreach ($array as $value) {
            unset($_REQUEST[$value]);
        }

        return array_keys($_REQUEST);
    }

    /**
     * XooslaObjectCallback::setPermissions()
     *
     * @param $id
     */
    public function setPermissions($id)
    {
        $groupids = XooslaRequest::doRequest($_REQUEST[$this->_model->groupName], $this->_model->groupName, false, 'array');
        if ($groupids !== false) {
            xoosla_setPerms($this->_model, $groupids, $id);
        }
    }

    /**
     * XooslaObjectCallback::delelePermissions()
     *
     * @param $id
     * @internal param mixed $keyName
     */
    public function deletePermissions($id)
    {
        xoosla_deletePerms($this->_model, $id);
    }

    /**
     * XooslaObjectCallback::clonePermissions()
     *
     * @param $old_id
     * @param $new_id
     * @internal param mixed $id
     * @internal param mixed $keyName
     */
    public function clonePermissions($old_id, $new_id)
    {
        xoosla_clonePerms($this->_model, $old_id, $new_id);
    }

    /**
     * XooslaObjectCallback::setNotifications()
     *
     * @param mixed $obj
     */
    public function setNotifications(&$obj)
    {
        if (isset($GLOBALS['xoopsModuleConfig']['notification_enabled'])
            && $GLOBALS['xoopsModuleConfig']['notification_enabled'] > 0) {
            if (method_exists($this->_model, 'upDateNotification')) {
                if (!empty($this->notifyType)) {
                    $this->_model->upDateNotification($obj, $this->notifyType);
                }
            }
        }
    }

    /**
     * XooslaObjectCallback::setTags()
     *
     * @param mixed $obj
     */
    public function setTags(&$obj)
    {
        if (xoosla_isModInstalled('tag')) {
            if (method_exists($this->_model, 'upTagHandler')) {
                $this->_model->upTagHandler($obj);
            }
        }
    }

    /**
     * XooslaObjectCallback::deleteComments()
     *
     * @param $mid
     * @param $id
     */
    public function deleteComments($mid, $id)
    {
        xoops_comment_delete($mid, $id);
    }
}
