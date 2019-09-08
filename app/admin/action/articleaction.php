<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.articlepopo, app.admin.action.AdminAction, model.articlemodel');

/**
 * 文章的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class ArticleAction extends AdminAction
{

    /**
     * 构造函数 
     * 
     * @access public
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_popo        = new ArticlePopo();
        $this->_popo->setFieldAttribute('tags', 'is_show', false);
        $this->_popo->setFieldAttribute('tags_name', 'is_show', false);
        $this->_popo->setFieldAttribute('description', 'is_show', false);
        $this->_popo->setFieldAttribute('author', 'is_show', false);
        $this->_model       = new ArticleModel($this->_popo);
    }

    public function index()
    {
        $where  = $this->_getActorWhere();
        if(HRequest::getParameter('status')) {
            $where .= ' AND `status` = ' . HRequest::getParameter('status');
        }
        $this->_search($where);

        $this->_render('article/list');
    }

     /**
     * 得到权限查询条件
     * @return [type] [description]
     */
    private function _getActorWhere()
    {
        if(HSession::getAttribute('actor', 'user') == 'county_member') {
            return '`author` = ' . HSession::getAttribute('id', 'user');
        }
        return '1=1';
    }

     /**
     * 搜索方法 
     * 
     * @access public
     */
    public function search()
    {
        $where  = $this->_combineWhere();
        if(HRequest::getParameter('status')) {
            $where  .= ' AND `status` = ' . HRequest::getParameter('status');
        }
        $this->_search($where);

        $this->_render('article/list');
    }

     /**
     * 组合搜索条件
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @return String 组合成的搜索条件
     */
    protected function _combineWhere()
    {
        return $this->_getActorWhere() . ' AND ' . parent::_combineWhere();
    }

    /**
     * 列表后驱
     * @return [type] [description]
     */
    protected function _otherJobsAfterList()
    {
        parent::_otherJobsAfterList();
        HResponse::registerFormatMap(
            'status',
            'name',
            ArticlePopo::$statusMap
        );
        HResponse::registerFormatMap(
            'is_recommend',
            'name',
            ArticlePopo::$isRecommendMap  
        );
    }

    /**
     * 视图后驱
     * 
     * {@inheritdoc}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     */
    protected function _otherJobsAfterAddView()
    {
        parent::_otherJobsAfterAddView();
        $this->_assignMyCategory();
        if(HSession::getAttribute('actor', 'user') == 'county_member') {
            unset(ArticlePopo::$statusMap[2]);
        }
        HResponse::setAttribute('status_list', ArticlePopo::$statusMap);
        HResponse::setAttribute('is_recommend_list', ArticlePopo::$isRecommendMap);
    }

    /**
     * 编辑后驱
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _otherJobsAfterEditView($record)
    {
        parent::_otherJobsAfterEditView($record);
        $this->_assignParentInfo($record['parent_id']);
        $this->_assignMyCategory();
        $this->_assignTags();
        if(HSession::getAttribute('actor', 'user') == 'county_member') {
            unset(ArticlePopo::$statusMap[2]);
        }
        HResponse::setAttribute('status_list', ArticlePopo::$statusMap);
        HResponse::setAttribute('is_recommend_list', ArticlePopo::$isRecommendMap);
    }

    /**
     * 执行模块的添加 
     * 
     * @access public
     */
    public function add()
    {
        $id     = $this->_add();
        $this->_setParentPath($id);
        $this->_addTagLinkedData($id);
        $this->_addLangLinkedData($id);
        //发布的时候才同步

        HResponse::succeed($acName . '添加成功！', HResponse::url('article'));
    }

    /**
     * 加载当前分类信息
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _assignMyCategory()
    {
        $record     = HResponse::getAttribute('record');

        HResponse::setAttribute(
            'parent_id_nodes', 
            $this->_formatToZTreeNodes(HResponse::getAttribute('parent_id_list'), array($record['parent_id']))
        );
    }

    /**
     * 编辑提示动作 
     * 
     * @access public
     */
    public function edit()
    {
        $this->_verifyActor();
        $record     = $this->_edit();
        $this->_setParentPath($record['id']);
        $this->_addTagLinkedData(HRequest::getParameter('id'));
        $this->_addLangLinkedData($record['id']);

        HResponse::succeed('更新成功！', HResponse::url('article'));
    }

    /**
     * 设置一些请求的字段值
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _setFieldsDefaultValue()
    {
        HRequest::setParameter('edit_time', $_SERVER['REQUEST_TIME']);
        if(!HRequest::getParameter('id')){
            HRequest::setParameter('author', HSession::getAttribute('id', 'user'));
        }else{
            $record     = $this->_model->getRecordById(HRequest::getParameter('id'));
            HRequest::setParameter('author', $record['author']);
        }
    }

    /**
     * 验证权限
     * @return [type] [description]
     */
    private function _verifyActor()
    {
         if(HSession::getAttribute('actor', 'user') == 'county_member') {
            $record     = $this->_model->getRecordById(HRequest::getParameter('id'));
            if(HSession::getAttribute('id', 'user') != $record['author']) {
                throw new HRequestException('对不起您不能修改');
            }
        }
    }

     /**
     * 删除动作 
     * 
     * @access public
     */
    public function delete()
    {
        $this->_verifyActor();
        $recordIds  = HRequest::getParameter('id');
        if(!is_array($recordIds)) {
            $recordIds  = array($recordIds);
        }
        $this->_delete($recordIds);
        $this->_otherJobsAfterDelete($recordIds);

        HResponse::succeed(
            '删除成功！', 
            HResponse::url($this->_popo->modelEnName, '', 'admin')
        );
    }

     /**
     * 编辑动作 
     * 
     * @access public
     */
    public function editview()
    {
        $this->_verifyActor();
        $this->_editview();
        $this->_otherJobsAfterInfo();
        
        $this->_render($this->_popo->modelEnName . '/info');
    }



    /**
     * 设置层级
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     * @param $id 编号
     */
    private function _setParentPath($id)
    {
        $cat    = $this->_category->getRecordById(HRequest::getParameter('parent_id'));
        if(!$cat) {
            throw new HVerifyException('分类不存在，请确认！');
        }
        $this->_model->editByWhere(array('parent_path' => $cat['parent_path']), '`id` = ' . $id);
    }

    /**
     * 得到当前模块的所有父类 
     * 
     * 根据当前popo类里的parentTable来判断是否有父类 
     * 
     * @access protected
     */
    protected function _assignAllParentList()
    {
        $list       = $this->_category->getSubcategoryByIdentifier('article-cat', true);
        $list       = array_merge($list, $this->_category->getSubcategoryByIdentifier('single-page', true));

        HResponse::setAttribute('parent_id_list', $list);
    }

    /**
     * 执行额外的删除操作
     * 
     * {@inheritdoc}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     */
    protected function _otherJobsAfterDelete($ids)
    {
        parent::_otherJobsAfterDelete();
        $this->_deleteTagsLinkedData($ids);
    }

    /**
     * 得到上层条件
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param $id 编号
     * @return String 
     */
    protected function _getParentWhere($id)
    {
        $cat    = $this->_category->getRecordById($id);
        if(!$cat) {
            throw new HVerifyException('分类已经不存在，请确认！');
        }

        return '`parent_path` LIKE \'' . $cat['parent_path'] . '%\'';
    }
}

?>
