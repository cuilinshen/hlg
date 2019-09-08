<?php

/**
 * @version			$Id$
 * @create 			2018-03-26 21:03:39 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.couponpopo, app.admin.action.AdminAction, model.couponmodel');

/**
 * 优惠券的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class CouponAction extends AdminAction
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
        $this->_popo        = new CouponPopo();
        $this->_model       = new CouponModel($this->_popo);
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
            HArray::turnItemValueAsKey(CouponPopo::$_statusMap, 'id')
        );  
        HResponse::registerFormatMap(
            'company_id',
            'name',
            HArray::turnItemValueAsKey($this->_getCompanyList($list), 'id')
        );  
        HResponse::registerFormatMap(
            'product_id',
            'name',
             HArray::turnItemValueAsKey($this->_getProductList($list), 'id')
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
        HResponse::setAttribute('company_id_list', $this->_getCompanyList());
        HResponse::setAttribute('status_list', CouponPopo::$_statusMap);
        HResponse::setAttribute('product_id_list', $this->_getProductList());
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

        HResponse::setAttribute('company_id_list', $this->_getCompanyList());
        HResponse::setAttribute('status_list', CouponPopo::$_statusMap);
        HResponse::setAttribute('product_id_list', $this->_getProductList());
    }

    /**
     * 得到店铺列表
     * @return [type] [description]
     */
    private function _getCompanyList($list)
    {
        $where = '1=1';
        if($list) {
            $where = HSqlHelper::whereInByListMap('id', 'company_id', $list);
        }
        $companyList =  HClass::quickLoadModel('company')->getAllRowsByFields('`id`, `name`', $where);
        
        return $companyList;
    }

    /**
     * 得到产品列表
     * @return [type] [description]
     */
    private function _getProductList($list)
    {
        $where = '1=1';
        if($list) {
            $where = HSqlHelper::whereInByListMap('id', 'product_id', $list);
        }
        $list   = HClass::quickLoadModel('product')->getAllRowsByFields('`id`, `name`', $where);

        return $list;
    }

}

?>
