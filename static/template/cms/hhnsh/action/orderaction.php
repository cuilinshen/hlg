<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.orderpopo, model.ordermodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 订单的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class OrderAction extends HhnshAction 
{

    /**
     * 构造函数 
     * 
     * 初始化类里的变量 
     * 
     * @access public
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_popo        = new OrderPopo();
        $this->_model       = new OrderModel($this->_popo);
        $this->_product     = HClass::quickLoadModel('product');
        $this->_company     = HClass::quickLoadModel('company');
        $this->_statusMap   = HArray::turnItemValueAsKey(OrderPopo::$_statusMap, 'id');
    }

    /**
     * 状态Map
     * @var [type]
     */
    protected $_statusMap;

    /**
     * 产品对象
     * @var [type]
     */
    protected $_product;

    /**
     * 公司对象
     * @var [type]
     */
    protected $_company;

    private $_all   = ' `status` in (1,2,3)';

    /**
     * 待付款条件
     * @var string
     */
    private $_daifukuan = ' `status` in (1)';

    /**
     * 待审核条件
     * @var string
     */
    private $_shenhe = ' `status` in (2)';

    /**
     * 待参加条件
     * @var string
     */
    private $_canjia   = ' `status` in (3)';

    /**
     * 用户已评价条件
     * @var string
     */
    private $_comment   = ' `status` in (10)';

    /**
     * 退款条件
     * @var string
     */
    private $_tuikuan   = ' `status` in (6, 8, 9)';

    /**
     * 订单商品对象
     * @var [type]
     */
    private $_orderProduct;

    /**
     * 订单运费对象
     * @var [type]
     */
    private $_orderYunFei;


    /**
     * 订单列表
     * @return [type] [description]
     */
    public function index()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $this->_verifyUserOrderRight();
        $page       = HRequest::getParameter('page') ?　HRequest::getParameter('page') : 0;
        $where      = '`parent_id` = ' . $userId;
        $statusMap  = array(
            'daifukuan' => $where . ' AND ' . $this->_daifukuan,
            'shenhe' => $where . ' AND ' . $this->_shenhe,
            'canjia' => $where . ' AND ' . $this->_canjia,
        );
        $result = array();
        foreach($statusMap as $key => $value) {
            $result[$key]   = $this->_getOrderList($value, $page);
        }

        HResponse::json(array('rs' => true, 'data' => $result));
    }

    /**
     * 所有的订单列表
     * @return [type] [description]
     */
    public function all()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $this->_verifyUserOrderRight();
        $page       = HRequest::getParameter('page') ?　HRequest::getParameter('page') : 0;
        $where      = '`parent_id` = ' . $userId . ' AND ' . $this->_all;
        $list       = $this->_getOrderList($where, $page);
        
        HResponse::json(array('rs' => true, 'data' => $list));   
    }

    /**
     * 待付款订单列表
     * @return [type] [description]
     */
    public function daifukuan()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $this->_verifyUserOrderRight();
        $page       = HRequest::getParameter('page') ?　HRequest::getParameter('page') : 0;
        $where      = '`parent_id` = ' . $userId . ' AND ' . $this->_daifukuan;
        $list       = $this->_getOrderList($where, $page);
        
        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 待审核列表
     * @return [type] [description]
     */
    public function shenhe()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $this->_verifyUserOrderRight();
        $page       = HRequest::getParameter('page') ?　HRequest::getParameter('page') : 0;
        $where      = '`parent_id` = ' . $userId . ' AND ' . $this->_shenhe;
        $list       = $this->_getOrderList($where, $page);
        
        HResponse::json(array('rs' => true, 'data' => $list));   
    }

    /**
     * 待参加列表
     * @return [type] [description]
     */
    public function canjia()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $this->_verifyUserOrderRight();
        $page       = HRequest::getParameter('page') ?　HRequest::getParameter('page') : 0;
        $where      = '`parent_id` = ' . $userId . ' AND ' . $this->_canjia;
        $list       = $this->_getOrderList($where, $page);
        
        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 评价订单列表
     * @return [type] [description]
     */
    public function comment()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $page       = HRequest::getParameter('page') ?　HRequest::getParameter('page') : 0;
        $where      = '`parent_id` = ' . $userId . ' AND ' . $this->_comment;
        $list       = $this->_getOrderList($where, $page);
        
        HResponse::json(array('rs' => true, 'data' => $list));   
    }

    /**
     * 退款订单列表
     * @return [type] [description]
     */
    public function tuikuan()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $page       = HRequest::getParameter('page') ?　HRequest::getParameter('page') : 0;
        $where      = '`parent_id` = ' . $userId . ' AND ' . $this->_tuikuan;
        $list       = $this->_getOrderList($where, $page);
        
        HResponse::json(array('rs' => true, 'data' => $list));      
    }

    /**
     * 得到分页的订单列表数据
     * @param  [type] $userId [description]
     * @param  [type] $page   [description]
     * @return [type]         [description]
     */
    protected function _getOrderList($where, $page)
    {
        $perpage    = 10;
        $this->_popo->setFieldAttribute('id', 'is_order', 'desc');
        $list       = $this->_model->getListByFields(
            '*', 
            $where,
            $page,
            $perpage
        );
        $this->_orderProduct    = HClass::quickLoadModel('orderproduct');
        foreach($list as $key => &$item) {
            $item['key']            = $key;
            $item['productInfo']    = $this->_getOrderProductListByOrder($item['id']);
            $item['status_name']    = $this->_statusMap[$item['status']]['name'];
        }


        return $list;    
    }

    /**
     * 得到订单商品列表
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function _getOrderProductListByOrder($id)
    {
        $orderProductRecord   = $this->_orderProduct->getRecordbyWhere('`parent_id` = ' . $id);
        if(!$orderProductRecord) {
            return array();
        }
        $productRecord  = $this->_product->getRecordbyWhere('`id` = ' . $orderProductRecord['product_id']);
        $productRecord['image_path'] = HString::formatImage($productRecord['image_path']);
        $productRecord['start_date'] = $this->_formatProductDate($productRecord['start_date'], $productRecord['end_date']);
        $productTypeRecord = HClass::quickLoadModel('producttype')->getRecordbyWhere('`id` = ' . $orderProductRecord['producttype_id']);

        return array('productRecord' => $productRecord, 'productTypeRecord' => $productTypeRecord);
    }

    /**
     * 添加/修改公司信息
     */
    public function add()
    {
        $data = $this->_verifyAddData();
        $id   = $this->_model->add($data);
        if(1 > $id) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
        }
        $this->_updateFinishedData($id);
        $data['id'] = $id;

        HResponse::json(array('rs' => true, 'data' => $data));
    }

    /**
     * 生成主要订单数据
     */
    private function _addMainOrderData()
    {
        $data   = array();
        $data['parent_id']      = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $data['name']           = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '联系人');
        $data['total_money']    = $this->_verifyJSONIsEmpty(HRequest::getParameter('total_money'), '总金额');
        $data['phone']          = $this->_verifyJSONIsEmpty(HRequest::getParameter('phone'), '联系电话');
        $data['code']           = $this->_getOrderCode();   
        $id     = HClass::quickLoadModel('mainorder')->add($data);
        if(1 > $id) {
            HResponse::json(array('rs' => false, '添加主订单失败'));
        }
        $data['id']     = $id;
        $data['product_id'] = 0;

        return $data;
    }

      /**
     * 重构验证添加数据
     * @return [type] [description]
     */
    protected function _verifyAddData()
    {
        $data   = array();
        $data['parent_id']      = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $data['name']           = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '联系人');
        $data['total_money']    = HRequest::getParameter('total_money');
        $data['phone']          = $this->_verifyJSONIsEmpty(HRequest::getParameter('phone'), '联系电话');
        $data['code']           = $this->_getOrderCode();
        $this->_verifyUserOrderRight();
        if(HRequest::getParameter('address_id')) {
            $data['address_id']     = HRequest::getParameter('address_id');
        }

        return $data;
    }     

    /**
     * 添加上门地址
     */
    private function _addOrderAddress()
    {
        $data       = array();
        $data['name']       = HRequest::getParameter('name');
        $data['address']    = $this->_verifyJSONIsEmpty(HRequest::getParameter('address'), '详细地址');
        $data['phone']      = $this->_verifyJSONIsEmpty(HRequest::getParameter('phone'), '联系电话');
        $data['menpai']     = HRequest::getParameter('menpai');
        $data['status']     = 2;
        $data['parent_id']  = HRequest::getParameter('user_id');
        $id                 = HClass::quickLoadModel('orderaddress')->add($data);
        if(1 > $id) {
            HResponse::json(array('rs' => false, 'message' => '收货地址创建失败'));
            return ;
        }        

        return $id;
    }


     /**
     * 更新完成后其他数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function _updateFinishedData($id)
    {
        $product_number = $this->_verifyJSONIsEmpty(HRequest::getParameter('product_number'), '数量');
        $product_id     = $this->_verifyJSONIsEmpty(HRequest::getParameter('product_id'), '所属产品');
        $product        = HClass::quickLoadModel('product');
        $productRecord  = $product->getRecordbyId($product_id);
        $producttype_id = $this->_verifyJSONIsEmpty(HRequest::getParameter('producttype_id'), '活动类型');
        $productTypeRecord = HClass::quickLoadModel('producttype')->getRecordbyId($producttype_id);
        $orderInfo      = $this->_model->getRecordbyId($id);
        if($productTypeRecord['type'] == 1) {
            if(1 > $this->_model->editByWhere(array('pay_status' => 1, 'pay_time' => time(), 'status' => 2), '`id` = ' . $orderInfo['id'])) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));
            }
            //更新活动数量
            if(1 > $product->editByWhere(array('total_orders' => $productRecord['total_orders'] + 1), '`id` = ' . $productRecord['id'])) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));
            }
        }
        
        $this->_orderProduct    = HClass::quickLoadModel('orderproduct');
        $data   = array(
            'product_id' => $product_id,
            'parent_id'  => $id,
            'producttype_id' => $producttype_id,
            'number'    => $product_number,
            'price'     => $productTypeRecord['price'],
            'name'      => $productTypeRecord['name'],
            'image_path'=> $productRecord['image_path'],
            'user_id'   => HRequest::getParameter('user_id'),
            'author'    => HRequest::getParameter('user_id')
        );
        $this->_addOrderProduct($data);
    }

    /**
     * 添加订单商品列表
     */
    private function _addOrderProduct($data)
    {
        if(1 > $this->_orderProduct->add($data)) {
            HResponse::json(array('rs' => false, 'message' => '订单商品添加失败'));
        }
    }

    /**
     * 添加订单运费列表
     */
    private function _addOrderYunFei($data)
    {
        if(1 > $this->_orderYunFei->add($data)) {
            HResponse::json(array('rs' => false, 'message' => '订单运费添加失败'));
        }
    }

    /**
     * 得到订单的Code
     * @return [type] [description]
     */
    private function _getOrderCode()
    {   
        return rand(1000, 9999) . time();
    }

    /**
     * 验证用户订单权限
     * @return [type] [description]
     */
    protected function _verifyUserOrderRight()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openID');
        $userInfo   = HClass::quickLoadModel('user')->getRecordbyId($userId);
        if($userInfo['open_id'] != $openId) {
            HResponse::json(array('rs' => false, 'message' => '权限不够'));
            return;
        } 
        
        return $userId;
    }

    /**
     * 验证记录数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _verifyRecordData($record)
    {   
        $this->_verifyUserOrderRight();
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        if($record['parent_id'] != $userId) {
            HResponse::json(array('rs' => false, 'message' => '权限不够'));
            return;
        } 
    }

      /**
     * 加载额外的记录数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _assignRecordOtherInfo($record)
    {
        $data       = array(
            'buyInfo'   => array(),
            'orderInfo' => array(),
            'productInfo' => array()
        );
        if($record['address_id'] > 0) {
            $data['buyInfo'] = HClass::quickLoadModel('orderaddress')->getRecordbyWhere('`id` = ' . $record['address_id']);
        }
        $this->_orderProduct    = HClass::quickLoadModel('orderproduct');
        $record['create_time']  = date('m-d H:i', strtotime($record['create_time'])); 
        $data['productInfo']    = $this->_getOrderProductListByOrder($record['id']);
        $data['orderInfo']      = $record;

        return $data;
    }

      /**
     * 用户取消订单
     * @return [type] [description]
     */
    public function cancleorder()
    {
        $userId     = $this->_verifyUserOrderRight();
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '订单ID');
        $record     = $this->_model->getRecordbyId($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '订单不存在'));
            return;
        }
        if($userId != $record['parent_id']) {
            HResponse::json(array('rs' => false, 'message' => '您不能操作该订单'));
            return;   
        }
        if($record['status'] == 12) {
            HResponse::json(array('rs' => false, 'message' => '订单已取消'));
            return;   
        } 
        if($record['status'] > 2) {
            HResponse::json(array('rs' => false, 'message' => '商家已接单，订单不能取消'));
            return;     
        }
        if(1 > $this->_model->editByWhere(array('status' => 12), '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
            return;
        }
        $record['status']   = 12;
        $record['status_name']  = $this->_statusMap[$record['status']]['name'];
        //发送站内消息
        
        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 用户确认订单
     * @return [type] [description]
     */
    public function confirmorder()
    {
        $userId     = $this->_verifyUserOrderRight();
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '订单ID');
        $record     = $this->_model->getRecordbyId($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '订单不存在'));
            return;
        }
        if($userId != $record['parent_id']) {
            HResponse::json(array('rs' => false, 'message' => '您不能操作该订单'));
            return;   
        }
        if($record['status'] == 5) {
            HResponse::json(array('rs' => false, 'message' => '订单已确认'));
            return;     
        }
        if($record['status'] != 11) {
            HResponse::json(array('rs' => false, 'message' => '订单未送达，不可确认'));
            return;     
        }
        if(1 > $this->_model->editByWhere(array('status' => 5), '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
            return;
        }
        $record['status']   = 5;
        $record['status_name']  = $this->_statusMap[$record['status']]['name'];
        //发送站内消息
        
        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 删除订单
     * @return [type] [description]
     */
    public function delorder()
    {
        $userId     = $this->_verifyUserOrderRight();
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '订单ID');
        $record     = $this->_model->getRecordbyId($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '订单不存在'));
            return;
        }
        if($userId != $record['parent_id']) {
            HResponse::json(array('rs' => false, 'message' => '您不能操作该订单'));
            return;   
        }
        if(1 > $this->_model->deleteByWhere('`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
            return;
        }
        
        HResponse::json(array('rs' => true, 'data' => $record));   
    }

    /**
     * 客户拒绝订单
     * @return [type] [description]
     */
    public function refundorder()
    {
        $userId     = $this->_verifyUserOrderRight();
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '订单ID');
        $record     = $this->_model->getRecordbyId($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '订单不存在'));
            return;
        }
        $companyInfo    = HClass::quickLoadModel('company')->getRecordbyWhere('`userid` = ' . $userId); 
        if($record['company_id'] != $companyInfo['id']) {
            HResponse::json(array('rs' => false, 'message' => '您不能操作该订单'));
            return;      
        }
        if($record['status'] == 8) {
            HResponse::json(array('rs' => false, 'message' => '订单已取消'));
            return;   
        } 
        if($record['status'] > 2 && $record['status'] != 8) {
            HResponse::json(array('rs' => false, 'message' => '订单不能拒绝'));
            return;     
        }
        if(1 > $this->_model->editByWhere(array('status' => 8), '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
            return;
        }
        $record['status']   = 8;
        $record['status_name']  = $this->_statusMap[$record['status']]['name'];

        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 得到评论的产品列表
     * @return [type] [description]
     */
    public function getcommentproduct()
    {
        $userId     = $this->_verifyUserOrderRight();
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '订单ID');
        $record     = $this->_model->getRecordbyId($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '订单不存在'));
            return;
        }
        if($userId != $record['parent_id']) {
            HResponse::json(array('rs' => false, 'message' => '您不能操作该订单'));
            return;   
        }
        $orderProduct = HClass::quickLoadModel('orderproduct');
        $list = $orderProduct->getAllRows('`parent_id` = ' . $record['id']);
        foreach($list as $key => &$item) {
            $item['image_path'] = HString::formatImage($item['image_path']);
            $item['open']   = false;
            $item['anonymous'] = false;
            $item['star']   = 0;
        }

        HResponse::json(array('rs' => true, 'data' => $list));
        
    }
    
    /**
     * 评价订单
     * @return [type] [description]
     */
    public function commentorder()
    {
        $this->_verifyUserOrderRight();
        $data      = array();
        $data['name'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '评论人');
        $data['parent_id'] = HRequest::getParameter('parent_id');
        $data['content']    = $this->_verifyJSONIsEmpty(HRequest::getParameter('content'), '评论内容');
        $data['product_id'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('product_id'), '所属产品');
        $data['order_id']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('order_id'), '评价订单');
        $data['sudu_score'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('sudu_score'), '评分');

        $orderProduct = HClass::quickLoadModel('orderproduct');
        $orderProductRecord = $orderProduct->getRecordbyWhere('`parent_id` = ' . $data['order_id'] . ' AND `product_id` = ' . $data['product_id']);
        if($orderProductRecord['comment_id'] > 0) {
            HResponse::json(array('rs' => false,  'message'=> '该商品已经被点评！'));
            return;
        }
        $comment = HClass::quickLoadModel('comment');
        $id     = $comment->add($data);
        if(1 > $id) {
            HResponse::json(array('rs' => false, 'message' => '点评失败'));
        }

        //更新订单状态、订单商品的评论ID、商品的总评价数
        $orderProduct->editByWhere(array('comment_id' => $id), '`id` = ' . $orderProductRecord['id']);
        $this->_model->editByWhere(array('status' => 10), '`id` = ' . $data['order_id']);
        $product    = HClass::quickLoadModel('product');
        $productInfo    = $product->getRecordbyId($data['product_id']);
        $productInfo['total_comments'] += 1;
        $product->editByWhere(array('total_comments' => $productInfo['total_comments']), '`id` = ' . $productInfo['id']);

        HResponse::json(array('rs' => true));
    }

}