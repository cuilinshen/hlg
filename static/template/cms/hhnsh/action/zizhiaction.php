<?php

/**
 * @version $Id$
 * @create 2014/3/24 21:40:39 By luodao
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('config.popo.zizhipopo, model.zizhimodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 资质的动作类
 * 
 * 主要处理联系方式的相关请求动作 
 * 
 * @author luodao <luodao@foxmail.com>
 * @package None
 * @since 1.0.0
 */
class ZizhiAction extends HhnshAction
{

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
        $this->_popo    = new ZizhiPopo();
        $this->_model   = new ZizhiModel($this->_popo);
        $this->_user    = HClass::quickLoadModel('user');
    }

    /**
     * 我的案例中心
     * @return [type] [description]
     */
    public function index()
    {
        $userId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'OpenID');
        $shopId  = $this->_verifyJSONIsEmpty(HRequest::getParameter('shop_id'), '店铺ID');
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $prepage= HRequest::getParameter('prepage') ? HRequest::getParameter('prepage') : 6;
        $this->_verifyUserOpenId($openId, $userId);
        $list = $this->_model->getListByFields('*', '`parent_id` = ' . $shopId, $page - 1, $prepage);
        foreach ($list as $key => &$value) {
            $value['image_path'] = HString::formatImage($value['image_path']);
        }

        HResponse::json(array('rs' => true, 'data' => $list));
    }
    
    /**
     * 验证添加的数据
     * @return [type] [description]
     */
    protected function _verifyAddData()
    {
        $data       = array();
        $userId      = $this->_verifyJSONIsEmpty(HRequest::getParameter('userid'), '用户id');
        $openid = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openid');
        $this->_verifyUserOpenId($openid, $userId);
        $data['user_name']        = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_name'), '法人姓名');
        $data['user_icard']       = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_icard'), '法人身份证');
        $data['face_image']       = $this->_verifyJSONIsEmpty(HRequest::getParameter('face_image'), '法人身份证正面照');
        $data['licence_name']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('licence_name'), '执照名称');
        $data['parent_id']        = $this->_verifyJSONIsEmpty(HRequest::getParameter('parent_id'), '所属分类');
        $data['image_path']       = $this->_verifyJSONIsEmpty(HRequest::getParameter('image_path'), '执照图片');
        $data['licence_number']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('licence_number'), '执照注册号');
        $data['licence_address']  = $this->_verifyJSONIsEmpty(HRequest::getParameter('licence_address'), '执照所在地');
        $data['licence_type']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('licence_type'), '执照类型');
        $data['licence_time']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('licence_time'), '执照有效期');
        $data['author']           = $userId;

        return $data;
    }

}
?>
