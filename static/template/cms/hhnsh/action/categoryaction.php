<?php

/**
 * @version			$Id$
 * @create 			2013-06-17 01:06:41 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.categorypopo, model.categorymodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 信息分类的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.site.action
 * @since 			1.0.0
 */
class CategoryAction extends HhnshAction
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
        $this->_popo    = new CategoryPopo();
        $this->_model   = new CategoryModel($this->_popo);
        $this->_user    = HClass::quickLoadModel('user');
    }

    /**
     * 根据标识得到子分类列表
     * @return [type] [description]
     */
    public function getsublistbyIdentifier()
    {
        $identifier = HRequest::getParameter('identifier');
        $record = $this->_model->getRecordByWhere('`identifier` = \'' . $identifier . '\'');
        $list   = $this->_model->getAllRowsByFields('`id`, `name`', '`parent_id` = ' . $record['id']);
        array_unshift($list, array('id' => 0, 'name' => '请选择活动分类'));

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 得到用户ID得到对应的活动分类
     * @return [type] [description]
     */
    public function agetproductcatlistbyuserid()
    {
        $userId     = HRequest::getParameter('user_id');
        $record     = $this->_model->getRecordByWhere('`identifier` = \'product-cat\'');
        $subList    = $this->_model->getSubCategory($record['id'], true);
        $hasCheckedList     = $this->_category->getAllRowsByFields('`id`, `name`, `image_path`', HSqlHelper::whereInByListMap('parent_id', 'id', $subList) . ' AND `total_use` = 1');
        foreach ($hasCheckedList as $key => &$value) {
            $value['isadTag'] = 1;
        }
        if($userId) {
            $linkeddata = HClass::quickLoadModel('linkeddata');
            $linkeddata->setRelItemModel('user', 'category');
            $linkeddataList = $linkeddata->getAllRows('`item_id` = ' . $userId);
            $userCheckList  = $this->_model->getAllRowsByFields('`id`, `name`, `image_path`', HSqlHelper::whereInByListMap('id', 'rel_id', $linkeddataList));
            $linkeddataMap  = HArray::turnItemValueAsKey($linkeddataList, 'rel_id');
            foreach($userCheckList as $key => &$item) {
                $extend = $linkeddataMap[$item['id']]['extend'];
                $extendArr = explode(',', $extend);
                $item['ref'] = $extendArr[0];
                $item['index'] = $extendArr[1];
            }
            $hasCheckedList = array_merge($hasCheckedList, $userCheckList);
        }
        $hasCheckedWhere = HSqlHelper::whereNotInByListMap('id', 'id', $hasCheckedList);
        $list       = $this->_model->getAllRowsByFields(
            '`id`, `name`, `image_path`', 
            '`parent_id` = ' . $record['id'] . ' AND `identifier` != \'jingxuan-cat\' AND ' . $hasCheckedWhere
        );
        foreach($list as $key => &$item) {
            $item['image_path'] = HString::formatImage($item['image_path']);
            $item['list'] = $this->_model->getAllRowsByFields('`id`, `name`', '`parent_id` = ' . $item['id'] . ' AND ' . $hasCheckedWhere, true);
        }

        HResponse::json(array('rs' => true, 'data' => array('hascheckedlist' => $hasCheckedList, 'alllist' => $list)));
    }
    
    /**
     * 格式化数据
     * @return [type] [description]
     */
    private function _formatUserCheckedList()
    {

    }

    /**
     * 保存用户的分类
     * @return [type] [description]
     */
    public function saveusercat()
    {
        $ids = HRequest::getParameter('ids');
        $refs = HRequest::getParameter('refs');
        $indexs = HRequest::getParameter('indexs');
        $userId         = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $openId         = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'OpenID');
        $userInfo       = $this->_verifyUserOpenId($openId, $userId);
        if(empty($ids)) {
            HResponse::json(array('rs' => false, 'message' => '至少选择一个分类'));
        }
        $idArr = explode(',', $ids);
        $refArr = explode(',', $refs);
        $indexArr = explode(',', $indexs);
        $linkeddata = HClass::quickLoadModel('linkeddata');
        $linkeddata->setRelItemModel('user', 'category');
        $linkeddataList = $linkeddata->getAllRows('`item_id` = ' . $userId);
        $data['item_id'] = $userId;
        if(empty($linkeddataList)) {
            for($key = 0; $key < count($idArr); $key ++) {
                $data['rel_id'] = $idArr[$key];
                $data['extend'] = $refArr[$key] . ',' . $indexArr[$key];
                if(1 > $linkeddata->add($data)) {
                    HResponse::json(array('rs' => false, 'message' => '操作失败'));
                }
            }
        }else{
            $hasLinkedDataMap = HArray::turnItemValueAsKey($linkeddataList, 'rel_id'); //已经存在的分类          
            $idArr = array_filter($idArr);
            //循环新的数组，把没存在的都加上
            foreach($idArr as $id) {
                if(!$hasLinkedDataMap[$id]) {
                    if(1 > $linkeddata->add(array('item_id' => $userId, 'rel_id' => $id))) {
                        HResponse::json(array('rs' => false, 'message' => '操作失败'));
                    }
                } 
            }
            //循环旧的数据，把没存在的都删除
            foreach($hasLinkedDataMap as $key => $item) {
                if(!in_array($item['rel_id'], $idArr)) {
                    if(1 > $linkeddata->deleteByWhere('`id` = ' . $item['id'])) {
                        HResponse::json(array('rs' => false, 'message' => '操作失败-2'));   
                    }
                }
            }
        }

        HResponse::json(array('rs' => true));
    }

}

?>
