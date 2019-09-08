<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 短信的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class SmsAction extends HhnshAction 
{

    /**
     * 应用appkey
     * @var string
     */
    private $_appkey = 'c32dd2348c145466947f264849b14658';

    /**
     * 登录短信发送tplid
     * 【快乐上门】您的验证码是#code#。如非本人操作，请忽略本短信
     * @var string
     */
    private $_loginTpl = '90603';

    /**
     * 商家收到订单时短信提醒模板
     * 【快乐上门】您有新的订单，#name#，#phone#，订单已支付，请商家马上处理。
     * @var string
     */
    private $_shopTpl = '47535';

    /**
     * 骑手收到订单时的短信提醒模板
     * 【快乐上门】您有新的跑腿订单，#name#，#phone#，商家已确认，请小哥及时处理。
     * @var string
     */
    private $_qishouTpl = '47536'; 

    /**
     * 接口请求地址
     * @var string
     */
    private $_sendUrl = 'http://v.juhe.cn/sms/send';

    /**
     * 短信发送对象
     * @var [type]
     */
    private $_smsSend;

    /**
     * 短信总量发送对象
     * @var [type]
     */
    private $_smsTotal;

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
        $this->_smsSend     = HClass::quickLoadModel('smssend');
        $this->_smsTotal    = HClass::quickLoadModel('smstotal');
    }

    /**
     * 发送验证码
     * @return [type] [description]
     */
    public function sendvcode()
    {
        header('content-type:text/html;charset=utf-8');
        $phone  = HRequest::getParameter('phone');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'));
        $this->_verifyJSONIsPhone($phone);
        $vcode   = $this->_getRandomVcode();

        $smsConf = array(
            'key'   => $this->_appkey, 
            'mobile'    => $phone, 
            'tpl_id'    => $this->_loginTpl, 
            'tpl_value' =>'#code#=' . $vcode
        );
        //做发送次数验证
        $this->_verifyIsNeedSendSms($openId, 1);
        $content = $this->juhecurl($this->_sendUrl, $smsConf,1); //请求发送短信
         
        if($content){
            $result = json_decode($content,true);
            $error_code = $result['error_code'];
            if($error_code == 0){
                //状态为0，说明短信发送成功
                //执行插入操作
                $smsSendData    = array(
                    'open_id' => $openId,
                    'vcode' => $vcode,
                    'tpl_id' => $this->_loginTpl,
                    'content' => $content,
                    'type' => 1,
                    'phone' => $phone
                );
                $this->_addSmsSendAndTotalData($smsSendData);
                HResponse::json(array('rs' => true));
            }else{
                //状态非0，说明失败
                $msg = $result['reason'];
                HResponse::json(array('rs' => false, 'message' => "短信发送失败(".$error_code.")：".$msg));
                exit();
            }
        }else{
            //返回内容异常，以下可根据业务逻辑自行修改
            HResponse::json(array('rs' => false, 'message' => "请求发送短信失败"));
            exit();
        }

    }

    /**
     * 发送短信给店家
     * @return [type] [description]
     */
    public function sendMsgToShoper($data)
    {
        header('content-type:text/html;charset=utf-8');
        $phone  = $data['phone'];
        $this->_verifyJSONIsPhone($phone);
        $tplValue = '#name#=' . $data['user_name'] . '&#phone#=' . $data['user_phone'];
        $smsConf = array(
            'key'   => $this->_appkey, 
            'mobile'    => $phone, 
            'tpl_id'    => $this->_shopTpl, 
            'tpl_value' => $tplValue
        );
        $content = $this->juhecurl($this->_sendUrl, $smsConf,1); //请求发送短信
        $smsSendData    = array(
            'name' => $data['user_name'],
            'tpl_id' => $this->_shopTpl,
            'content' => $tplValue,
            'phone' => $phone
        );
        if($content){
            $result = json_decode($content,true);
            $error_code = $result['error_code'];
            if($error_code == 0){
                //状态为0，说明短信发送成功
                //执行插入操作
                $smsSendData['status'] = 1;
                $this->_addSmsMessage($smsSendData);
                HResponse::json(array('rs' => true));
            }else{
                //状态非0，说明失败
                $msg = $result['reason'];
                $smsSendData['content'] = "短信发送失败(".$error_code.")：".$msg;
                $smsSendData['status'] = 2;
                $this->_addSmsMessage($smsSendData);
                HResponse::json(array('rs' => false, 'message' => "短信发送失败(".$error_code.")：".$msg));
            }
        }else{
            //返回内容异常，以下可根据业务逻辑自行修改
            $smsSendData['content'] = "短信发送失败";
            $smsSendData['status'] = 2;
            $this->_addSmsMessage($smsSendData);
            HResponse::json(array('rs' => false, 'message' => "请求发送短信失败"));
            exit();
        }

    }   

    /**
     * 发送短信给骑手
     * @return [type] [description]
     */
    public function sendMsgToQishou()
    {
         header('content-type:text/html;charset=utf-8');
        $phone  = $data['phone'];
        $this->_verifyJSONIsPhone($phone);
        $tplValue = '#name#=' . $data['user_name'] . '&#phone#=' . $data['user_phone'];
        $smsConf = array(
            'key'   => $this->_appkey, 
            'mobile'    => $phone, 
            'tpl_id'    => $this->_qishouTpl, 
            'tpl_value' => $tplValue
        );
        $content = $this->juhecurl($this->_sendUrl, $smsConf,1); //请求发送短信
        $smsSendData    = array(
            'name' => $data['user_name'],
            'tpl_id' => $this->_qishouTpl,
            'content' => $tplValue,
            'phone' => $phone
        );
        if($content){
            $result = json_decode($content,true);
            $error_code = $result['error_code'];
            if($error_code == 0){
                //状态为0，说明短信发送成功
                //执行插入操作
                $smsSendData['status'] = 1;
                $this->_addSmsMessage($smsSendData);
                HResponse::json(array('rs' => true));
            }else{
                //状态非0，说明失败
                $msg = $result['reason'];
                $smsSendData['content'] = "短信发送失败(".$error_code.")：".$msg;
                $smsSendData['status'] = 2;
                $this->_addSmsMessage($smsSendData);
                HResponse::json(array('rs' => false, 'message' => "短信发送失败(".$error_code.")：".$msg));
                exit();
            }
        }else{
            //返回内容异常，以下可根据业务逻辑自行修改
            $smsSendData['content'] = "短信发送失败";
            $smsSendData['status'] = 2;
            $this->_addSmsMessage($smsSendData);
            HResponse::json(array('rs' => false, 'message' => "请求发送短信失败"));
            exit();
        }
    }

    /**
     * 验证今日发送的次数
     * @param  [type] $openId [description]
     * @param  [type] $type   [description]
     * @return [type]         [description]
     */
    private function _verifyIsNeedSendSms($openId, $type)
    {
        $date = date('Y-m-d', time());
        $where = '`open_id` = \'' . $openId . '\' AND `type` = ' . $type . ' AND `date` = \'' . $date . '\'';
        $record     = $this->_smsTotal->getRecordByWhere($where);
        if(!$record) {
            return;
        }
        //超过三次就限制
        if($record['total_num'] > 2) {
            HResponse::json(array('rs' => false, 'message' => '今日次数已满'));
            exit();
        }
    }

    /**
     * 添加短信发送数据
     * @param [type] $smsSendData [description]
     */
    private function _addSmsSendAndTotalData($smsSendData)
    {
        $smsSendId = $this->_smsSend->add($smsSendData);
        if(!$smsSendId) {
            HResponse::json(array('rs' => false, 'message' => '短信发送数据添加失败'));
        }
        $date = date('Y-m-d', time());
        $where  = '`open_id` = \'' . $smsSendData['open_id'] . '\' AND `type` = ' . $smsSendData['type'] . ' AND `date` = \'' . $date . '\'';
        $smsTotalRecord     = $this->_smsTotal->getRecordByWhere($where);
        if($smsTotalRecord) {
            $smsTotalData = array(
                'vcode'     => $smsSendData['vcode'],
                'total_num' => $smsTotalRecord['total_num'] + 1,
                'sms_send_id'=> $smsSendId
            );
            if(1 > $this->_smsTotal->editByWhere($smsTotalData, '`id` = ' . $smsTotalRecord['id'])) {
                HResponse::json(array('rs' => false, 'message' => '短信发送量更新失败'));
            }
        }else{
            $smsTotalData = array(
                'vcode'     => $smsSendData['vcode'],
                'total_num' => 1,
                'sms_send_id'=> $smsSendId,
                'type'      => $smsSendData['type'],
                'date'      => $date,
                'open_id'   => $smsSendData['open_id']
            );
            if(1 > $this->_smsTotal->add($smsTotalData)) {
                HResponse::json(array('rs' => false, 'message' => '短信发送量更新失败'));
            }
        }
    }

    /**
     * 添加短信发送记录
     * @param [type] $data [description]
     */
    private function _addSmsMessage($data)
    {
        $smsmessage = HClass::quickLoadModel('smsmessage');
        if(1 > $smsmessage->add($data)) {
            HResponse::json(array('rs' => false, 'message' => '添加短信发送记录失败'));
        }
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    private function juhecurl($url,$params=false,$ispost=0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }


    /**
     * 得到随机验证码
     * @return [type] [description]
     */
    private function _getRandomVcode()
    {
        return rand(1000, 9999);
    }

}