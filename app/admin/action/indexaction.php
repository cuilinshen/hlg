<?php

/**
 * @version			$Id$
 * @create 			2012-4-8 8:30:17 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('app.admin.action.AdminAction');

/**
 * 管理主页的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class IndexAction extends AdminAction
{
     /**
     * @var 订单对象
     */
    private $_order;

    /**
     * 公司对象
     * @var [type]
     */
    private $_company;

    /**
     * 用户对象
     * @var [type]
     */
    private $_user;

    /**
     * 应用对象
     * @var [type]
     */
    private $_info;

    /**
     * @var 商品对象
     */
    private $_product;

    /**
     * 构造函数
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->_order   = HClass::quickLoadModel('order');
        $this->_product = HClass::quickLoadModel('product');
        $this->_company = HClass::quickLoadModel('company');
        $this->_user    = HClass::quickLoadModel('user');
        $this->_info    = HClass::quickLoadModel('information');
    }

    /**
     * 主页动作 
     * 
     * @desc
     * 
     * @access public
     */
    public function index()
    {
        $this->_assignModelManagerList();
        $this->_assignModelTypeList();
        $this->_assignLangList();
        $this->_assignDaiShenHeShop();
        $this->_assignShops();
        $this->_assignUsers();
        $this->_assignVisits();
        /**
        $this->_assignOrderShopJieDan();
        $this->_assignOrderWaitPay();
        $this->_assignOrderYesterday();
        $this->_assignGoodsCount();
        $this->_assignOrderTrade();
        $this->_assignOrderQiShouJieDan();
        **/
        $this->_render('index');
    }

    /**
     * 加载模块管理所有列表
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _assignModelManagerList()
    {
        $mManager    = HClass::quickLoadModel('ModelManager');

        HResponse::setAttribute('list', $mManager->getAllRows());
    }

    /**
     * 加载模块类型列表
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _assignModelTypeList()
    {
        if(!HResponse::getAttribute('list')) {
            return;
        }
        $category   = HClass::quickLoadModel('category');
        HResponse::setAttribute(
            'catModelList', 
            $category->getSubCategoryByIdentifier('model-category', false)
        );
    }

     /**
     * 待审核商户总数
     */
    private function _assignOrderShopJieDan()
    {
        $where      = '`status` = 2';
        $count      = $this->_order->getTotalRecords($where);

        HResponse::setAttribute('orderShopJieDan', $count);
    }

    /**
     * 待骑手接单订单总数
     * @return [type] [description]
     */
    private function _assignOrderQiShouJieDan()
    {
        $where      = '`status` = 3';
        $count      = $this->_order->getTotalRecords($where);

        HResponse::setAttribute('orderQiShouJieDan', $count);   
    }

    /**
     * 待支付订单总数
     */
    private function _assignOrderWaitPay()
    {
        $where      = '`status` = 1';
        $count      = $this->_order->getTotalRecords($where);

        HResponse::setAttribute('orderWaitPay', $count);
    }

    /**
     * 昨日订单
     */
    private function _assignOrderYesterday()
    {
        $where      = '(TO_DAYS(NOW()) - TO_DAYS(`create_time`) = 1) AND (`status` > 2 AND `status` != 8 AND `status` != 12)';
        $count      = $this->_order->getTotalRecords($where);

        HResponse::setAttribute('orderYesterday', $count);
    }

    /**
     * 昨日交易额
     */
    private function _assignOrderTrade()
    {
        $where      = '(TO_DAYS(NOW()) - TO_DAYS(`create_time`) = 1) AND (`status` > 2 AND `status` != 8 AND `status` != 12)';
        $total      = $this->_order->getSum('total_money', $where);

        HResponse::setAttribute('orderTrade', $total);
    }

    /**
     * 加载已上架商品总数
     */
    private function _assignGoodsCount()
    {
        $where      = '`status` = 1';
        $count      = $this->_product->getTotalRecords($where);

        HResponse::setAttribute('goodsCount', $count);
    }

    /**
     *  加载进行中的促销活动
     */
    private function _assignActivity()
    {
        $activity   = HClass::quickLoadModel('activity');
        $where      = '`status` = 2 AND `start_time` <= ' . time() . ' AND `end_time` >= ' . time();
        $count      = $activity->getTotalRecords($where);

        HResponse::setAttribute('activityCount', $count);
    }

    /**
     * 加载待审核店铺
     * @return [type] [description]
     */
    private function _assignDaiShenHeShop()
    {
        HResponse::setAttribute('applyShops', $this->_company->getTotalRecords('`status` = 1'));
    }

    /**
     * 加载店铺总数
     * @return [type] [description]
     */
    private function _assignShops()
    {
        HResponse::setAttribute('allShops', $this->_company->getTotalRecords('1=1'));
    }

    /**
     * 加载会员数
     * @return [type] [description]
     */
    private function _assignUsers()
    {   
        HResponse::setAttribute('users', $this->_user->getTotalRecords('`parent_id` = 2 OR `parent_id` = 3'));
    }

    /**
     * 加载总访问量
     * @return [type] [description]
     */
    private function _assignVisits()
    {
        HResponse::setAttribute('infoRecord', $this->_info->getRecordById(1));
    }

}

?>
