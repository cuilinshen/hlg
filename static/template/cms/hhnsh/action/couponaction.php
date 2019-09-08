<?php

/**
 * @version $Id$
 * @create 2014/3/24 21:40:39 By luodao
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('config.popo.couponpopo, model.couponmodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 优惠券的动作类
 * 
 * 主要处理联系方式的相关请求动作 
 * 
 * @author luodao <luodao@foxmail.com>
 * @package None
 * @since 1.0.0
 */
class CouponAction extends HhnshAction
{

    /**
     * @var String $_identifier分类名称
     */
    protected $_identifier;

    /**
     * 构造方法
     * 
     * 初始化类里面的变量
     * 
     * @author luodao <luodao@foxmail.com>
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->_popo    = new CouponPopo();
        $this->_model   = new CouponModel($this->_popo);
        $this->_user    = HClass::quickLoadModel('user');
    }

    /**
     * 得到优惠券列表
     * @return [type] [description]
     */
    public function userlist()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $this->_verifyUserOpenId($openId, $userId);
        $linkeddata = HClass::quickLoadModel('linkeddata');
        $linkeddata->setRelItemModel('user', 'coupon');
        $linkeddataList = $linkeddata->getAllRows('`item_id` = ' . $userId);
        if(!$linkeddataList) {
            HResponse::json(array('rs' => true, 'data' => array()));            
        }
        $where  = HSqlHelper::whereInByListMap('id', 'rel_id', $linkeddataList);
        $couponMap = HArray::turnItemValueAsKey(
            $this->_model->getAllRowsByFields('*', $where), 
            'id'
        );
        $data = array(
            '0' => array(), 
            '1' => array(), 
            '2' => array()
        );
        foreach($linkeddataList as $key => &$item) {
            if(isset($data[$item['extend']])) {
                 $item['coupon'] = $couponMap[$item['rel_id']];
                 array_push($data[$item['extend']], $item);
            }
            
        }

        HResponse::json(array('rs' => true, 'data' => $data));
    }

    /**
     * 用户领取优惠券
     * @return [type] [description]
     */
    public function aget()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '优惠券Id');
        $this->_verifyUserOpenId($openId, $userId);
        $record     = $this->_model->getRecordById($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '记录不存在'));
        }
        if($record['level_num'] < 1) {
            HResponse::json(array('rs' => false, 'message' => '优惠券已经被领完'));
        }
        if($record['status'] == 2) {
            HResponse::json(array('rs' => false, 'message' => '优惠券已过期'));   
        }
        if($record['status'] == 3) {
            HResponse::json(array('rs' => false, 'message' => '优惠券已失效'));   
        }
        $linkeddata = HClass::quickLoadModel('linkeddata');
        $linkeddata->setRelItemModel('user', 'coupon');
        $where = '`item_id` = ' . $userId . ' AND `rel_id` = ' . $id;
        $linkeddataRecord = $linkeddata->getRecordByWhere($where);
        if($linkeddataRecord) {
            HResponse::json(array('rs' => false, 'message' => '您已经领取该优惠券'));
        }
        $data = array(
            'item_id' => $userId,
            'rel_id'  => $id,
            'extend' => 0
        );
        if(1 > $linkeddata->add($data)) {
            HResponse::json(array('rs' => false, 'message' => '关联关系建立失败'));
        }

        HResponse::json(array('rs' => true));
    }

    /**
     * 验证添加/编辑数据
     * @return [type] [description]
     */
    protected function _verifyAddData()
    {
        $data   = array();
        $userId = $this->_verifyJSONIsEmpty('user_id', '所属用户');
        $openId = $this->_verifyJSONIsEmpty('open_id', '用户授权openid');
        $userInfo= $this->_verifyUserOpenId($openId, $userId);
        //判断用户是不是商家
        if($userInfo['parent_id'] != 3) {
            HResponse::json(array('rs' => false, 'message' => '对不起您不是店家'));
        }
        $data['company_id'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('company_id'), '所属店铺');
        $data['product_id'] = HRequest::getParameter('product_id') ? HRequest::getParameter('product_id') : 0;
        $data['name']       = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '优惠券名称');
        $data['max']        = $this->_verifyJSONIsEmpty(HRequest::getParameter('max'), '满的金额');
        $data['song']       = $this->_verifyJSONIsEmpty(HRequest::getParameter('song'), '减的金额');
        $data['total_num']  = $this->_verifyJSONIsEmpty(HRequest::getParameter('total_num'), '发放总数');
        $data['start_time'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('start_time'), '生效时间');
        $data['end_time']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('end_time'), '过期时间');
        $data['description']= HRequest::getParameter('description');
        $data['status']     = 1;


        return $data;
    }

    /**
     * 得到优惠券列表
     * @return [type] [description]
     */
    public function companylist()
    {   
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '所属用户');
        $openId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), '用户授权openid');
        $companyId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('company_id'), '店铺ID');
        $userInfo= $this->_verifyUserOpenId($openId, $userId);
        //判断用户是不是商家
        if($userInfo['parent_id'] != 3) {
            HResponse::json(array('rs' => false, 'message' => '对不起您不是店家'));
        }
        $this->_verifyUserCompany($userId, $companyId);
        $page       = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $prepage    = HRequest::getParameter('prepage') ? HRequest::getParameter('prepage') : 10;
        $where      = '`company_id` = ' . $companyId;
        if(HRequest::getParameter('keyword')) {
            $where .= ' AND `name` like \'%' . HRequest::getParameter('keyword') . '%\'';
        }
        if(HRequest::getParameter('status')) {
            $where .= ' AND `status` = ' . HRequest::getParameter('status');
        }
        $list = $this->_model->getListByWhere($where, $page - 1, $prepage);

        HResponse::json(array('rs' => true, 'data' => $list));
    }

}
?>
