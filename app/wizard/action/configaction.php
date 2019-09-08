<?php

/**
 * @version			$Id$
 * @create 			2013-08-13 15:08:39 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.configpopo, app.admin.action.AdminAction, model.configmodel');

/**
 * 网站配置的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since 			1.0.0
 */
class ConfigAction extends AdminAction
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
        $this->_popo        = new ConfigPopo();
        $this->_model       = new ConfigModel($this->_popo);
    }

    /**
     * 添加视图
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function addview()
    {
        $this->_addview();
        $this->_assignLangTypeList();

        $this->_render('config/info');
    }

    /**
     * 编辑视图
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function editview()
    {
        $this->_editview();
        $this->_assignLangTypeList();

        $this->_render('config/info');
    }

}

?>
