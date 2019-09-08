<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.qrcodepopo, model.qrcodemodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 二维码的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class QrcodeAction extends HhnshAction 
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
        $this->_popo        = new QrcodePopo();
        $this->_model       = new QrcodeModel($this->_popo);
    	$this->_appId 		= 'wxb52fd55f08de0933';
    	$this->_secret 		= 'ffb10586ea99bc254f112c319653f899';
    }

    /**
     * AppId
     * @var [type]
     */
    private $_appId;

    /**
     * secret秘钥
     * @var [type]
     */
    private $_secret;

    /**
     * 得到accessToken值
     * @return [type] [description]
     */
    private  function _getAccessToken()
    {
    	$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->_appId . '&secret=' . $this->_secret;
        $json = HRequest::getContents($url);
        $result = json_decode($json, true);
        $accessToken = $result['access_token'];
        if(!$accessToken) {
        	HResponse::json(array('rs' => false, 'message' => '权限获取失败'));
        }

        return $accessToken;
    }


    /**
     * 接口C：适用于需要的码数量较少的业务场景
     * @return [type] [description]
     */
    public function createWxaQrcode($path, $savePath, $width = 430)
    {
        $accessToken = $this->_getAccessToken();
        $postUrl = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=' . $accessToken;
        $post_data='{"path":"'.$path.'","width":'.$width.'}';
        $result=$this->_postData($postUrl,$post_data);
        $filepath = ROOT_DIR . $savePath;
        $result = file_put_contents($filepath, $result);
        if(!$result) {
        	HResponse::json(array('rs' => false, 'message' => '二维码生成失败'));
        }
        
        return $savePath;
    }

    /**
     * post请求数据
     * @param  [type] $url  [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function _postData($url, $data){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }else{
             
            return $tmpInfo;
        }
    }


}