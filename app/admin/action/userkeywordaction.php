<?php

/**
 * @version			$Id$
 * @create 			2018-08-02 18:08:51 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.userkeywordpopo, app.admin.action.AdminAction, model.userkeywordmodel');

/**
 * 用户搜索关键词的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class UserkeywordAction extends AdminAction
{

    /**
     * 构造函数 
     * 
     * 初始化类变量 
     * 
     * @access public
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_popo        = new UserkeywordPopo();
        $this->_model       = new UserkeywordModel($this->_popo);
    }

}

?>
