<?php 

/**
 * @version $Id$
 * @create 2017-04-16 17:41:59 By luoxinhua
 * @copyRight Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');
HClass::import('app.oauth.action.auseraction');
HClass::import('config.popo.userpopo, model.usermodel');

/**
 * 登陆动作 
 * 
 * @desc
 * 
 * @author luoxinhua <1171102882@qq.com>
 * @package None
 * @since 1.0.0
 */
class EnterAction extends HhnshAction
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
    }

    /**
     * 角色Map
     * @var array
     */
    private $_roleMap = array(
        '2' => '用户',
        '3' => '店家',
        '4' => '骑手'
    );

    /**
     * 手机号码或者昵称及密码验证登录
     * @return [type] [description]
     */
    public function dologinbypwd()
    {
        $phone      = HRequest::getParameter('phone');
        $password   = HRequest::getParameter('password');
        $openId     = HRequest::getParameter('open_id');
        $where      = '`phone` = \'' . $phone . '\'';
        $record     = $this->_model->getRecordByWhere($where);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '手机号码未注册，请选择手机号码快捷登录'));
            return ;
        }
        if($record['open_id'] != $openId) {
            HResponse::json(array('rs' => false, 'message' => '您的权限不合法，请联系管理员'));
            return ;
        }
        if($record['password'] != md5($password)) {
            HResponse::json(array('rs' => false, 'message' => '您的密码错误，请确认'));
            return ;   
        }
        $record['role']     = $this->_roleMap[$record['parent_id']];
        //登录成功
        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 手机号码或验证码验证通过
     * @return [type] [description]
     */
    public function dologinbyvcode()
    {
        $phone  = HRequest::getParameter('phone');
        $vcode  = HRequest::getParameter('vcode');
        $openId = HRequest::getParameter('open_id');
        $name   = HRequest::getParameter('name');
        $this->_verifyJSONIsEmpty($name, '用户昵称');
        $this->_verifyJSONIsEmpty($openId, 'openid');
        $this->_verifyJSONIsEmpty($vcode, '验证码');
        $this->_verifyJSONIsEmpty($phone, '手机号码');
        $smsTotal   = HClass::quickLoadModel('smstotal');
        $date = date('Y-m-d', time());
        $where  = '`open_id` = \'' . $openId . '\' AND `date` = \'' . $date . '\' AND `type` = 1';
        $record = $smsTotal->getRecordByWhere($where); 
        if($record['vcode'] != $vcode) {
            HResponse::json(array('rs' => false, 'message' => '验证码错误'));
        }
        $smsSendRecord = HClass::quickLoadModel('smssend')->getRecordByWhere('`id` = ' . $record['sms_send_id']);
        if($smsSendRecord['phone'] != $phone) {
            HResponse::json(array('rs' => false, 'message' => '手机号码错误，请确认'));
        }
        $userInfo   = $this->_model->getRecordByWhere('`open_id` = \'' . $openId . '\'');
        if($userInfo) {
            $editData = array('name' => $name, 'phone' => $phone);
            if(!$userInfo['token']) {
                $editData['token']  = md5(uniqid());
            }
            if(1 > $this->_model->editByWhere($editData, '`id` = ' . $userInfo['id'])){
                HResponse::json(array('rs' => false, 'message' =>'登录失败'));
            }
            $userInfo['name']   = $name;
            $userInfo['phone']  = $phone;
            $userInfo['token']  = $userInfo['token'] ? $userInfo['token'] : $editData['token'];
            $userInfo['role']   = $this->_roleMap[$userInfo['parent_id']];
            HResponse::json(array('rs' => true, 'data' => $userInfo));
            return;
        }
        $data['phone']      = $phone;
        $data['name']       = $name;
        $data['image_path'] = HRequest::getParameter('image_path');
        $data['open_id']    = $openId;
        $data['login_time'] = time();
        $data['ip']         = HRequest::getClientIp();
        $data['create_time']= date('Y-m-d H:i:s', time());
        $data['token']      = md5(uniqid());
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
     * 设置密码
     * @return [type] [description]
     */
    public function dosetpwd()
    {
        $password   = HRequest::getParameter('password');
        $token      = HRequest::getParameter('token'); 
        $openId     = HRequest::getParameter('open_id');
        $roleId     = HRequest::getParameter('role_id');
        $this->_verifyJSONIsEmpty($password, '密码');
        $this->_verifyJSONIsEmpty($token, 'token');
        $this->_verifyJSONIsEmpty($openId, 'openid');
        $this->_verifyJSONIsEmpty($roleId, '角色id');
        $where      = '`token` = \'' . $token . '\' AND `open_id` = \'' . $openId . '\'';
        $record     = $this->_model->getRecordByWhere($where);
        $data['password']   = md5($password);
        $data['parent_id']  = $roleId;
        if(1 > $this->_model->editByWhere($data, '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '设置密码失败'));
        }
        $record['parent_id']    = $roleId;
        $record['role']         = $this->_roleMap[$roleId];

        HResponse::json(array('rs' => true, 'data' => $record));
    }

     /**
     * 用户openid授权验证登录
     * @return [type] [description]
     */
    public function dologinbyauth()
    {
        $openId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), '权限');
        $name       = $this->_verifyJSONIsEmpty(HRequest::getParameter('nickName'), '昵称');
        $sex        = $this->_verifyJSONIsEmpty(HRequest::getParameter('gender'), '性别');
        $avatarUrl  = HRequest::getParameter('avatarUrl');
        $city       = HRequest::getParameter('city');
        $province   = HRequest::getParameter('province');
        $where      = '`open_id` = \'' . $openId . '\'';
        $record     = $this->_model->getRecordByWhere($where);
        if(!$record) {
            $data['name']       = $name;
            $data['image_path'] = $avatarUrl;
            $data['open_id']    = $openId;
            $data['login_time'] = time();
            $data['sex']        = $sex;
            $data['city']       = $city;
            $data['province']   = $province;
            $data['ip']         = HRequest::getClientIp();
            $data['create_time']= date('Y-m-d H:i:s', time());
            $data['parent_id']  = 2;
            $data['token']      = md5(uniqid());
            $id     = $this->_model->add($data);
            if(1 > $id) {
                HResponse::json(array('rs' => false, 'data' => '用户添加失败'));
                exit();
            }
            $record     = $this->_model->getRecordById($id);
            HResponse::json(array('rs' => true, 'data' => $record));
        }else{
            //登录成功
            HResponse::json(array('rs' => true, 'data' => $record));
        }
    }



      /**
     * 微信登录获取openid
     * @return [type] [description]
     */
    public function wxlogin()
    {
        //活动汇
        // $appId  = 'wx3aff9f8f931e0a82';    
        // $secret = '91c03ce989be9495b364ac244ff76b76';
        //活动行
        $appId  = 'wx76971b277dab5112';    
        $secret = 'b952499604337fe344d4652d62b2ad30';
        $code   = $this->_verifyJSONIsEmpty(HRequest::getParameter('code'), 'code');
        $url    = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appId . '&secret=' . $secret . '&js_code=' . $code . '&grant_type=authorization_code' ;
        $json = HRequest::getContents($url);
        $result = json_decode($json, true);
        
        HResponse::json(array('rs' => true, 'data' => $result));
    }

}

?>
