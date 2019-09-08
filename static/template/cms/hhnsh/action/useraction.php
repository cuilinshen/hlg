<?php 

/**
 * @version $Id$
 * @create 2017-04-16 17:41:59 By luoxinhua
 * @copyRight Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
HClass::import('config.popo.userpopo, model.usermodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');
HClass::import('app.oauth.action.auserAction');

/**
 * 个人中心
 * 
 * @desc
 * 
 * @author luoxinhua <1171102882@qq.com>
 * @package None
 * @since 1.0.0
 */
class UserAction extends HhnshAction
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
        $this->_popo        = new UserPopo();
        $this->_model       = new UserModel($this->_popo);
        $this->_user        = $this->_model;
    }

    /**
     * 得到实时数据
     * 
     * @author luoxinhua <1171102882@qq.com>
     * @access public
     * @return void
     */
    public function index()
    {
        $userId         = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId         = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'OpenID');
        $userInfo       = $this->_verifyUserOpenId($openId, $userId);
        //$totalMessage   = HClass::quickLoadModel('message')->getTotalRecords('`parent_id` = ' . $userId . ' AND `status` = 1');
        unset($userInfo['password']);
        //$userInfo['total_message'] = $totalMessage;
        $userInfo['companyInfo'] = HClass::quickLoadModel('company')->getRecordByWhere('`userid` = ' . $userInfo['id']);
        $userInfo   = $this->_assignOrderNumber($userInfo);
        $userInfo['total_visits'] = $this->_getHistoryVisits($userId);


        HResponse::json(array('rs' => true, 'data' => $userInfo));
    }

    /**
     * 加载订单数
     * @param  [type] $userId [description]
     * @return [type]         [description]
     */
    private function _assignOrderNumber($userInfo)
    {
        $statusMap = array(
            'daizhifu'  => '`status` = 1',
            'daishenhe' => '`status` = 2',
            'daicanjia' => '`status` = 3'
        );
        $order = HClass::quickLoadModel('order');
        foreach($statusMap as $key => $where) {
            $where .= ' AND `parent_id` = ' . $userInfo['id'];
            $userInfo[$key] = $order->getTotalRecords($where);
        }

        return $userInfo;
    }

    /**
     * 加载总的浏览记录
     * @param  [type] $userId [description]
     * @return [type]         [description]
     */
    private function _getHistoryVisits($userId)
    {
        $linkeddata = HClass::quickLoadModel('linkeddata');
        $linkeddata->setRelItemModel('product', 'user');

        return $linkeddata->getTotalRecords('`rel_id` = ' . $userId);
    }

    /**
     * 登录获取用户及其他相关信息
     * @return [type] [description]
     */
    public function login()
    {
        $openid     = HRequest::getParameter('openid');
        $name       = HRequest::getParameter('name');
        $image_path = HRequest::getParameter('image_path');

        $this->_verifyJSONIsEmpty($name, '昵称');
        $this->_verifyJSONIsEmpty($openid, 'openid');

        $where  = '`open_id` = \'' . $openid . '\'';
        $record = $this->_model->getRecordByWhere($where);
        if($record) {
            HResponse::json(array('rs' => true, 'data' => $record));
            exit();
        }
        $data['name']       = $name;
        $data['image_path'] = $image_path;
        $data['open_id']    = $openid;
        $data['login_time'] = time();
        $data['ip']         = '127.0.1';
        $data['create_time']= date('Y-m-d H:i:s', time());
        $id     = $this->_model->add($data);
        if(1 > $id) {
            HResponse::json(array('rs' => false, 'data' => '用户添加失败'));
            exit();
        }
        $record     = $this->_model->getRecordById($id);
        HResponse::json(array('rs' => true, 'data' => $record));
        exit();
    }

    /**
     * 我的收藏列表
     * @return [type] [description]
     */
    public function collectlist()
    {
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $prepage    = HRequest::getParameter('prepage') ? HRequest::getParameter('prepage') : 10;
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $linkeddata = HClass::quickLoadModel('linkeddata');
        $linkeddata->setRelItemModel('user', 'collect');
        $this->_verifyUserOpenId($openId, $userId);
        $where  = '`item_id` = ' . $userId;
        $list   = $linkeddata->getListByWhere($where, $page - 1, $prepage);
        if(empty($list)) {
            HResponse::json(array('rs' => true, 'data' => array()));            
        }
        $company = HClass::quickLoadModel('company');
        $companyMap = HArray::turnItemValueAsKey(
            $company->getAllRowsByFields('*', HSqlHelper::whereInByListMap('id', 'rel_id', $list)), 
            'id'
        );  
        $lat       = HRequest::getParameter('lat');
        $lng       = HRequest::getParameter('lng');
        foreach($list as $key => &$item) {
            $companyInfo = $companyMap[$item['rel_id']];
            $companyInfo['image_path']  = HString::formatImage($companyInfo['image_path']);
            $companyInfo['distance']    = $this->_getDistance($lat, $lng, $companyInfo['lat'], $companyInfo['longs']);
            $companyInfo['tagArr']      = $this->_getTagsArr($companyInfo['attrs']);
            $item['company'] = $companyInfo;
        }

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 格式化标签为数组
     * @param  [type] $tagStr [description]
     * @return [type]         [description]
     */
    private function _getTagsArr($tagStr)
    {
        if(!$tagStr) {
            return array();
        }

        return explode(',', $tagStr);
    }

    /**
     * 浏览记录列表
     * @return [type] [description]
     */
    public function visitlist()
    {
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $prepage    = HRequest::getParameter('prepage') ? HRequest::getParameter('prepage') : 10;
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $linkeddata = HClass::quickLoadModel('linkeddata');
        $linkeddata->setRelItemModel('user', 'visit');
        $this->_verifyUserOpenId($openId, $userId);
        $where  = '`item_id` = ' . $userId;
        $list   = $linkeddata->getListByWhere($where, $page - 1, $prepage);
        if(empty($list)) {
            HResponse::json(array('rs' => true, 'data' => array()));            
        }
        $company = HClass::quickLoadModel('company');
        $companyMap = HArray::turnItemValueAsKey(
            $company->getAllRowsByFields('*', HSqlHelper::whereInByListMap('id', 'rel_id', $list)), 
            'id'
        );  
        $lat       = HRequest::getParameter('lat');
        $lng       = HRequest::getParameter('lng');
        foreach($list as $key => &$item) {
            $companyInfo = $companyMap[$item['rel_id']];
            $companyInfo['image_path']  = HString::formatImage($companyInfo['image_path']);
            $companyInfo['distance']    = $this->_getDistance($lat, $lng, $companyInfo['lat'], $companyInfo['longs']);
            $companyInfo['tagArr']      = $this->_getTagsArr($companyInfo['attrs']);
            $item['company'] = $companyInfo;
        }

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 验证添加/修改数据
     * @return [type] [description]
     */
    protected function _verifyAddData()  
    {
        $data   = array();
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '用户ID');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $this->_verifyUserOpenId($openId, $userId);
        $data['id']     = $userId;
        $data['name'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '昵称');
        $data['phone'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('phone'), '电话');
        $data['address'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('address'), '地址');
        $data['sex'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('sex'), '性别');
        $data['birthday'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('birthday'), '生日');
        $data['car_number'] = $this->_verifyJSONIsEmpty(HRequest::getParameter('car_number'), '车牌号');

        return $data;
    }

}   

?>
