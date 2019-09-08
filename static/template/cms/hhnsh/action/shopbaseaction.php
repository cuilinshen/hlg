<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.companypopo, model.companymodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 店铺管理的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class ShopbaseAction extends HhnshAction 
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
        $this->_popo        = new CompanyPopo();
        $this->_model       = new CompanyModel($this->_popo);
    }

    /**
     * 店铺管理中心
     * @return [type] [description]
     */
    public function index()
    {
        //验证商户权限
        $this->_verifyShopRight();
        $shopId     = HRequest::getParameter('shop_id');
        $companyInfo= $this->_model->getRecordByWhere('`id` = ' . $shopId);
        $data['companyInfo']    = $companyInfo;


        HResponse::json(array('rs' => true, 'data' => $data));
    }
    
    /**
     * 验证商户权限
     * @return [type] [description]
     */
    protected function _verifyShopRight()
    {
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户id');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $shopId = $this->_verifyJSONIsEmpty(HRequest::getParameter('shop_id'), 'shopId');
        $user   = HClass::quickLoadModel('user');
        $userInfo = $user->getRecordByWhere('`id` = ' . $userId . ' AND `open_id` = \'' . $openId . '\'');
        if(!$userInfo || $userInfo['parent_id'] != 3) {
            HResponse::json(array('rs' => false, 'message' => '商户权限不足'));
        }
    }




}