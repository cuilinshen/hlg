<?php

/**
 * @version			$Id$
 * @create 			2018-04-23 12:04:16 By luodao
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.goodscategorypopo, app.admin.action.AdminAction, model.goodscategorymodel');

/**
 * 商品分类的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			luodao <luodao@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class GoodscategoryAction extends AdminAction
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
        $this->_popo        = new GoodscategoryPopo();
        $this->_model       = new GoodscategoryModel($this->_popo);
    }

    /**
     * 列表后驱方法
    */
    public function _otherJobsAfterList()
    {
        parent::_otherJobsAfterList();
        $list   = HResponse::getAttribute('list');
        HResponse::registerFormatMap(
            'parent_id',
            'name',
            HArray::turnItemValueAsKey($this->_getShopList(), 'id')
        );
    }

     /**
     * 添加视图后驱
     * 
     * @author luodao <luodao@foxmail.com>
     * @access protected
     */
    protected function _otherJobsAfterAddView() 
    { 
        parent::_otherJobsAfterAddView();
        HResponse::setAttribute('parent_id_list', $this->_getShopList());
    }

    /**
     * 视频详细页后驱
     * 
     * @author luodao <luodao@foxmail.com>
     * @access protected
     */
    protected function _otherJobsAfterEditView($record = null) 
    { 
        parent::_otherJobsAfterEditView();

        HResponse::setAttribute('parent_id_list', $this->_getShopList());
    }

    /**
     * 得到商户列表
     * @return [type] [description]
     */
    private function _getShopList($list)
    {   
        $where = '1=1';
        if($list) {
            $where = HSqlHelper::whereInByListMap('id', 'parent_id', $list);
        }
        $list = HClass::quickLoadModel('company')->getAllRows($where);

        return $list;
    }

}

?>
