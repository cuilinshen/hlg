
<?php 

/**
 * @version $Id$
 * @create 2013-8-6 10:20:09 By luoxinhua
 * @copyRight Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import(HResponse::getCurThemePath() . '.action.articleaction');

/**
 * 关于我们信息类 
 * 
 * @desc
 * 
 * @author luoxinhua <1171102882@qq.com>
 * @package app.site.action
 * @since 1.0.0
 */
class NewsAction extends ArticleAction
{

    /**
     * 构造函数
     * 
     * @desc
     * 
     * @author luoxinhua <1171102882@qq.com>
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->_catIdentifier   = 'news';
        $this->_popo->modelEnName   = 'news';
        $this->_popo->modelZhName   = '苗木资讯';
        $this->_nav                 = 'news';
    }

    /**
     * 首页
     * @return [type] [description]
     */
    public function _list()
    {
        $identifierArr = array(
            'articleList' => array('identifier' => 'article-cat', 'name' => '全部'), 
            'shidiList'=> array('identifier' => 'shidi-news', 'name' => '实地走访'), 
            'aixinList' => array('identifier'=> 'aixin-news', 'name' => '爱心活动') 
        );
        if(HRequest::getParameter('identifier')) {
            $identifierArr[HRequest::getParameter('identifier')]['class'] = 'am-active';
        }else{
            $identifierArr['articleList']['class'] = 'am-active';
        }
        $this->_assignNewsList('1=1', $identifierArr);
        $this->_commAssign();

        $this->_render('news-list');
    }

    /**
     * 加载消息数据
     * @return [type] [description]
     */
    private function _assignNewsList($where, $identifierArr)
    {
         $list  = array();
         foreach($identifierArr as $key => $item) {
            $subCategory = $this->_category->getSubCategoryByIdentifier($item['identifier']);
            $where      = HSqlHelper::whereInByListMap('parent_id', 'id', $subCategory);
            $data       = $this->_model->getSomeRowsByFields(6, '`id`, `name`, `parent_id`, `description`, `content`, `image_path`, `create_time`', $where);    

            $list[$key]  = $data;
        }

        HResponse::setAttribute('identifierArr', $identifierArr);
        HResponse::setAttribute('list', $list);
    }

    /**
     * 得到部份消息列表
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    private function _getSomeNewsListByWhere($where)
    {
        $list = $this->_model->getSomeRows(6, $where);

        return $list;
    }

    /**
     * 合并查询条件
     * @return [type] [description]
     */
    private function _combineSearchWhere()
    {
        $where  = '1=1';
        $catId  = HRequest::getParameter('catid');
        if($catId) {
            $category = $this->_category->getRecordByWhere('`id` = \'' . $catId . '\'');
            HResponse::setAttribute('category', $category);
            $where  .= ' AND `parent_id` = ' . $catId;
        }

        return $where;
    }

    /**
     * 重写type方法
     * @return [type] [description]
     */
    public function type()
    {
        $id     = HRequest::getParameter('id');
        HVerify::isEmpty($id, '分类编号');
        $categoryRecord     = $this->_category->getRecordById($id);
        if(empty($categoryRecord)) {
            throw new HRequestException('该分类不存在，请稍后再试');
        }
        HResponse::setAttribute('categoryRecord', $categoryRecord);
        $identifierArr  = $this->_getIdentifierArr($id);
        $where      = $this->_getSubCategoryWhereById($categoryRecord['id'], true);
        $this->_assignNewsList($where, $identifierArr);

        $this->_render('news-list');
    }


    /**
     * 得到标识分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function _getIdentifierArr($id)
    {
        $identifierArr      = array();
        $subCategoryList    = $this->_category->getSubCategory($id);
        $categoryRecord     = HResponse::getAttribute('categoryRecord');
        foreach($subCategoryList as $key => $item) {
            $data = array(
                'identifier' => $item['identifier'],
                'name'      => $item['name']
            );
            if($item['identifier'] == HRequest::getParameter('identifier')) {
                $data['class'] = 'am-active';
            }
            if($item['id'] != $categoryRecord['id']) {
                $identifierArr[$item['identifier']]     = $data;
            }
        }
        
        array_unshift($identifierArr, array(
            'identifier' => $categoryRecord['identifier'],
            'name'      => $categoryRecord['name']
            )
        );

        return $identifierArr;
    }


     /**
     * {@inheritDoc}
     */
    protected function _detail()
    {
        parent::_detail();
        $this->_assignRecommendNewsList(4);
        $this->_render('news-detail');
    }
}   

?>
