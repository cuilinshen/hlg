<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.goodscategorypopo, model.goodscategorymodel');
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
class GoodscategoryAction extends ShopbaseAction 
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
        $this->_popo        = new GoodscategoryPopo();
        $this->_model       = new GoodscategoryModel($this->_popo);
    }

    /**
     * 分类列表
     * @return [type] [description]
     */
    public function index()
    {
    	$this->_verifyShopRight();
    	$where = '`parent_id` = ' . HRequest::getParameter('shop_id');	
    	$list  = $this->_model->getAllRows($where);

    	HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 验证添加的数据
     * @return [type] [description]
     */
    protected function _verifyAddData()
    {
    	$this->_verifyShopRight();
        $data       = array();
        $data['parent_id'] 	= HRequest::getParameter('shop_id');
        $data['author'] 	= HRequest::getParameter('user_id');
        $data['name'] 		= $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '分类名称');
        $data['sort_num'] 	= $this->_verifyJSONIsEmpty(HRequest::getParameter('sort_num'), '排序号');

        return $data;
    }

    
    
    
}