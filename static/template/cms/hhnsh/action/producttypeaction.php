<?php

/**
 * @version $Id$
 * @create 2014/3/24 21:40:39 By luodao
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('config.popo.producttypepopo, model.producttypemodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 活动类型的动作类
 * 
 * 主要处理联系方式的相关请求动作 
 * 
 * @author luodao <luodao@foxmail.com>
 * @package None
 * @since 1.0.0
 */
class ProducttypeAction extends HhnshAction
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
        $this->_popo    = new ProducttypePopo();
        $this->_model   = new ProducttypeModel($this->_popo);
        $this->_user    = HClass::quickLoadModel('user');
    }

     /**
     * 验证添加的数据
     * @return [type] [description]
     */
    protected function _verifyAddData()
    {
        $data       = array();
        $this->_user         = HClass::quickLoadModel('user');
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户id');
        $userInfo = $this->_verifyUserOpenId(HRequest::getParameter('open_id'), $userId);
        $data['name']        = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '活动类型名称');
        $data['total_number']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('total_number'), '满额人数');
        $data['parent_id']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('parent_id'), '所属活动');
        $data['huodong_date']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('huodong_date'), '活动时间');
        $data['price']      = HRequest::getParameter('price');
        $data['remark']= HRequest::getParameter('remark');
        $data['type']    = $this->_verifyJSONIsEmpty(HRequest::getParameter('type'), '收费类型');

        return $data;
    }

}
?>
