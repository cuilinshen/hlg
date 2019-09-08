<?php

/**
 * @version			$Id$
 * @create 			2013-06-17 01:06:41 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.qishoupopo, model.qishoumodel');
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
class QishouAction extends HhnshAction
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
        $this->_popo    = new QishouPopo();
        $this->_model   = new QishouModel($this->_popo);
        $this->_user    = HClass::quickLoadModel('user');
    }

    /**
     * 骑手基本信息
     * @return [type] [description]
     */
    public function index()
    {
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $this->_verifyUserOpenId($openId, $userId);
        $record     = $this->_model->getRecordByWhere('`parent_id` = ' . $userId);
        $record['notices'] = $this->_getNewsNoticeNumber($userId);

        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 得到最新的消息数
     * @return [type] [description]
     */
    private function _getNewsNoticeNumber($userId)
    {
        return HClass::quickLoadModel('notice')->getTotalRecords('`parent_id` = ' . $userId . ' AND `status` = 1');
    }

}