<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.shopsumpopo, model.shopsummodel');
HClass::import(HResponse::getCurThemePath() . '.action.ShopbaseAction');

/**
 * 公司的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class ShopsumAction extends ShopbaseAction 
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
        $this->_popo        = new ShopsumPopo();
        $this->_model       = new ShopsumModel($this->_popo);
    }

    /**
     * 分类列表
     * @return [type] [description]
     */
    public function index()
    {
    	$this->_verifyShopRight();
        $this->_popo->setFieldAttribute('id', 'is_order', null);
        $this->_popo->setFieldAttribute('cur_date', 'is_order', desc);
    	$where = '`parent_id` = ' . HRequest::getParameter('shop_id');	
        $page  = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $prepage = HRequest::getParameter('prepage') ? HRequest::getParameter('prepage') : 10;
    	$list  = $this->_model->getListByWhere($where, $page - 1, $prepage);

    	HResponse::json(array('rs' => true, 'data' => $list));
    }

}