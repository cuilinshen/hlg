<?php

/**
 * @version			$Id$
 * @create 			2017-10-16 14:10:38 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.qishoumoneypopo, app.admin.action.AdminAction, model.qishoumoneymodel');

/**
 * 骑手收入明细的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class QishoumoneyAction extends AdminAction
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
        $this->_popo        = new QishoumoneyPopo();
        $this->_model       = new QishoumoneyModel($this->_popo);
    }

}

?>
