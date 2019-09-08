<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.advpopo, model.advmodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 广告的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class AdvAction extends HhnshAction 
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
        $this->_popo        = new AdvPopo();
        $this->_model       = new AdvModel($this->_popo);
    }

    /**
     * 列表
     * @return [type] [description]
     */
    public function _list()
    {
        $identifier     = HRequest::getParameter('identifier');
        $this->_verifyJSONIsEmpty($identifier, '标识');
        $record         = $this->_category->getRecordByIdentifier($identifier);
        $where = '`parent_id` = ' . $record['id'];
        parent::_list($where, 5);
        $list   = HResponse::getAttribute('list');
        $list   = array_column($list, 'image_path');

        HResponse::json(array('rs' => true, 'data' => $list));
    }

}