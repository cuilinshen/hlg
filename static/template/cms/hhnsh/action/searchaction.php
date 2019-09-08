<?php

/**
 * @version			$Id$
 * @create 			2012-10-29 15:10:05 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.articlepopo, model.articlemodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 产品展示的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.cms.action
 * @since 			1.0.0
 */
class SearchAction extends HhnshAction
{

    /**
     * 构造函数 
     * 
     * @access public
     */
    public function __construct() 
    {
        $this->_popo        = new UserPopo();
        $this->_model       = new UserModel($this->_popo);

    }

    /**
     * 构造函数 
     * 
     * @access public
     */
    public function __construct() 
    {
        $this->_hasSelf     = true;
        $this->_perpage     = 10;
        $this->_popo        = new ArticlePopo();
        $this->_model       = new ArticleModel($this->_popo);
        $this->_identifier          = 'search';
        $this->_popo->modelZhName   = '搜索产品、案例、博客';
    }

    /**
     * {@inheritDoc}
     */
    protected function _list()
    {
        HVerify::isEmpty(HRequest::getParameter('keyword'), '搜索关键字');
        parent::_list(
            $this->_getSearchWhere(HRequest::getParameter('keyword')),
            $this->_perpage
        );
        $this->_otherJobs();

        $this->_render('search-list');
    }

    /**
     * 执行其它动作
     * 
     * {@inheritdoc}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     */
    protected function _otherJobs()
    {
        $this->_assignLatestModelList();
        $this->_fixItemModel();
    }

    /**
     * 修正项目模块
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _fixItemModel()
    {
        $list   = HResponse::getAttribute('list');
        if(!$list) {
            return;
        }
        $catIds         = array();
        foreach($list as &$item) {
            $item['catIds']     = array_filter(explode(',', $item['parent_id']));
            $catIds     = array_merge($catIds, $item['catIds']);
        }
        if(1 > count($catIds)) {
            return;
        }
        HResponse::setAttribute('list', $list);
        $category       = HClass::quickLoadModel('category');
        HResponse::setAttribute(
            'categoryMap',
            HArray::turnItemValueAsKey(
                $category->getAllRows('`identifier` IS NOT NULL AND ' . HSqlHelper::whereIn('id', $catIds)),
                'id'
            )
        );
    }

    /**
     * 加载服务单位
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _assignLatestModelList()
    {
        $category    = HClass::quickLoadModel('category');
        foreach(array('cases' => 4, 'blog' => 4, 'product' => 4) as $identifier => $rows) {
            $cat    = $category->getRecordByIdentifier($identifier);
            HResponse::setAttribute(
                $identifier . 'List', 
                $this->_model->getSomeRows($rows, 'parent_id LIKE \'%,' . $cat['id'] . ',%\'')
            );
        }
    }

}

?>
