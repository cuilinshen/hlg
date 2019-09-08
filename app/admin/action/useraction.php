<?php

/**
 * @version			$Id$
 * @create 			2012-04-25 12:04:22 By xjiujiu
 * @package 	 	app.admin
 * @subpackage 	 	action
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 * HongJuZi Framework
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.userpopo, app.admin.action.AdminAction, model.usermodel');

/**
 * 用户列表的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class UserAction extends AdminAction
{   
    /**
     * 前驱方法
     * @return [type] [description]
     */
    public function beforeAction()
    {
        $this->_hverifyUserActor();
    }

    /**
     * 验证用户操作权限
     * @return [type] [description]
     */
    private function _hverifyUserActor()
    {
        $actor  = $this->_getUserActor();
    }

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
        $this->_popo        = new UserPopo();
        $this->_model       = new UserModel($this->_popo);
    }

     /**
     * 列表后驱方法
    */
    public function _otherJobsAfterList()
    {
        parent::_otherJobsAfterList();
        $list   = HResponse::getAttribute('list');
        HResponse::registerFormatMap(
            'sex',
            'name',
            UserPopo::$_sexMap
        );
        HResponse::registerFormatMap(
            'is_renzheng',
            'name',
            UserPopo::$_isRenZhengMap
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
        HResponse::setAttribute('sex_list', UserPopo::$_sexMap);
        HResponse::setAttribute('is_renzheng_list', UserPopo::$_isRenZhengMap);
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
        HResponse::setAttribute('sex_list', UserPopo::$_sexMap);
        $record     = HResponse::getAttribute('record');
        HResponse::setAttribute('is_renzheng_list', UserPopo::$_isRenZhengMap);
    }

    /**
     * 全称为CheckUserName即检测当前的用户名 
     * 
     * 当用户名存在时给出错误的提示 
     * 
     * @access public
     */
    public function isunused()
    {
        HVerify::isAjax();
        HVerify::isEmpty(HRequest::getParameter('name'), '用户名');
        if(true === $this->_model->isUserNameUsed($userName)) {
            throw new HVerifyException('用户名已经使用！');
        }
        HResponse::json(array('rs' => true, 'message' => '可以使用！'));
    }

    /**
     * 执行模块的添加 
     * 
     * @access public
     */
    public function add()
    {
        HVerify::isStrLen(HRequest::getParameter('password'), '登录密码', 6, 20);
        $this->_formatFieldData();
        $this->_verifyLoginNameAndEmail();
        $this->_add();

        HResponse::succeed('添加成功！');
    }

    /**
     * 检测用户名或邮箱是否已经使用
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     * @param $id 用户编号，默认为NULL表示为新加
     * @throws HVerifyException 验证异常
     */
    private function _verifyLoginNameAndEmail($id = null)
    {
        $where  = '(`name` = \'' . HRequest::getParameter('name') 
            . '\' OR `email` = \'' . HRequest::getParameter('email') . '\')';
        if($id) {
            $where  .= ' AND `id` != ' . $id;
        }
        $record     = $this->_model->getRecordByWhere($where);
        if(!$record) {
            return;
        }
        if($record['name'] == HRequest::getParameter('name')) {
            throw new HVerifyException('用户名已经使用！');
        }
        throw new HVerifyException('邮箱已经使用！');
    }

    /**
     * 格式化字段数据
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _formatFieldData($isEdit = false)
    {
        if(HRequest::getParameter('password')) {
            HRequest::setParameter('password', md5(HRequest::getParameter('password', false)));
        }
        if(HRequest::getParameter('login_time')) {
            HRequest::setParameter('login_time', strtotime(HRequest::getParameter('login_time')));
        }
    }

    /**
     * 编辑提示动作 
     * 
     * @access public
     */
    public function edit()
    {
        HVerify::isRecordId(HRequest::getParameter('id'), '用户ID');
        if(HRequest::getParameter('password')) {
            HVerify::isStrLen(HRequest::getParameter('password'), '登录密码', 6, 20);
        } else {
            HRequest::deleteParameter('password');
        }
        $this->_formatFieldData(true);
        $this->_verifyLoginNameAndEmail(HRequest::getParameter('id'));
        $this->_edit();

        HResponse::succeed('更新成功！', HResponse::url($this->_popo->modelEnName, '', 'admin'));
    }

    /**
     * 删除动作 
     * 
     * @access public
     * @exception HRequestException 请求异常
     */
    public function delete()
    {
        $recordIds  = HRequest::getParameter('id');
        if(!is_array($recordIds)) {
            $recordIds  = array($recordIds);
        }
        if(in_array(HSession::getAttribute('id', 'user'), $recordIds)) {
            throw new HRequestException('删除用户中不能包含自己！');
        }
        $this->_delete($recordIds);
        HResponse::succeed(
            '删除成功！', 
            HResponse::url($this->_popo->modelEnName, '', 'admin')
        );
    }

}

?>
