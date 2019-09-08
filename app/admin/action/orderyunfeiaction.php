<?php

/**
 * @version			$Id$
 * @create 			2017-09-30 16:09:03 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.orderyunfeipopo, app.admin.action.AdminAction, model.orderyunfeimodel');

/**
 * 订单骑手运费表的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class OrderyunfeiAction extends AdminAction
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
        $this->_popo        = new OrderyunfeiPopo();
        $this->_model       = new OrderyunfeiModel($this->_popo);
    }

}

?>
