<?php

/**
 * @version			$Id$
 * @create 			2014-06-09 22:06:49 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.messagepopo, app.admin.action.AdminAction, model.messagemodel');

/**
 * 访客留言的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class MessageAction extends AdminAction
{

    /**
     * 构造函数 
     * 
     * 初始化类变量 
     * 
     * @access public
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_popo        = new MessagePopo();
        $this->_model       = new MessageModel($this->_popo);
    }

    /**
     * 列表额外任务
     * 
     * {@inheritdoc}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     */
    protected function _otherJobsAfterList()
    {
        parent::_otherJobsAfterList();
        HResponse::registerFormatMap('parent_id', 'name', HArray::turnItemValueAsKey($this->_getTypeList(), 'id'));
        HResponse::registerFormatMap('status', 'name', HArray::turnItemValueAsKey(MessagePopo::$_statusMap, 'id'));
        HResponse::registerFormatMap('product_id', 'name', HArray::turnItemValueAsKey($this->_getProductList(), 'id'));
    }

     /**
     * 添加视图其它工作
     * 
     * {@inheritdoc}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     */
    protected function _otherJobsAfterAddView()
    {
        parent::_otherJobsAfterAddView();
        HResponse::setAttribute('parent_id_list', $this->_getTypeList());
        HResponse::setAttribute('status_list', MessagePopo::$_statusMap);
        HResponse::setAttribute('product_id_list', $this->_getProductList());
    }

    /**
     * 编辑视图工作
     * 
     * {@inheritdoc}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     */
    protected function _otherJobsAfterEditView($record)
    {
        parent::_otherJobsAfterEditView($record);
        HResponse::setAttribute('parent_id_list', $this->_getTypeList());
        HResponse::setAttribute('status_list', MessagePopo::$_statusMap);
        $this->_getReplyList($record['id']);
        HResponse::setAttribute('product_id_list', $this->_getProductList());
    }

    private function _getTypeList($list)
    {
        $where = '1=1';
        if($list) {
            $where = HSqlHelper::whereInByListMap('id', 'parent_id', $list);
        }
        
        return HClass::quickLoadModel('user')->getAllRowsByFields('`id`, `name`', $where);
    }

    /**
     * 得到回复数据
     * @return [type] [description]
     */
    private function _getReplyList($id)
    {
        $where = '`item_id` = ' . $id;
        $list = HClass::quickLoadModel('reply')->getAllRows($where);

        HResponse::setAttribute('replyList', $list);
    }

    /**
     * 得到产品列表
     * @return [type] [description]
     */
    private function _getProductList()
    {
        return HClass::quickLoadModel('product')->getAllRowsByFields('`id`, `name`');
    }

}   

?>
