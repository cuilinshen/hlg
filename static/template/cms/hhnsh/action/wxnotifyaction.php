<?php 
	
	/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */

HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');
HClass::import(HResponse::getCurThemePath() . '.action.SmsAction');
	
/**
 * 微信支付通知的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class WxnotifyAction extends HhnshAction 
{

	private $_order;

	private $_sms;

	private $_notice;

	/**
     * 构造函数 
     * 
     * 初始化类里的变量 
     * 
     * @access public
     */
    public function __construct() 
    {  
    	$this->_order 	= HClass::quickLoadModel('order');
    	$this->_sms 	= new SmsAction();
    	$this->_notice 	= HClass::quickLoadModel('notice');
    }

	public function index() 
	{
		$postXml = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数  
		if (empty($postXml)) {  
		    return false;  
		}  
		
		$attr = $this->xmlToArray($postXml);  
		//HLog::write($attr, HLog::$L_WARN);
		$postSign = $attr['sign'];
		unset($attr['sign']);

		 /* 微信官方提醒：
         *  商户系统对于支付结果通知的内容一定要做【签名验证】,
         *  并校验返回的【订单金额是否与商户侧的订单金额】一致，
         *  防止数据泄漏导致出现“假通知”，造成资金损失。
         */
        ksort($attr); //对数据排序
        $attrStr 	= $this->ToUrlParams($attr);
        $userSign 	= strtoupper(md5($attr)); //重新生成签名 与 postSign比较


		$total_fee = $attr['total_fee'];  
		$open_id = $attr['openid'];  
		$out_trade_no = $attr['out_trade_no'];  
		$time = $attr['time_end']; 	
		$orderInfo 	= $this->_getOrderInfo($out_trade_no);

		if($attr['return_code'] == 'SUCCESS' && $postSign) {
			HLog::write($orderInfo, HLog::$L_WARN);
			/**
			 * 首先判断，订单是否已经更新为ok，因为微信会总共发送8次回调确认
             * 其次，订单已经为ok的，直接返回SUCCESS
             * 最后，订单没有为ok的，更新状态为ok，返回SUCCESS
			*/
			if($orderInfo['pay_status']) {
				$this->return_success();
			}else{
				$updateData 	= array(
					'pay_time' 	=> $attr['time_end'],
					'pay_status'=> 1,
					'status' 	=> 2
				);
				if(1 > $this->_order->editByWhere($updateData, '`id` =' . $orderInfo['id'])) {
					echo '微信支付失败';
					return;
				}	
				//支付成功 发送短信或者站内消息
				$this->_doOtherJobsAfterNotifySuccess($orderInfo);
				$this->return_success();
			}


		}else{
			echo '微信支付失败';
		}


		//其他操作
		// $this->_updateOrderPayStatus($attr);
	}

	/**
	 * 得到订单信息
	 * @param  [type] $out_trade_no [description]
	 * @return [type]               [description]
	 */
	private function _getOrderInfo($out_trade_no)
	{
		if(!$out_trade_no) {
			return;
		}
		$record = $this->_order->getRecordByWhere('`code` = \'' . $out_trade_no . '\'');

		return $record;
	}

	/**
	 * 更新订单支付状态
	 * @return [type] [description]
	 */
	private function _updateOrderPayStatus($attr)
	{	
		$order 	= HClass::quickLoadModel('order');
		if(!$attr['out_trade_no']) {
			return;
		}
		$record = $order->getRecordByWhere('`code` = \'' . $attr['out_trade_no'] . '\'');
		if(!$record) {
			return;
		}
		$data 	= array(
			'pay_time' 	=> $attr['time_end'],
			'code' 		=> $attr['out_trade_no']
		);
		$order->editByWhere($data, '`id` =' . $record['id']);
	}	

	//将xml格式转换成数组  
	 private function xmlToArray($xml) {  
    	//禁止引用外部xml实体   
	    libxml_disable_entity_loader(true);  
	    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);  
	    $val = json_decode(json_encode($xmlstring), true);  
	    
	    return $val;  
	 }  

	  /**
     * 将参数拼接为url: key=value&key=value
     * @param $params
     * @return string
     */
    public function ToUrlParams( $params ){
        $string = '';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.$value;
            }
            $string = implode("&",$array);
        }
        return $string;
    }

    /*
     * 给微信发送确认订单金额和签名正确，SUCCESS信息 -xzz0521
     */
    private function return_success()
    {
        $return['return_code'] = 'SUCCESS';
        $return['return_msg'] = 'OK';
        $xml_post = '<xml>
                    <return_code>'.$return['return_code'].'</return_code>
                    <return_msg>'.$return['return_msg'].'</return_msg>
                    </xml>';
        echo $xml_post;
        exit;
    }

    /**
     * 微信成功通知后执行的操作
     * @return [type] [description]
     */
    private function _doOtherJobsAfterNotifySuccess($orderInfo)
    {
    	$orderProduct = HClass::quickLoadModel('orderproduct');
    	$list 	= $orderProduct->getAllRows('`parent_id` = ' . $orderInfo['id']);
    	$companyMap = HArray::turnItemValueAsKey(
    		HClass::quickLoadModel('company')->getAllRowsByFields(
    		'`id`, `name`, `phone`, `userid`', 
    		HSqlHelper::whereInByListMap('id', 'company_id', $list)
    	), 'id');
    	foreach($list as $key => $item) {
    		$companyInfo = $companyMap[$item['company_id']];
    		//发送站内通知给商户
    		$description = '您有新的快乐上门订单，马上点击处理！';
    		$noticeData 	= array(
    			'name' => $companyInfo['name'],
    			'parent_id' => $companyInfo['userid'],
    			'type' 	=> 2,
    			'description' => $description,
    			'status' => '1',
    			'rel_id' => $orderInfo['id'],
    			'rel_model' => 'order',
    			'author' => $companyInfo['userid']
    		);	
    		HLog::write($noticeData, HLog::$L_WARN);
    		if(1 > $this->_notice->add($noticeData)) {
    			HLog::write('错误信息', HLog::$L_WARN);
    			HLog::write($noticeData, HLog::$L_WARN);
    		}
    		$msgData 	= array(
	    		'phone' =>  $companyInfo['phone'], //店家的电话
	    		'user_name' => $orderInfo['name'],
	    		'user_phone' => $orderInfo['phone']
    		);
    		//发送短信通知给商户
    		$this->_sms->sendMsgToShoper($msgData);
    	}
    }

}
?>