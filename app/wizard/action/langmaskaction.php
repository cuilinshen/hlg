<?php

/**
 * @version			$Id$
 * @create 			2013-08-08 12:08:03 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.langmaskpopo, app.admin.action.AdminAction, model.langmaskmodel');

/**
 * 语言标识的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since 			1.0.0
 */
class LangmaskAction extends AdminAction
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
        $this->_popo        = new LangmaskPopo();
        $this->_model       = new LangmaskModel($this->_popo);
    }

    /**
     * Ajax搜索
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @throws HRequestException 请求异常
     */
    public function asearch()
    {
        try {
            HVerify::isAjax();
            HResponse::json(
                array(
                    'rs' => true, 
                    'list' => $this->_model->getAllRows(
                        '`name` LIKE \'%' . HRequest::getParameter('mask') . '%\''
                    )
                )
            );
        } catch(HRequestException $ex) {
            HResponse::json(array('rs' => false, 'info' => $ex->getMessage()));
        }
    }

    /**
     * 动态加载所有标识
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @throws HRequestException 请求异常
     */
    public function aload()
    {
        try {
            HVerify::isAjax();
            HResponse::json(
                array(
                    'rs' => true, 
                    'list' => $this->_model->getAllRows()
                )
            );
        } catch(HRequestException $ex) {
            HResponse::json(array('rs' => false, 'info' => $ex->getMessage()));
        }
    }

    /**
     * 编辑视图
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @throws Exception
     */
    public function editview()
    {
        $this->_editview();
        $this->_assignLangTypeList();
        $this->_assignLangList();
        $this->_assignTplList();
        $this->_render('langmask/info');
    }

    /**
     * 加载模板列表
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _assignTplList()
    {
        $tpl    = HClass::quickLoadModel('tpl');
        HResponse::setAttribute('tplList', $tpl->getAllRows());
    }

    /**
     * 加载语言翻译列表
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _assignLangList()
    {
        $lang   = HClass::quickLoadModel('lang');
        HResponse::setAttribute(
            'langList', 
            HArray::turnItemValueAsKey(
                $lang->getAllRows('`mask_id` = ' . HRequest::getParameter('id')),
                'parent_id'
            )
        );
    }

    /**
     * {@inhertDoc}
     */
    public function edit()
    {
        $this->_updateOrAddLang(HRequest::getParameter('id'));
        $this->_edit();
        HResponse::succeed('更新成功！');
    }

    /**
     * {@inhertDoc}
     */
    public function addview()
    {
        $this->_addview();
        $this->_assignLangTypeList();
        $this->_assignTplList();
        HResponse::setAttribute('langList', array());
        $this->_render('langmask/info');
    }

    /**
     * {@inhertDoc}
     */
    public function add()
    {
        $this->_add();
        $this->_updateOrAddLang(HResponse::getAttribute('insertId'));
        HResponse::succeed('添加成功！');
    }

    /**
     * 更新或添加语言翻译
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @throws HVerifyException | HRequestException
     */
    protected function _updateOrAddLang($id)
    {
        $lid        = HRequest::getParameter('lid');
        $content    = HRequest::getParameter('content');
        $langType   = HRequest::getParameter('langtype');
        $lang       = HClass::quickLoadModel('lang');
        foreach($langType as $key => $type) {
            $data   = array(
                'mask_id' => $id,
                'parent_id' => $type,
                'name' => $content[$key],
                'author' => HSession::getAttribute('id', 'user')
            );
            if(empty($lid[$key])) {
                $langId    = $lang->add($data);
                if(!$langId) {
                    throw new HRequestException('语言翻译添加失败，请您稍后再试！');
                }
                $this->_addTplJoinInfo($langId);
                continue;
            }
            $data['id']     = $lid[$key];
            if(!$lang->edit($data)) {
                throw new HRequestException('语言翻译更新失败，请您稍后再试！');
            }
            $this->_addTplJoinInfo($data['id']);
        }
    }

    /**
     * @point
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @return void
     * @throws none
     */
    protected function _addTplJoinInfo($id)
    {
        if(!HRequest::getParameter('tpl')) {
            return;
        }
        $tplIds     = HRequest::getParameter('tpl');
        $tpl        = HClass::quickLoadModel('tpl');
        foreach($tplIds as $tplId) {
            $record = $tpl->getRecordByWhere('`tpl_id` = ' . $tplId . ' AND `lang_id` = ' . $id);
            if($record) {
                continue;
            }
            $data   = array('lang_id' => $id, 'tpl_id' => $tplId);
            if($tpl->add($data)) {
                throw new HRequestException('添加模板及使用的语言关联信息失败！');
            }
        }
    }

    /**
     * 删除标识
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @throws HRequestException | HVerifyException 相关异常 
     */
    public function delete() 
    {
        $recordIds  = HRequest::getParameter('id');
        if(!is_array($recordIds)) {
            $recordIds  = array($recordIds);
        }
        $this->_delete($recordIds);
        $this->_deleteLang($recordIds);
        HResponse::alertAndJump('删除成功！', HResponse::url('wizard/' . $this->_popo->modelEnName));
    }

    /**
     * 删除对应的语言翻译
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  Array $maskIds 标识IDs
     * @throws Exception 异常
     */
    protected function _deleteLang($maskIds)
    {
        $lang   = HClass::quickLoadModel('lang');
        $lang->deleteByWhere(HSqlHelper::whereIn('parent_id', $maskIds));
    }

}

?>
