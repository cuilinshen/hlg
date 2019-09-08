<?php

/**
 * @version			$Id$
 * @create 			2012-10-10 20:10:06 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.fieldpopo, app.admin.action.AdminAction, model.fieldmodel');

/**
 * 模块字段的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since 			1.0.0
 */
class FieldAction extends AdminAction
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
        $this->_popo        = new FieldPopo();
        $this->_model       = new FieldModel($this->_popo);
    }

    /**
     * 添加字段配置 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @return void
     * @throws none
     */
    public function add()
    {
        if(false === $this->_model->add(HPopoHelper::getAddFieldsAndValues($this->_popo))) {
            HResponse::succeed('添加失败！');
        }
        $this->_writeFieldTpl(HRequest::getParameter('field_name')); 
        
        HResponse::succeed('添加成功！');
    }

    /**
     * 编辑动作 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function editview()
    {
        try {
            HVerify::isRecordId(HRequest::getParameter('id'));
            $record     = $this->_model->getRecordById(HRequest::getParameter('id'));
            if(empty($record)) {
                throw new HVerifyException('没有对应的记录！');
            }
            $this->_setResponseModelName();
            $this->_assignModelParents();
            $record['field_tpl']    = $this->_readFieldTpl($record['field_name']);
            
            HResponse::setAttribute('record', $record);
            HResponse::setAttribute('nextAction', 'edit');

            $this->_render($this->_popo->getActionView('editview'));
        } catch(HVerifyException $ex) {
            HResponse::succeed($ex->getMessage());
        }
    }

    /**
     * 编辑提示动作 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function edit()
    {
        try {
            HVerify::isRecordId(HRequest::getParameter('id'));
            HRequest::setParameter($this->_popo->modelEnName . '_id', HRequest::getParameter('id'));
            $record     = $this->_model->getRecordById(HRequest::getParameter('id'));
            if(empty($record)) {
                throw new HVerifyException('记录不存在，请确认！');
            }
            if(false === $this->_model->edit(HPopoHelper::getUpdateFieldsAndValues($this->_popo))) {
                HResponse::succeed('更新失败！');
            }
            $this->_writeFieldTpl(HRequest::getParameter('field_name'));

            HResponse::alertAndJump('更新成功！', HResponse::url('admin/' . $this->_popo->modelEnName));
        } catch(HVerifyException $ex) {
            HResponse::succeed($ex->getMessage());
        }
    }

    /**
     * 写入字段模板内容 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  String $field 字段名
     * @return Boolean 
     * @throws none
     */
    protected function _writeFieldTpl($field)
    {
        try {
            $filePath   = ROOT_DIR . DS .
                          HObject::GC('FIELD_DIR') . DS .
                          $field . '.tpl';
            //默认替换已经存在文件
            HFile::create(
                $filePath, 
                HString::cleanSlash(
                    HString::decodeHtml(HRequest::getParameter('field_tpl'))
                ),
                true
            );
            return true;
        } catch (HIOException $ex) {
            return false;
        }
    }

    /**
     * 得到对应字段的网页模板 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  String $field 当前需要操作的字段
     * @return String 
     * @throws none
     */
    protected function _readFieldTpl($field)
    {
        try {
            return HFile::read(
                 ROOT_DIR . DS . HObject::GC('FIELD_DIR') . DS . $field . '.tpl'
            );
        } catch(HIOException $ex) {
            return '';
        }
    }

}

?>
