<?php

/**
 * @version			$Id$
 * @create 			2012-10-10 20:10:38 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.datamodelpopo, app.admin.action.AdminAction, model.datamodelmodel');

/**
 * 数据模型的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since 			1.0.0
 */
class DatamodelAction extends AdminAction
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
        $this->_popo        = new DatamodelPopo();
        $this->_model       = new DatamodelModel($this->_popo);
    }

    /**
     * 更新数据模型的字段内容 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @return void
     * @throws none
     */
    public function updatefields()
    {
        try {
            HVerify::isAjax();
            HVerify::isRecordId(HRequest::getParameter('id'));
            HVerify::isEmpty(HRequest::getParameter('has_fields'));
            $record     = $this->_model->getRecordById(HRequest::getParameter('id'));
            if(empty($record)) {
                throw new HVerifyException('对应数据模型不存在！');
            }
            if(!$this->_model->updatefields(
                HRequest::getParameter('id'),
                HRequest::getParameter('has_fields')
            )) {
                throw new HRequestException('保存数据模型失败！');
            }
            HResponse::json(
                array('msg' => '保存成功！')
            );
        } catch(HVerifyException $ex) {
            HResponse::json(
                array('msg' => $ex->getMessage())
            );
        } catch(HRequestException $ex) {
            HResponse::json(
                array('msg' => $ex->getMessage())
            );
        }
    }

    /**
     * 得到ov数据模型的字段 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @return void
     * @throws none
     */
    public function getfields()
    {
        try {
            HVerify::isAjax(); 
            HVerify::isRecordId(HRequest::getParameter('id'));
            $record     = $this->_model->getRecordById(HRequest::getParameter('id'));
            if(!$record) {
                throw new HVerifyException('数据模型不存在！');
            }
            HResponse::json(array(
                'st' => 1,
                'fields' => $record['has_fields']
            ));
        } catch(HVerifyException $ex) {
            HResponse::json(array('msg' => $ex->getMessage()));
        }
    }

    /**
     * 得到数据模型所有列表 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @return void 
     * @throws none
     */
    public function getlist()
    {
        try {
            HVerify::isAjax();
            $list   = $this->_model->getDataModelListByAjax();
            HResponse::json($list);
        } catch(HVerifyException $ex) {
            HResponse::json(array(
                'msg' => $ex->getMessage()
            ));
        }
    }

    /**
     * 新建数据模型通过Ajax 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @return void
     * @throws none
     */
    public function ajaxnew()
    {
        try {
            HVerify::isAjax();
            HVerify::stringLen(HRequest::getParameter('datamodel_name'), '模型名称', '2', '20');
            HRequest::setParameter('pass_flag', 2);
            HRequest::setParameter('edit_time', HDatetime::getNow());
            HRequest::setParameter('create_time', HDatetime::getNow());
            HRequest::setParameter('author', HSession::getAttribute('userName'));
            if(!$this->_model->add(HPopoHelper::getAddFieldsAndValues($this->_popo))) {
                throw new HRequestException('添加失败！');
            }
            HResponse::json(array(
                'msg' => '添加成功！',
                'st' => 1,
                'id' => $this->_model->getLastInsertId()
            ));
        } catch(HVerifyException $ex) {
            HResponse::json(array(
                'msg' => $ex->getMessage()
            ));
        } catch(HRequestException $ex) {
            HResponse::json(array(
                'msg' => $ex->getMessage()
            ));
        }
    }

}

?>
