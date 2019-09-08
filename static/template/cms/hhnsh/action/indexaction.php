<?php

/**
 * @version			$Id$
 * @create 			2012-4-7 17:27:43 By 1171102882
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.1171102882.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import(HResponse::getCurThemePath() . '.action.hhnshaction');

/**
 * 主页的动作类
 *
 * 主要处理主页的相关请求动作
 *
 * @author 			1171102882 <1171102882@foxmail.com>
 * @package 		app.cms.action
 * @since 			1.0.0
 */
class IndexAction extends HhnshAction
{

    /**
     * 公司对象
     * @var [type]
     */
    private $_company;

    /**
     * 产品对象
     * @var [type]
     */
    private $_product;

    /**
     * 构造函数
     * 
     * @author 1171102882 <1171102882@foxmail.com>
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->_product     = HClass::quickLoadModel('product');
        $this->_company     = HClass::quickLoadModel('company');
    }

    /**
     * 网站首页
     * 
     * @author 1171102882 <1171102882@foxmail.com>
     * @access public
     */
    public function index()
    {
        $advList    = $this->_getAdvList();
        $recommendGoodsList = $this->_getRecommendGoodsList();
        $data['adlist']     = $advList;        
        $data['recommendgoodslist'] = $recommendGoodsList;
        $data['categorylist']  = $this->_getCategoryList();
        $this->_updateInformationTotalVisits();

        HResponse::json(array('rs' => true, 'data' => $data));
    }

    /**
     * infomation
     * @return [type] [description]
     */
    public function infomation()
    {
        $infomation = $this->_getInfomation();

        HResponse::json(array('rs' => true, 'data' => $infomation));
    }

    /**
     * 得到轮播图列表
     * @return [type] [description]
     */
    private function _getBannerList()
    {
        $banner    = HClass::quickLoadModel('banner');
        $bannerList = $banner->getSomeRowsByFields(3, '*', '1=1');
        foreach($bannerList as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
        }

        return $bannerList;
    }

    /**
     * 得到广告列表
     * @return [type] [description]
     */
    private function _getAdvList()
    {
        $adv    = HClass::quickLoadModel('adv');
        $record = $this->_category->getRecordByWhere('`identifier` = \'jingxuan-cat\'');
        $advList = $adv->getSomeRowsByFields(4, '*', '`parent_id` = ' . $record['id']);
        foreach($advList as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
        }

        return $advList;
    }

    /**
     * 得到Menu列表
     * @return [type] [description]
     */
    private function _getMenuList()
    {
        $menu   = HClass::quickLoadModel('menu');
        $menuList = $menu->getAllRows('1=1');
        foreach ($menuList as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
        }

        return $menuList;
    }

    /**
     * 得到站点信息
     * @return [type] [description]
     */
    private function _getInfomation()
    {   
        $record = HClass::quickLoadModel('information')->getRecordByWhere('`id` = 1');
        
        return array(
            'name' => $record['name'],
            'total_visits' => $record['total_visits'],
            'total_shops' => $record['total_shops']
        );
    }   

    /**
     * 得到附近的商家列表
     * @return [type] [description]
     */
    private function _getCompanyList()
    {
        $company    = HClass::quickLoadModel('company');
        $where      = '`status`= 3 ';
        $page       = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $perpage    = HRequest::getParameter('perpage') ? HRequest::getParameter('perpage') : 10;
        $companyList= $company->getListByFields('*',$where, $page - 1, $perpage);
        $lat       = HRequest::getParameter('lat');
        $lng       = HRequest::getParameter('lng');
        foreach($companyList as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
            $item['distance']       = $this->_getDistance($lat, $lng, $item['lat'], $item['longs']);
            $item['tagArr']         = $this->_getTagsArr($item['attrs']);
        }

        return $companyList;
    }

        /**
     * 得到附近的商家列表
     * @return [type] [description]
     */
    private function _getCompanyListByRecommend()
    {
        $company    = HClass::quickLoadModel('company');
        $where      = '`status`= 3 AND `is_open` = 1';
        $page       = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $perpage    = HRequest::getParameter('perpage') ? HRequest::getParameter('perpage') : 10;
        $companyList= $company->getListByFields('*',$where, $page - 1, $perpage);
        $lat       = HRequest::getParameter('lat');
        $lng       = HRequest::getParameter('lng');
        foreach($companyList as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
            $item['distance']       = $this->_getDistance($lat, $lng, $item['lat'], $item['longs']);
            $item['tagArr']         = $this->_getTagsArr($item['attrs']);
        }

        return $companyList;
    }

    /**
     * 格式化标签为数组
     * @param  [type] $tagStr [description]
     * @return [type]         [description]
     */
    private function _getTagsArr($tagStr)
    {
        if(!$tagStr) {
            return array();
        }

        return explode(',', $tagStr);
    }

