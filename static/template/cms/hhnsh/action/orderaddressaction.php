<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.orderaddresspopo, model.orderaddressmodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 用户地址的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class OrderaddressAction extends HhnshAction 
{

    /**
     * 构造函数 
     * 
     * 初始化类里的变量 
     * 
     * @access public
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_popo        = new OrderaddressPopo();
        $this->_model       = new OrderaddressModel($this->_popo);
        $this->_user        = HClass::quickLoadModel('user');
    }

    /**
     * 用户地址列表
     * @return [type] [description]
     */
    public function index()
    {
        $this->_popo->setFieldAttribute('id', 'is_order', null);
        $this->_popo->setFieldAttribute('status', 'is_order', 'desc');
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $where      = '`parent_id` = ' . $userId;
        $list       = $this->_model->getAllRows($where);

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 重写添加数据验证
     * @return [type] [description]
     */
    protected function _verifyAddData()
    {
        $data   = array();
        $data['name']       = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '用户名');
        $smsSend = $this->_verifyVcode();
        $data['phone']      = $this->_verifyJSONIsEmpty(HRequest::getParameter('phone'), '手机号码');
        $data['parent_id']  = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '所属用户');
        $data['sex']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('sex'), '性别');
        $data['status']     = 2;
        $this->_verifyUserOpenId(HRequest::getParameter('open_id'), HRequest::getParameter('user_id'));
        if(HRequest::getParameter('id')) {
            $record = $this->_model->getRecordById(HRequest::getParameter('id'));
            if($record['parent_id'] != $data['parent_id']) {
                HResponse::json(array('rs' => false, 'message' => '对不起您权限不够'));
                return;
            }
        }

        return $data;
    }

    /**
     * 验证短信验证码
     * @return [type] [description]
     */
    private function _verifyVcode()
    {   
        $vcode  = $this->_verifyJSONIsEmpty(HRequest::getParameter('vcode'), '验证码');
        $where = '`open_id` = \'' . HRequest::getParameter('open_id') . '\' AND `vcode` = \'' . $vcode . '\'';
        $record = HClass::quickLoadModel('smstotal')->getRecordByWhere($where);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '验证码错误'));
        }
        $smsSend = HClass::quickLoadModel('smssend')->getRecordByWhere('`id` = ' . $record['sms_send_id']);
        if($smsSend['vcode'] != $vcode || $smsSend['is_express'] > 1) {
            HResponse::json(array('rs' => false, 'message' => '验证码过期'));   
        }

        return $smsSend;
    }

    /**
     * 更新添加/修改完成后的数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function _updateFinishedData($id)
    {
        $where = '`open_id` = \'' . HRequest::getParameter('open_id') . '\' AND `vcode` = \'' . HRequest::getParameter('vcode') . '\'';
        if(1 > HClass::quickLoadModel('smssend')->editByWhere(array('is_express' => 2), $where)) {
            HResponse::json(array('rs' => false, 'message' => '验证码操作失败'));   
        }
    }

    /**
     * 验证删除数据权限
     * @return [type] [description]
     */
    protected function _verifyDelData($record)
    {
        if($record['parent_id'] != HRequest::getParameter('user_id')) {
            HResponse::json(array('rs' => false, 'message' => '对不起您权限不够'));
            return;
        }
    }

    /**
     * 验证记录数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _verifyRecordData($record)
    {   
        if($record['parent_id'] != HRequest::getParameter('user_id')) {
            HResponse::json(array('rs' => false, 'message' => '地址数据权限不够'));
        }   
    }

    /**
     * 得到默认的地址
     * @return [type] [description]
     */
    public function getdefaultaddress()
    {
        $userid     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $this->_verifyUserOpenId(HRequest::getParameter('open_id'), HRequest::getParameter('user_id'));
        $record = $this->_model->getRecordByWhere('`parent_id` = ' . $userid . ' AND `status` =2');
        
        HResponse::json(array('rs' => true, 'data' => $record));
    }

      /**
     * 加载额外的记录数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _assignRecordOtherInfo($record)
    {
        return $record;   
    }


}