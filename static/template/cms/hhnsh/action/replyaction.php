<?php

/**
 * @version $Id$
 * @create 2014/3/24 21:40:39 By luodao
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('config.popo.replypopo, model.replymodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 回复的动作类
 * 
 * 主要处理联系方式的相关请求动作 
 * 
 * @author luodao <luodao@foxmail.com>
 * @package None
 * @since 1.0.0
 */
class ReplyAction extends HhnshAction
{

    /**
     * 构造方法
     * 
     * 初始化类里面的变量
     * 
     * @author luodao <luodao@foxmail.com>
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->_popo    = new ReplyPopo();
        $this->_model   = new ReplyModel($this->_popo);
        $this->_user    = HClass::quickLoadModel('user');
    }

    /**
     * 得到消息总数
     * @return [type] [description]
     */
    public function agetsum()
    {
        $userId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'OpenID');
        $this->_verifyUserOpenId($openId, $userId);
        $where  = '`parent_id` = ' . $userId . ' AND `status` = 1';
        $total  = $this->_model->getSum('id', $where);

        HResponse::json(array('rs' => true, 'data' => $total));
    }

    /**
     * 得到回复列表
     * @return [type] [description]
     */
    public function index()
    {   
        $userId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'OpenID');
        $this->_verifyUserOpenId($openId, $userId);
        $page    = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $where   = '`user_id` = ' . $userId;
        $list    = $this->_model->getListByWhere($where, $page - 1, 10);
        foreach($list as $key => &$item) {
            $item['create_time'] = HString::formatTime($item['create_time']);
        }
        
        HResponse::json(array('rs' => true, 'data' => $list));
    }  
    
}
?>
