<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.menupopo, model.menumodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 菜单的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class MenuAction extends HhnshAction 
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
        $this->_popo        = new MenuPopo();
        $this->_model       = new MenuModel($this->_popo);
    }

    /**
     * 得到菜单列表
     * @return [type] [description]
     */
    public function index()
    {
        //一行的菜单个数
        $line       = HRequest::getParameter('line') ? HRequest::getParameter('line') : 5;
        $row        = HRequest::getParameter('row') ? HRequest::getParameter('row') : 2;
        $swipe      = HRequest::getParameter('swipe') ? HRequest::getParameter('swipe') : 2;
        $this->_popo->setFieldAttribute('id', 'is_order', null);
        $this->_popo->setFieldAttribute('sort_num', 'is_order', asc);
        $list       = $this->_model->getAllRows('1=1');
        $index      = 0;
        $result     = array();
        $lineIndex  = 0;
        $rowIndex   = 0;
        $swipeIndex = 0;
        while ($index < count($list)) {
            $item = $list[$index];
            if($lineIndex == 5) {
                $rowIndex ++;
                $lineIndex  = 0;
                if($rowIndex == 2) {
                    $swipeIndex ++;
                    $lineIndex = 0;
                    $lineIndex = 0;
                }
            }
            $item['url']    = HString::decodeHtml($item['url']);
            $result[$swipeIndex][$rowIndex][$lineIndex] = $item;
            $lineIndex ++;
            $index ++;
        }

        HResponse::json(array('rs' => true, 'data' => $result));
    }

}