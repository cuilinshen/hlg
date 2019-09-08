<?php

/**
 * @version			$Id$
 * @create 			2018-04-23 22:04:21 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.replypopo, app.admin.action.AdminAction, model.replymodel');

/**
 * 评论回复的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class ReplyAction extends AdminAction
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
        $this->_popo        = new ReplyPopo();
        $this->_model       = new ReplyModel($this->_popo);
    }

      /**
     * 列表后驱方法
    */
    public function _otherJobsAfterList()
    {
        parent::_otherJobsAfterList();
        $list   = HResponse::getAttribute('list');
        HResponse::registerFormatMap(
            'status',
            'name',
            HArray::turnItemValueAsKey(ReplyPopo::$_statusMap, 'id')
        );  
        HResponse::registerFormatMap(
            'item_id',
            'name',
            HArray::turnItemValueAsKey($this->_getMessageList(), 'id')
        );  
        HResponse::registerFormatMap(
            'user_id',
            'name',
            HArray::turnItemValueAsKey($this->_getUserList(), 'id')
        );  
    }

     /**
     * 添加视图后驱
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _otherJobsAfterAddView() 
    { 
        parent::_otherJobsAfterAddView();
        HResponse::setAttribute('status_list', ReplyPopo::$_statusMap);
        HResponse::setAttribute('item_id_list', $this->_getMessageList());
        HResponse::setAttribute('user_id_list', $this->_getUserList());
    }

    /**
     * 视频详细页后驱
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _otherJobsAfterEditView($record = null) 
    { 
        parent::_otherJobsAfterEditView();

        HResponse::setAttribute('status_list', ReplyPopo::$_statusMap);        
        HResponse::setAttribute('item_id_list', $this->_getMessageList());
        HResponse::setAttribute('user_id_list', $this->_getUserList());
    }

    /**
     * 得到消息列表
     * @return [type] [description]
     */
    private function _getMessageList()
    {
        return HClass::quickLoadModel('message')->getAllRows();
    }

    /**
     * 得到用户列表
     * @return [type] [description]
     */
    private function _getUserList()
    {
        return HClass::quickLoadModel('user')->getAllRows();
    }

    /**
     * 追加回复内容
     * @return [type] [description]
     */
    public function addpost()
    {
        $content = HRequest::getParameter('content');
        $id      = HRequest::getParameter('id');
        HVerify::isEmpty($id, '编号');
        HVerify::isEmpty($content, '回复内容');
        $data   = array(
            'content' => $content,
            'name' => '管理员',
            'item_id' => $id,
            'parent_id' => HSession::getAttribute('id', 'user')
        );
        $id = $this->_model->add($data);
        if(1 > $id) {
            HResponse::json(array('rs' => false, 'message' => '操作错误'));
        }
        $record = $this->_model->getRecordById($id);

        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 异步删除数据
     * @return [type] [description]
     */
    public function del() 
    {
        $id = HRequest::getParameter('id');
        HVerify::isEmpty($id, '编号');
        if(1 > $this->_model->deleteByWhere('`id` = ' . $id)) {
            HResponse::json(array('rs' => false, 'message' => '操作错误'));
        }

        HResponse::json(array('rs' => true, 'message' => '操作成功'));
    }

}

?>
