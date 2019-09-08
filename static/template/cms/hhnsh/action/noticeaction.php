<?php

/**
 * @version			$Id$
 * @create 			2013-06-17 01:06:41 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.noticepopo, model.noticemodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 消息的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.site.action
 * @since 			1.0.0
 */
class NoticeAction extends HhnshAction
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
        $this->_popo    = new NoticePopo();
        $this->_model   = new NoticeModel($this->_popo);
        $this->_user    = HClass::quickLoadModel('user');
    }

    /**
     * 得到最新的消息列表
     * @return [type] [description]
     */
    public function index()
    {
        $data   = array();
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $this->_verifyUserOpenId($openId, $userId);
        $where  = '`parent_id` = ' . $userId;
        $data['orders']     = $this->_getNoticeList($where . ' AND `type` = 2', 0);
        $data['system']     = $this->_getNoticeList($where . ' AND `type` = 1', 0);
        $data['wuliu']      = $this->_getNoticeList($where . ' AND `type` = 3', 0);
        
        HResponse::json(array('rs' => true, 'data' => $data));
    }

    /**
     * 得到列表数据
     * @return [type] [description]
     */
    public function agetlist()
    {
        $page   = HRequest::getParameter('page');
        $page   = $page ? $page : 0;
        $type   = HRequest::getParameter('type');
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $this->_verifyUserOpenId($openId, $userId);
        $where  = '`parent_id` = ' . $userId . ' AND `type` = ' . $type;
        $list   = $this->_getNoticeList($where, $page);

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 得到消息列表
     * @param  [type] $where [description]
     * @param  [type] $page  [description]
     * @return [type]        [description]
     */
    private function _getNoticeList($where, $page)
    {   
        $list = $this->_model->getListByWhere($where, $page, 5);

        return $list;
    }

    
    
}