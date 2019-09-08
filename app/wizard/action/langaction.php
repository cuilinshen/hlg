<?php

/**
 * @version			$Id$
 * @create 			2013-08-08 12:08:18 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.langpopo, app.admin.action.AdminAction, model.langmodel');

/**
 * 语言的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since 			1.0.0
 */
class LangAction extends AdminAction
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
        $this->_popo        = new LangPopo();
        $this->_model       = new LangModel($this->_popo);
    }

    /**
     * 加载语言标识列表
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _registerFormatMap()
    {
        parent::_registerFormatMap();

        //注册地址格式化
        HResponse::registerFormatMap(
            'mask_id',
            'name',
            HArray::turnItemValueAsKey(
                $this->_getRelationModelList(
                    'langmask',
                    'mask_id',
                    HResponse::getAttribute('list')
                ), 
                'id'
            )
        );
    }

}

?>
