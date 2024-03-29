<?php

/**
 * @version			$Id$
 * @create 			2013-08-08 12:08:50 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.langtypepopo, app.admin.action.AdminAction, model.langtypemodel');

/**
 * 语言种类的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since 			1.0.0
 */
class LangtypeAction extends AdminAction
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
        $this->_popo        = new LangtypePopo();
        $this->_model       = new LangtypeModel($this->_popo);
    }

}

?>
