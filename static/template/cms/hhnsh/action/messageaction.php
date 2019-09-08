<?php

/**
 * @version $Id$
 * @create 2014/3/24 21:40:39 By luodao
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('config.popo.messagepopo, model.messagemodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 留言的动作类
 * 
 * 主要处理联系方式的相关请求动作 
 * 
 * @author luodao <luodao@foxmail.com>
 * @package None
 * @since 1.0.0
 */
class MessageAction extends HhnshAction
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
        $this->_popo    = new MessagePopo();
        $this->_model   = new MessageModel($this->_popo);
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
     * 得到消息列表
     * @return [type] [description]
     */
    public function index()
    {   
        $id  = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '产品ID');
        $where   = '`product_id` = ' . $id;
        $list    = $this->_model->getAllRows($where);
        $reply   = HClass::quickLoadModel('reply');
        foreach($list as $key => &$item) {
            $item['create_time'] = HString::formatTime($item['create_time']);
            $replyList = $reply->getAllRows('`item_id` = ' . $item['id']);
            $item['replyList'] = $replyList;
        }
        
        HResponse::json(array('rs' => true, 'data' => $list));
    }  
        
    /**
     * 验证添加/修改数据
     * @return [type] [description]
     */
    protected function _verifyAddData()  
    {
        $userId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'OpenID');
        $userInfo= $this->_verifyUserOpenId($openId, $userId);
        $name    = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '昵称');
        $productId    = $this->_verifyJSONIsEmpty(HRequest::getParameter('product_id'), '所属产品');
        $title   = $this->_verifyJSONIsEmpty(HRequest::getParameter('title'), '咨询问题');

        return array(
            'parent_id' =>  $userId,
            'name' => $name,
            'image_path' => $userInfo['image_path'],
            'product_id' => $productId,
            'title' => $title,
            'author' => $userId
        );
    }

}
?>
