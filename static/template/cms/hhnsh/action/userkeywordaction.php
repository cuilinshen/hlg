<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.userkeywordpopo, model.userkeywordmodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 产品的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class UserkeywordAction extends HhnshAction 
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
        $this->_popo        = new UserkeywordPopo();
        $this->_model       = new UserkeywordModel($this->_popo);
    }

    /**
     * 得到用户的关键词列表
     * @return [type] [description]
     */
    public function index()
    {
        $userId = HRequest::getParameter('user_id');
        $this->_verifyJSONIsEmpty($userId, '用户ID');
        $list   = $this->_model->getListByAll('`parent_id` = ' . $userId, '`id`, `name`', '`id` DESC', $page = 0, $perpage = 8, 'name');

        HResponse::json(array('rs' => true, 'data' => $list));
    }

     /**
     * 验证添加/修改数据
     * @return [type] [description]
     */
    protected function _verifyAddData()  
    {
        $data['name']       = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '关键词');
        $data['parent_id']  = $this->_verifyJSONIsEmpty(HRequest::getParameter('parent_id'), '所属用户');
        $data['city']       = HRequest::getParameter('city');
        $data['from']       = HRequest::getParameter('from');
        $data['rel_id']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('rel_id'), '型号');
        
        return $data;

    }

}