<?php

/**
 * @version			$Id$
 * @create 			2017-08-30 23:08:16 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.commentpopo, app.admin.action.AdminAction, model.commentmodel');

/**
 * 用户评论的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class CommentAction extends AdminAction
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
        $this->_popo        = new CommentPopo();
        $this->_model       = new CommentModel($this->_popo);
    }

    /**
     * 列表后驱方法
    */
    public function _otherJobsAfterList()
    {
        parent::_otherJobsAfterList();
        $list   = HResponse::getAttribute('list');
        HResponse::registerFormatMap(
            'parent_id',
            'name',
            HArray::turnItemValueAsKey($this->_getParentIdList(), 'id')
        );
        HResponse::registerFormatMap(
            'status',
            'name',
            HArray::turnItemValueAsKey(CommentPopo::$_statusMap, 'id')
        );  
         HResponse::registerFormatMap(
            'type',
            'name',
            HArray::turnItemValueAsKey(CommentPopo::$_typeMap, 'id')
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
        HResponse::setAttribute('parent_id_list', $this->_getParentIdList());
        HResponse::setAttribute('status_list', CommentPopo::$_statusMap);
        HResponse::setAttribute('type_list', CommentPopo::$_typeMap);
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

        HResponse::setAttribute('parent_id_list', $this->_getParentIdList());
        HResponse::setAttribute('status_list', CommentPopo::$_statusMap);
        HResponse::setAttribute('type_list', CommentPopo::$_typeMap);
    }

    /**
     * 得到
     * @return [type] [description]
     */
    private function _getParentIdList($list)
    {
        $where = '1=1';
        if($list) {
            $where = HSqlHelper::whereInByListMap('id', 'parent_id', $list);
        }

        return HClass::quickLoadModel('user')->getAllRowsByFields('`id`, `name`', $where);
    }

}

?>