    /**
     * 得到头条列表
     * @return [type] [description]
     */
    private function _getTouTiaoList()
    {
        $categoryRecord = $this->_category->getRecordByWhere('`identifier` = \'toutiao-news\'');
        $list = HClass::quickLoadModel('article')->getSomeRowsByFields(1, '`id`, `name`', '`parent_id` = ' . $categoryRecord['id']);
        
        return $list[0];
    }

    /**
     * 首页的店铺数据
     * @return [type] [description]
     */
    public function company()
    {
        $nearList = $this->_getCompanyList();
        $recommendList = $this->_getCompanyListByRecommend();
           
        HResponse::json(array('rs' => true, 'data' => array('near' => $nearList, 'recommend' => $recommendList)));
    }

    /**
     * 得到商家列表分页 附近的
     * @return [type] [description]
     */
    public function agetcompanylist()
    {
        $list = $this->_getCompanyList();
           
        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 得到商家列表分页 推荐的
     * @return [type] [description]
     */
    public function agetcompanylistbyrecommend()
    {
        $list = $this->_getCompanyListByRecommend();
    }

    /**
     * 得到产品列表
     * @return [type] [description]
     */
    private function _getProductList()
    {
        $productCat     = array(
            array('identifier' => 'songcai-cat', 'name' => '送菜上门'),
            array('identifier' => 'luoyeguanmu-cat', 'name' => '家政上门'),
            array('identifier' => 'paotui-cat', 'name' => '跑腿上门'),
            array('identifier' => 'xiaochi-cat', 'name' => '特色小吃'),
            
        );
        foreach($productCat as $key => &$cat) {
            $category   = $this->_category->getRecordByWhere('`identifier` = \'' . $cat['identifier'] . '\'');
            $cat['id']  = $category['id'];
            $subCategoryList = $this->_category->getSubCategoryByRows($category['id']);
            $where      = HSqlHelper::whereInByListMap('parent_id', 'id', $subCategoryList);
            $list       = $this->_product->getSomeRowsByFields(
                3, 
                '`id`, `name`, `image_path`, `price`, `start_date`, `company_id`', 
                $where
            );
            $list           = $this->_getProductCompanyMap($list);
            $cat['list']    = $list;
        }

        return $productCat;
    }

    /**
     * 得到产品公司的Map
     * @return [type] [description]
     */
    private function _getProductCompanyMap($list)
    {
        if(!$list) {
            return;
        }
        $where  = HSqlHelper::whereInByListMap('id', 'company_id', $list);
        $companyMap    = HArray::turnItemValueAsKey(
            $this->_company->getAllRowsByFields('`id`, `name`', $where), 
            'id'
        );
        foreach($list as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
            $item['shop'] = $companyMap[$item['company_id']]['name'];
        }

        return $list;
    }

    /**
     * 得到推荐产品列表
     * @return [type] [description]
     */
    private function _getRecommendGoodsList()
    {
        $product    = HClass::quickLoadModel('product');
        $where      = '`is_recommend` = 2 AND `status` = 1';
        if(HRequest::getParameter('city')) {
            $area = HClass::quickLoadModel('area')->getRecordByWhere('`name` = \'' . HRequest::getParameter('city') . '\' AND `level` = 2');
            if($area) {
                $where .= ' AND `city_id` = ' . $area['id'];
            }
        } 
        $list       = $product->getSomeRowsByFields(6, '*', $where);
        foreach($list as $key => &$item) {
            $item['image_path'] = HString::formatImage($item['image_path']);
            $item['start_date']       = $this->_formatProductDate($item['start_date'], $item['end_date']);
        }

        return $list;
    }

    /**
     * 得到推荐的分类列表
     * @return [type] [description]
     */
    private function _getCategoryList()
    {
        $record     = $this->_category->getRecordByWhere('`identifier` = \'product-cat\'');
        $subList    = $this->_category->getSubCategory($record['id'], true);
        $list   = $this->_category->getSomeRowsByFields(5, '`id`, `name`', HSqlHelper::whereInByListMap('parent_id', 'id', $subList) . ' AND `total_use` = 1');
        if(HRequest::getParameter('user_id')) {
            $linkeddata = HClass::quickLoadModel('linkeddata');
            $linkeddata->setRelItemModel('user', 'category');
            $linkeddataList = $linkeddata->getAllRows('`item_id` = ' . HRequest::getParameter('user_id'));
            $userCheckList  = $this->_category->getAllRowsByFields('`id`, `name`, `image_path`', HSqlHelper::whereInByListMap('id', 'rel_id', $linkeddataList));
            $list = array_merge($list, $userCheckList);
        }

        return $list;
    }

}

?>
