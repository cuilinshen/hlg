<?php 

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import(HResponse::getCurThemePath() . '.action.WxPayAction');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 用户支付的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class PayAction extends HhnshAction 
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
        //活动汇
    	// $this->_appId 	= 'wx3aff9f8f931e0a82';
    	// $this->_mchId 	= '1509586771'; 
    	// $this->_key 	= 'q01Ui3EDZMhYbksPudB5OKcB0FDYZoHZ';
        //活动行
        $this->_appId   = 'wx76971b277dab5112';
        $this->_mchId   = '1521132851'; 
        $this->_key     = 'Ssrs1XinxZSrK0GolecHs9w4qLACIslk';
    }

    //小程序appid
    private $_appId;

    //商户号
    private $_mchId;

    //签证的key值
    private $_key;

    /**
     * 开始支付
     * @return [type] [description]
     */
    public function index()
    {

		//用户openid
		$openid= HRequest::getParameter('open_id');  
		
		 //商户订单号
		$out_trade_no = HRequest::getParameter('out_trade_no');  
		
		//总金额
		$total_fee = HRequest::getParameter('fee');  

		if(empty($total_fee)) //押金  
		{  
		    $body = $this->_getBodyData();  
		    $total_fee = floatval(99*100);  
		}  
		 else {  
		     $body = $this->_getBodyData();  
		     $total_fee = floatval($total_fee*100);  
		 }  
		$weixinpay 	= new WxPayAction(
			$this->_appId,
			$openid,
			$this->_mchId,
			$this->_key,
			$out_trade_no,
			$body,
			$total_fee
		);  
		$return		= $weixinpay->pay();  
		
	    HResponse::json(array('rs' => true, 'data' => $return));
    }

    /**
     * 得到Body描述内容
     * @return [type] [description]
     */
    private function _getBodyData()
    {
        return '活动参与费';
    }

}

?>