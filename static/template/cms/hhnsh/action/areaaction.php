<?php

/**
 * @version $Id$
 * @create 2014/3/24 21:40:39 By luodao
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('config.popo.areapopo, model.areamodel');
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
class AreaAction extends HhnshAction
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
        $this->_popo    = new AreaPopo();
        $this->_model   = new AreaModel($this->_popo);
    }

    /**
     * 首页
     * @return [type] [description]
     */
    public function index()
    {
        $where = '`level` = 2 AND `first` IS NOT NULL';
        $list = $this->_model->getAllRowsByFields('`id`, `name`, `shortname`, `first`, `lat`, `lng`', $where);
        $letterMap = array();
        foreach(range('A', 'Z') as $value) {
            $letterMap[$value] = array();
        }
        foreach($list as $key => $item) {
            if(array_key_exists($item['first'], $letterMap)) {
                array_push($letterMap[$item['first']], $item);
            }else{
                $letterMap[$item['first']][] = $item;
            }
        }

        HResponse::json(array('rs' => true, 'data' => $letterMap));
    }
    
    /**
     * 搜索
     * @return [type] [description]
     */
    public function search()
    {
        $keyword = HRequest::getParameter('keyword');
        $where = '`level` = 2 AND `first` IS NOT NULL';
        if($keyword) {
            $where .= ' AND `name` LIKE \'%' . $keyword . '%\'';
        }
        $list = $this->_model->getAllRowsByFields('`id`, `name`, `shortname`, `first`, `lat`, `lng`', $where);

        HResponse::json(array('rs' => true, 'data' => $list));
    }


}
?>
