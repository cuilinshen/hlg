<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import(HResponse::getCurThemePath() . '.action.OrderAction');

/**
 * 店家订单的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class DianjiaorderAction extends OrderAction 
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
        
    }

    /**
     * 店家接单
     * @var string
     */
    private $_dianjiaJieDan = '`status` in (2, 3)';

    /**
     * 骑手接单
     * @var string
     */
    private $_qiShouJieDan = '`status` in (4)';

    /**
     * 用户确认条件
     * @var string
     */
    private $_queren   = ' `status` in (5, 11)';

    /**
     * 用户评价
     * @var string
     */
    private $_comment   = ' `status` in (10)';

    /**
     * 退款
     * @var string
     */
    private $_tuikuan   = ' `status` in (6, 8, 9)';

     /**
     * 订单列表
     * @return [type] [description]
     */
    public function index()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $this->_verifyUserOrderRight();
        $page       = HRequest::getParameter('page') ?　HRequest::getParameter('page') : 0;
        $companyList= $this->_company->getAllRowsByFields('`id`, `name`', '`userid` = ' . $userId); 
        $where      = HSqlHelper::whereInByListMap('company_id', 'id', $companyList);
        $statusMap  = array(
            'dianjiajiedan' => $where . ' AND ' . $this->_dianjiaJieDan,
            'qishoujiedan' => $where . ' AND ' . $this->_qiShouJieDan,
            'queren' => $where . ' AND ' . $this->_queren,
            'comment' => $where . ' AND ' . $this->_comment,
            'tuikuan' => $where . ' AND ' . $this->_tuikuan
        );
        $result = array();
        foreach($statusMap as $key => $value) {
            $result[$key]   = $this->_getOrderList($value, $page);
        }

        HResponse::json(array('rs' => true, 'data' => $result));
    }
        

}