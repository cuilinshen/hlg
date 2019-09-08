<?php

/**
 * @version			$Id$
 * @create 			2017-10-22 14:10:30 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.mainorderpopo, app.admin.action.AdminAction, model.mainordermodel');

/**
 * 主订单表的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class MainorderAction extends AdminAction
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
        $this->_popo        = new MainorderPopo();
        $this->_model       = new MainorderModel($this->_popo);
        $this->_popo->setFieldAttribute('id', 'is_order', 'DESC');
    }

}

?>
