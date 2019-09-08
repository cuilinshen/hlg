<?php

/**
 * @version			$Id$
 * @create 			2018-07-29 16:07:33 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.producttypepopo, app.admin.action.AdminAction, model.producttypemodel');

/**
 * 活动类型的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class ProducttypeAction extends AdminAction
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
        $this->_popo        = new ProducttypePopo();
        $this->_model       = new ProducttypeModel($this->_popo);
    }

     /**
     * 列表后驱方法
    */
    public function _otherJobsAfterList()
    {
        parent::_otherJobsAfterList();
        $list   = HResponse::getAttribute('list');
        HResponse::registerFormatMap(
            'status',
            'name',
            HArray::turnItemValueAsKey(ProducttypePopo::$_statusMap, 'id')
        );  
        HResponse::registerFormatMap(
            'type',
            'name',
            HArray::turnItemValueAsKey(ProducttypePopo::$_typeMap, 'id')
        );  
    }

     /**
     * 添加视图后驱
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _otherJobsAfterAddView() 
    { 
        parent::_otherJobsAfterAddView();
        HResponse::setAttribute('status_list', ProducttypePopo::$_statusMap);
        HResponse::setAttribute('type_list', ProducttypePopo::$_typeMap);
    }

    /**
     * 视频详细页后驱
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _otherJobsAfterEditView($record = null) 
    { 
        parent::_otherJobsAfterEditView();

        HResponse::setAttribute('status_list', ProducttypePopo::$_statusMap);        
        HResponse::setAttribute('type_list', ProducttypePopo::$_typeMap);
    }

}

?>
