<?php

/**
 * @version $Id$
 * @create 2014/3/24 21:40:39 By luodao
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('config.popo.commentpopo, model.commentmodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 评论的动作类
 * 
 * 主要处理联系方式的相关请求动作 
 * 
 * @author luodao <luodao@foxmail.com>
 * @package None
 * @since 1.0.0
 */
class CommentAction extends HhnshAction
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
        $this->_popo    = new CommentPopo();
        $this->_model   = new CommentModel($this->_popo);
        $this->_user    = HClass::quickLoadModel('user');
    }

    /**
     * 用户对商户的评价列表
     * @return [type] [description]
     */
    public function shopcomment()
    {
        $userId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'OpenID');
        $this->_verifyUserOpenId($openId, $userId);
        $where  = '`type` = 1 AND `status` = 1 AND `parent_id` = ' . $userId;
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $prepage= HRequest::getParameter('prepage') ? HRequest::getParameter('prepage') : 10;
        $list   = $this->_model->getListByWhere($where, $page - 1, $prepage); 
        $shopMap = HArray::turnItemValueAsKey(HClass::quickLoadModel('company')->getAllRowsByFields(
            '`id`, `name`, `image_path`, `address`',
            HSqlHelper::whereInByListMap('id', 'product_id', $list)
        ), 'id');
        foreach($list as $key => &$item) {
            $item['name']       = HString::cutString($item['name'],10 ,'...');
            $item['create_time'] = HString::formatTime($item['create_time']);
            $company = $shopMap[$item['product_id']];
            $company['image_path'] = HString::formatImage($company['image_path']);
            $item['company']    = $company;
            $item['replylist']  = $this->_getReplyListById($item['id']);
        }

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 得到评论列表
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function _getReplyListById($id)
    {
        $reply = HClass::quickLoadModel('reply');
        $where = '`user_id` = ' . HRequest::getParameter('user_id') . ' AND `item_id` = ' . $id . ' AND `status` = 1';
        $list = $reply->getAllRowsByFields('*', $where);
        foreach($list as $key => &$item) {
            $item['create_time'] = HString::formatTime($item['create_time']);
        }

        return $list;

    }

    /**
     * 用户对跑男的评价列表
     * @return [type] [description]
     */
    public function qishoucomment()
    {
        $where = '`type` = 2 AND `status` = 1';


    }


    /**
     * 验证记录数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _verifyAddData($record)
    {   
        $data   = array();
        $userId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'OpenID');
        $this->_verifyUserOpenId($openId, $userId);
        $data['parent_id'] = $userId;
        $data['name'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '用户名');
        $data['content'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('content'), '评论内容');
        $data['product_id'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('shop_id'), '所属店铺');
        $data['order_id'] = 0;
        $data['total_score'] = HRequest::getParameter('total_score'); 
        $data['type']    = 1;

        return $data;
    }

    /**
     * 更新完成后其他数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function _updateFinishedData($id)
    {
        $record = $this->_model->getRecordById($id);
        $sum    = $this->_model->getSum('total_score', '`product_id` = ' . $record['product_id']);
        $total  = $this->_model->getTotalRecords('`product_id` = ' . $record['product_id']);
        $avg    = round($sum/$total, 1);
        
        if(1 > HClass::quickLoadModel('company')->editByWhere(array('total_score' => $avg), '`id` = ' . $record['product_id'])) {
            HResponse::json(array('rs' => false, 'message' => '更新总评分失败'));
        }
    }

}
?>
