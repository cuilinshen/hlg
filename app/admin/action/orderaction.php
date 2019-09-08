<?php

/**
 * @version			$Id$
 * @create 			2017-08-31 00:08:39 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.orderpopo, app.admin.action.AdminAction, model.ordermodel');

/**
 * 订单的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class OrderAction extends AdminAction
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
        $this->_popo        = new OrderPopo();
        $this->_model       = new OrderModel($this->_popo);
    }

    /**
     * 主页动作 
     * 
     * @access public
     */
    public function index()
    {        
        $this->_search();

        $this->_render('order/list');
    }

    /**
     * 搜索方法 
     * 
     * @access public
     */
    public function search()
    {
        $this->_search($this->_combineWhere());

        $this->_render('order/list');
    }

     /**
     * 组合搜索条件
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @return String 组合成的搜索条件
     */
    protected function _combineWhere()
    {
        $where      = $this->_getDateWhere();
        if(1 < intval(HRequest::getParameter('type'))) {
            array_push($where, $this->_getParentWhere(HRequest::getParameter('type')));
        }
        $keyword    = HRequest::getParameter('keywords');
        if($keyword && '关键字...' !== $keyword) {
            array_push($where, $this->_getSearchWhere($keyword));
        }
        if(HRequest::getParameter('pay_start_date')) {
            array_push($where, '`pay_time` >= \'' . strtotime(HRequest::getParameter('pay_start_date')) . '\'');
        }
        if(HRequest::getParameter('pay_end_date')) {
            array_push($where, '`pay_time` <= \'' . strtotime(HRequest::getParameter('pay_end_date')) . '\'');
        }
        if(HRequest::getParameter('status')) {
            array_push($where, '`status` = ' . HRequest::getParameter('status') . '');
        }

        return !$where ? null : implode(' AND ', $where);
    }

     /**
     * 打印视图
     * 
     * @return [type] [description]
     */
    public function printview()
    {
        $id     = HRequest::getParameter('id');
        $record = $this->_model->getRecordById($id);
        if(!$record) {
            throw new HVerifyException('信息不存在，请确认！');
        }
        HResponse::setAttribute('record', $record);
        $this->_assignOrderGoodsList($record);
        $this->_assignOrderAddress($record);
        $this->_assignOrderYunFei($record);

        $this->_render('order/print');
    }

    /**
     * 列表后驱方法
    */
    public function _otherJobsAfterList()
    {
        parent::_otherJobsAfterList();
        $list   = HResponse::getAttribute('order/list');
        HResponse::registerFormatMap(
            'status',
            'name',
            HArray::turnItemValueAsKey(OrderPopo::$_statusMap, 'id')
        );  
        HResponse::registerFormatMap(
            'pay_status',
            'name',
            HArray::turnItemValueAsKey(OrderPopo::$_payStatusMap, 'id')
        );  
        HResponse::registerFormatMap(
            'company_id',
            'name',
             HArray::turnItemValueAsKey($this->_getCompanyList($list), 'id')
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
        HResponse::setAttribute('status_list', OrderPopo::$_statusMap);
        HResponse::setAttribute('pay_status_list', OrderPopo::$_payStatusMap);
        HResponse::setAttribute('company_id_list', $this->_getCompanyList());
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
        $record     = HResponse::getAttribute('record');
        $this->_assignOrderGoodsList($record);
        $this->_assignOrderAddress($record);
        $this->_assignOrderYunFei($record);
    }

    /**
     * 加载订单收货地址
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    private function _assignOrderAddress($record)
    {
        $orderAddress = HClass::quickLoadModel('orderaddress')->getRecordByWhere('`id` = ' . $record['address_id']);

        HResponse::setAttribute('orderAddress', $orderAddress);
    }

    /**
     * 加载订单运费
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    private function _assignOrderYunFei($record)
    {
        $orderYunFei = HClass::quickLoadModel('orderyunfei')->getRecordByWhere('`parent_id` = ' . $record['id']);

        HResponse::setAttribute('orderYunFei', $orderYunFei);
    }

     /**
     * 加载商品列表
     *
     * @return [type] [description]
     */
    private function _assignOrderGoodsList($record)
    {
        $orderProductList = HClass::quickLoadModel('orderproduct')->getAllRows('`parent_id` = ' . $record['id']);
        $companyMap     = HArray::turnItemValueAsKey(
            HClass::quickLoadModel('company')->getAllRowsByFields(
                '`id`, `name`', 
                HSqlHelper::whereInByListMap('id', 'company_id', $orderProductList)
            ), 
        'id');

        HResponse::setAttribute('orderProductList', $orderProductList);
        HResponse::setAttribute('companyMap', $companyMap);
    }

      /**
     * 加载商品供货商列表
     * @return [type] [description]
     */
    private function _assignGoodsShopList()
    {
        $goodsMap   = HResponse::getAttribute('goodsMap');
        $shop       = HClass::quickLoadModel('shop');
        foreach($goodsMap as &$item) {
            $record     = $shop->getRecordByWhere('`id` = ' . $item['shop_id']);
            $item['shop_name']  = $record['name'];
        }
        HResponse::setAttribute('goodsMap', $goodsMap);
    }

    /**
     * 得到公司列表
     * @return [type] [description]
     */
    private function _getCompanyList($list)
    {
        $where = '1=1';
        if($list) {
            $where = HSqlHelper::whereInByListMap('id', 'company_id', $list);
        }
        $list = HClass::quickLoadModel('company')->getAllRowsByFields('`id`, `name`', $where);

        return $list;
    }

    /**
     * 用户待支付、取消订单
     * @return [type] [description]
     */
    public function daizhifu()
    {
        $where = $this->_combineWhere();
        $where .= ' AND (`status` = 1 OR `status` = 12)';
        $this->_search($where);

        $this->_render('order/list');   
    }

    /**
     * 商家未接单与已接单与拒绝订单
     * @return [type] [description]
     */
    public function shop()
    {
        $where = $this->_combineWhere();
        $where .= ' AND (`status` = 2 OR `status` = 3 OR `status` = 7)';
        $this->_search($where);

        $this->_render('order/list');      
    }

    /**
     * 商家接单与拒绝订单
     * @return [type] [description]
     */
    public function qishou()
    {
        $where = $this->_combineWhere();
        $where .= ' AND (`status` = 3 OR `status` = 4 OR `status` = 11)';
        $this->_search($where);

        $this->_render('order/list');      
    }

    /**
     * 商家接单与拒绝订单
     * @return [type] [description]
     */
    public function tuikuan()
    {
        $where = $this->_combineWhere();
        $where .= ' AND (`status` = 6 OR `status` = 8 OR `status` = 9)';
        $this->_search($where);

        $this->_render('order/list');      
    }

     /**
     * 快捷操作 
     * 
     * @access public
     */
    public function quick()
    {
        HVerify::isEmpty(HRequest::getParameter('operation'), HTranslate::__('操作不能为空'));
        HVerify::isEmpty(HRequest::getParameter('id'), HTranslate::__('操作项目'));
        $recordIds          = HRequest::getParameter('id');
        switch(HRequest::getParameter('operation')) {
            case 'delete': $this->delete(); return;
            case 'trash': $this->trash(); return;
            default: throw new HVerifyException('操作还没有开放使用～');
        }
        if(false === $this->_model->moreUpdate($recordIds, $opCfg)) {
            throw new HRequestException(HTranslate::__('更新失败'));
        }
        HResponse::succeed(HTranslate::__('更新成功'), $this->_getReferenceUrl(1));
    }

    /**
     * 删除订单
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function delete()
    {
        $recordIds  = HRequest::getParameter('id');
        if(!is_array($recordIds)) {
            $recordIds  = array($recordIds);
        }
        foreach($recordIds as $item) {
            $record     = $this->_model->getRecordById($item);
            if(!$record) {
                throw new HVerifyException('订单不存在，编号：' . $item);
            }
            if(12 != $record['status']) {
                throw new HVerifyException('订单不是删除状态，不能删除哦！' );
            }
            $this->_model->deleteByWhere('`id` = ' . $record['id']);
        }
        HResponse::succeed('删除成功！');
    }

    /**
     * 移除订单
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function trash()
    {
        $recordIds  = HRequest::getParameter('id');
        if(!is_array($recordIds)) {
            $recordIds  = array($recordIds);
        }
        foreach($recordIds as $item) {
            $record     = $this->_model->getRecordById($item);
            if(!$record) {
                throw new HVerifyException('订单不存在，编号：' . $item);
            }
            if(1 != $record['status']) {
                throw new HVerifyException('订单不是等待付款状态，不能取消哦！编号：' . $item );
            }
        }
        $data   = array('status' => 12);
        $this->_model->editByWhere($data, HSqlHelper::whereIn('id', $recordIds));
        HResponse::succeed('订单取消成功！');
    }

}

?>
