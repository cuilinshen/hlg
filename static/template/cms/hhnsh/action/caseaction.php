<?php

/**
 * @version $Id$
 * @create 2014/3/24 21:40:39 By luodao
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('config.popo.casepopo, model.casemodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 案例中心的动作类
 * 
 * 主要处理联系方式的相关请求动作 
 * 
 * @author luodao <luodao@foxmail.com>
 * @package None
 * @since 1.0.0
 */
class CaseAction extends HhnshAction
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
        $this->_popo    = new CasePopo();
        $this->_model   = new CaseModel($this->_popo);
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
    

}
?>
