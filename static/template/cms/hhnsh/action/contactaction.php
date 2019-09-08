<?php 

/**
 * @version $Id$
 * @create 2013-8-6 10:20:09 By xjiujiu
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import(HResponse::getCurThemePath() . '.action.articleaction');

/**
 * 联系我信息类
 * 
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @package app.cms.action
 * @since 1.0.0
 */
class ContactAction extends ArticleAction
{

    /**
     * 构造函数
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->_identifier    = 'contact-us';
        $this->_popo->modelZhName   = '联系我们';
    }
    
    /**
     * {@inheritDoc}
     */
    public function index()
    {
        $record     = $this->_model->getRecordByIdentifier($this->_identifier);
        $this->_setDetailInfo($record);
        
        $this->_commAssign();
        
        $this->_render('detail');
    }

    /**
     * 接收信息
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function asend()
    {
        HVerify::isAjax();
        HVerify::isEmpty(HRequest::getParameter('name', '称呼'));
        HVerify::isEmail(HRequest::getParameter('email', '邮箱'));
        HVerify::isEmpty(HRequest::getParameter('content', '信息内容'));
        $data   = array(
            'name' => HRequest::getParameter('name'),
            'email' => HRequest::getParameter('email'),
            'content' => HRequest::getParameter('content'),
            'ip' => HRequest::getClientIp()
        );
        $message    = HClass::quickLoadModel('message');
        if(1 > $message->add($data)) {
            throw new HVerifyException('留言出现了一个问题，请您稍后再试！');
        }
        HResponse::json(array('rs' => true));
    }

    /**
     * 基本信息
     * @return [type] [description]
     */
    public function info()
    {
        
        $this->_render('user/contact-info');
    }

    /**
     * 数据提交
     * @return [type] [description]
     */
    public function post()
    {
        
    }
}

?>
