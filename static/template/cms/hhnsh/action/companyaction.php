<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.companypopo, model.companymodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');
HClass::import(HResponse::getCurThemePath() . '.action.QrcodeAction');
/**
 * 公司的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class CompanyAction extends HhnshAction 
{

    /**
     * 构造函数 
     * 
     * 初始化类里的变量 
     * 
     * @access public
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_popo        = new CompanyPopo();
        $this->_model       = new CompanyModel($this->_popo);
        $this->_user        = HClass::quickLoadModel('user');
    }

    /**
     * 店铺首页
     * @return [type] [description]
     */
    public function index()
    {
        $id     = HRequest::getParameter('id');
        $this->_verifyJSONIsEmpty($id, '店铺ID');
        $record         = $this->_model->getRecordByWhere('`id` = ' . $id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '记录不存在'));
        }
        $record['image_path']   = HString::formatImage($record['image_path']);
        $where = '`id` = ' . $record['parent_id'];
        $categoryRecord = HClass::quickLoadModel('category')->getRecordByWhere($where);
        $record['category_name'] = $categoryRecord['name'];
        $userId = HRequest::getParameter('user_id');
        $openid = HRequest::getParameter('openid'); 
        $record['is_collect'] = 0;
        if($userId && $openid) {
            $this->_verifyUserOpenId($openid, $userId);
            $linkeddata = HClass::quickLoadModel('linkeddata');
            $linkeddata->setRelItemModel('user', 'collect');
            $linkeddataRecord = $linkeddata->getRecordByWhere('`item_id` = ' . $userId . ' AND `rel_id` = ' . $id . ' AND `extend` = 1');
            $record['is_collect'] = $linkeddataRecord ? 1 : 0;
            //更新访问记录
            $this->_updateTotalVisit($userId, $id, $record['total_visits']);
        }
        $commentList = $this->_getShopCommentList($record['id']);
        $result     = array(
            'record' => $record,
            'caselist' => $this->_getShopCaseList($record['id']),
            'commentlist' => $commentList
        );
        $this->_updateInformationTotalVisits();

        HResponse::json(array('rs' => true, 'data' => $result));
    }

    /**
     * 更新访问记录
     * @return [type] [description]
     */
    private function _updateTotalVisit($userId, $id, $totalVisits)
    {
        $linkeddata     = HClass::quickLoadModel('linkeddata');
        $linkeddata->setRelItemModel('user', 'visit');
        $data   = array(
            'item_id' => $userId,
            'rel_id'  => $id,
            'author'  => $userId
        );
        if(1 > $linkeddata->add($data)) {
            HResponse::json(array('rs' => false, 'message' => '访问量更新失败'));   
        }
        if(1 > $this->_model->editByWhere(array('total_visits' => $totalVisits + 1), '`id` = ' . $id)) {
        HResponse::json(array('rs' => false, 'message' => '访问总量更新失败'));   
        }
        
    }

    /**
     * 得到店铺案例列表
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function _getShopCaseList($id)
    {
        $case = HClass::quickLoadModel('case');
        $list = $case->getAllRowsByFields('*', '`parent_id` = ' . $id);
        foreach ($list as $key => &$value) {
            $value['image_path'] = HString::formatImage($value['image_path']);
        }

        return $list;
    }

    /**
     * 得到店铺的评价列表
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function _getShopCommentList($id)
    {
        $where  = '`type` = 1 AND `status` = 1 AND `product_id` = ' . $id;
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $prepage= HRequest::getParameter('prepage') ? HRequest::getParameter('prepage') : 5;
        $comment = HClass::quickLoadModel('comment');
        $list   = $comment->getListByWhere($where, $page - 1, $prepage); 
        if(empty($list)) {
            return array('total' => 0, 'list' => array());
        }
        $total  = $comment->getTotalRecords($where);
        $userMap = HArray::turnItemValueAsKey(
            $this->_user->getAllRowsByFields('`id`, `name`, `image_path`', HSqlHelper::whereInByListMap('id', 'parent_id', $list)), 
            'id'
        );
        foreach($list as $key => &$item) {
            $item['name']           = HString::cutString($item['name'], 10, '...');
            $item['create_time']    = HString::formatTime($item['create_time']);
            $item['user_avatar']    = $userMap[$item['parent_id']]['image_path'];
        }

        return array('total' => $total, 'list' => $list);
    }

    /**
     * 得到店铺商品列表
     * @param  [type] $companyId [description]
     * @return [type]            [description]
     */
    private function _getCompanyProductList($companyId)
    {
        $product    = HClass::quickLoadModel('product');
        
    }    

    /**
     * 得到店铺优惠券列表
     * @param  [type] $companyId [description]
     * @return [type]            [description]
     */
    private function _getCompanyCouponList($companyId)
    {

    }

    /**
     * 搜索店铺
     * @return [type] [description]
     */
    public function search()
    {
        $keyword = HRequest::getParameter('keyword');
        if($keyword) {
            $where      = '`name` LIKE \'%' . $keyword . '%\'';    
        }else{
            $where      = '1=1';    
        }
        $page       = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $perpage    = HRequest::getParameter('perpage') ? HRequest::getParameter('perpage') : 10;
        $companyList= $this->_model->getListByFields('*',$where, $page - 1, $perpage);
        $lat       = HRequest::getParameter('lat');
        $lng       = HRequest::getParameter('lng');
        foreach($companyList as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
            $item['distance']       = $this->_getDistance($lat, $lng, $item['lat'], $item['longs']);
        }

        HResponse::json(array('rs' => true, 'data' => $companyList));
    }

    /**
     * 新店列表
     * @return [type] [description]
     */
    public function type()
    {
        $where      = '`status`=3';
        $catId      = HRequest::getParameter('cat_id'); 
        $sort       = HRequest::getParameter('sort');//distance 距离 id 最新 total_visits 热门 total_score 评分
        if($sort && $sort != 'distance') {
            $this->_popo->setFieldAttribute('id', 'is_order', null);
            $this->_popo->setFieldAttribute($sort, 'is_order', 'desc');
        }
        if($catId) {
            $where .= ' AND `parent_id` = ' . $catId;
        }
        $page       = HRequest::getParameter('page') ? HRequest::getParameter('page') : 1;
        $perpage    = HRequest::getParameter('perpage') ? HRequest::getParameter('perpage') : 10;
        $companyList= $this->_model->getListByFields('*',$where, $page - 1, $perpage);
        $lat       = HRequest::getParameter('lat');
        $lng       = HRequest::getParameter('lng');
        foreach($companyList as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
            $item['distance']       = $this->_getDistance($lat, $lng, $item['lat'], $item['longs']);
            $item['tagArr']         = $this->_getTagsArr($item['attrs']);
        }
        $this->_updateInformationTotalVisits();

        HResponse::json(array('rs' => true, 'data' => $companyList));
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
     * 验证添加的数据
     * @return [type] [description]
     */
    protected function _verifyAddData()
    {
        $data       = array();
        $data['userid']      = $this->_verifyJSONIsEmpty(HRequest::getParameter('userid'), '用户id');
        $openid = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openid');
        $this->_verifyUserOpenId($openid, $data['userid']);
        $data['name']        = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '店铺名称');
        $data['phone']       = $this->_verifyJSONIsEmpty(HRequest::getParameter('phone'), '联系电话');
        $data['username']    = $this->_verifyJSONIsEmpty(HRequest::getParameter('username'), '联系人');
        $data['address']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('address'), '店铺地址');
        $data['parent_id']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('parent_id'), '所属分类');
        $data['image_path']  = HRequest::getParameter('image_path');
        $data['zhuying']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('zhuying'), '主营');
        $data['lat']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('lat'), '经度');
        $data['longs']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('longs'), '纬度');
        $data['attrs']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('attrs'), '标签');
        

        return $data;
    }

    /**
     * 得到公司所在的省市
     * @return [type] [description]
     */
    public function province()
    {
        $province   = HClass::quickLoadModel('province');
        $city       = HClass::quickLoadModel('city');
        $provinceList = $province->getAllRows('`is_open` = 2');
        $where      = HSqlHelper::whereInByListMap('parent_id', 'id', $provinceList) . ' AND `is_open` = 2';
        $cityList   = $city->getAllRowsByFields('`id`, `name`, `parent_id`', $where);
        $result     = array();
        foreach($provinceList as $key => $item) {
            foreach($cityList as $cityKey => $city) {
                if($item['id'] == $city['parent_id']) {
                    $item['citys'][$cityKey] = $city;
                }
            }
            $result[$key]   = $item;
        }
        foreach ($result as $key => &$value) {
            $value['citys'] = array_values($value['citys']);
        }

        HResponse::json(array('rs' => true, 'data' => $result));
    }

    /**
     * 得到指定用户对应的店铺
     * @return [type] [description]
     */
    public function info()
    {
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户id');
        $openId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('shop_id'), '店铺Id');
        $this->_verifyUserOpenId($openId, $userId);
        $record     = $this->_model->getRecordByWhere('`userid` = ' . $userId . ' AND `id` = ' . $id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '店铺不存在'));
            exit();
        }
        $categoryRecord = HClass::quickLoadModel('category')->getRecordByWhere('`id` = ' . $record['parent_id']);
        $record['category_name'] = $categoryRecord['name'];
        $record['shopSum']   = $this->_getSumData($id);
        $record['tagsArr']   = explode(',', $record['attrs']);

        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 得到今日商户经营数据
     * @return [type] [description]
     */
    private function _getSumData($shopId)
    {
        $list  = array();
        $today = date('Y-m-d', time());
        $yes   = date('Y-m-d', time() - 3600 * 24);
        $shopSum = HClass::quickLoadModel('shopsum'); 
        $list['today'] = $shopSum->getRecordByWhere('`cur_date` = \'' . $today . '\' AND `parent_id` = ' . $shopId);
        $list['yes']   = $shopSum->getRecordByWhere('`cur_date` = \'' . $yes . '\' AND `parent_id` = ' . $shopId);
        
        return $list;
    }

    /**
     * 加载企业的省市信息
     * @param  [type] $company [description]
     * @return [type]          [description]
     */
    private function _assignProvinceCityInfo($company)
    {
        if(!$company['province_id']) {
            return $company;
        }
        $provinceInfo   = HClass::quickLoadModel('province')->getRecordById($company['province_id']);
        $cityInfo       = HClass::quickLoadModel('city')->getRecordById($company['city_id']);
        $company['province_name']   = $provinceInfo['name'];
        $company['city_name']       = $cityInfo['name'];

        return $company;
    }

    /**
     * 得到产品列表
     * @return [type] [description]
     */
    private function _getProductList($company)
    {
        $product    = HClass::quickLoadModel('product');        
        $list       = $product->getAllRows('`company_id` = ' . $company['id']);
        $onsales    = array();
        $outsales   = array();
        foreach($list as $key => $item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
            if($item['status'] == 1) {
                $item['attrs']  = json_decode($item['attrs'], true);
                $item['shop']   = $company['name'];
                array_push($onsales, $item);
            }
            if($item['status'] == 2) {
                $item['attrs']  = json_decode($item['attrs'], true);
                $item['shop']   = $company['name'];
                array_push($outsales, $item);
            }
        }   
        $company['onsales']     = $onsales;
        $company['outsales']    = $outsales;

        return $company;
    }

    /**
     * 得到外卖模板的数据
     * @return [type] [description]
     */
    public function agetwaimailist()
    {
        $id     = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), 'ID');
        $record = $this->_model->getRecordById($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '记录不存在'));
            return;
        }
        $data   = array('companyInfo' => $record);
        $categoryList = $this->_category->getSubCategory($record['category_id'], false);
        $product    = HClass::quickLoadModel('product');
        $where      = '`company_id` = ' . $id . ' AND ' . HSqlHelper::whereInByListMap('parent_id', 'id', $categoryList);
        $productList = $product->getAllRowsByFields(
            '`id`, `name`, `image_path`, `parent_id`, `total_orders`, `price`, `total_number` ', 
            $where
        );
        $list = array();
        foreach($categoryList as $key => &$category) {
            $category['typeName']   = $category['name'];
            foreach($productList as $productKey => &$product) {
                if($category['id'] == $product['parent_id']) {
                    $product['sales'] = $product['total_orders'];
                    $product['numb']   = 0;
                    $product['src']   = HString::formatImage($product['image_path']);
                    $category['menuContent'][$productKey] = $product;
                }
            }
        }
        $data['list']   = $categoryList;

        HResponse::json(array('rs' => true, 'data' => $data));
    }

    /**
     * 店铺与取消收藏
     * @return [type] [description]
     */
    public function collect()
    {
        $id     = HRequest::getParameter('id');
        $this->_verifyJSONIsEmpty($id, '店铺ID');
        $record         = $this->_model->getRecordByWhere('`id` = ' . $id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '记录不存在'));
        }
        $userId = HRequest::getParameter('user_id');
        $this->_verifyJSONIsEmpty($userId, '用户ID');
        $openid = HRequest::getParameter('open_id'); 
        $this->_verifyJSONIsEmpty($openid, 'openID');
        $this->_verifyUserOpenId($openid, $userId);
        $collect = HRequest::getParameter('collect');  //0代表取消收藏动作 1代表收藏动作
        $linkeddata = HClass::quickLoadModel('linkeddata');
        $linkeddata->setRelItemModel('user', 'collect');
        $linkeddataRecord = $linkeddata->getRecordByWhere('`item_id` = ' . $userId . ' AND `rel_id` = ' . $id);
        if($collect) {
            //收藏动作
            if($linkeddataRecord) {
                if(1 > $linkeddata->editByWhere(array('extend' => 1), '`id` = ' . $linkeddataRecord['id'])) {
                    HResponse::json(array('rs' => false, 'message' =>'收藏失败'));
                }
            }else{
                $data = array(
                    'item_id' => $userId,
                    'rel_id'  => $id,
                    'extend'  => 1
                );
                if(1 > $linkeddata->add($data)) {
                    HResponse::json(array('rs' => false, 'message' =>'更新失败'));   
                } 
            }
            $this->_model->editByWhere(array('total_collects' => $record['total_collects'] + 1), '`id` = ' . $record['id']);
        }else{
            //取消收藏
             if(1 > $linkeddata->editByWhere(array('extend' => 0), '`id` = ' . $linkeddataRecord['id'])) {
                    HResponse::json(array('rs' => false, 'message' =>'取消失败'));
            }

            $this->_model->editByWhere(array('total_collects' => $record['total_collects'] -1), '`id` = ' . $record['id']);
        }

        HResponse::json(array('rs' => true, 'data' => $collect));

    }

    /**
     * 得到加盟的页面数据
     * @return [type] [description]
     */
    public function agetjiamendata()
    {
        $dianPuCatList  = $this->_category->getSubCategoryByIdentifier('dianpu-cat', false);
        $tagList        = $this->_category->getSubCategoryByIdentifier('shop-tags', false);
        $data = array(
            'dianPuCatList' => $dianPuCatList,
            'tagList'       => $tagList
        );

        HResponse::json(array('rs' => true, 'data' => $data));
    }

    
    /**
     * 生成图片
     * @return [type] [description]
     */
    public function createshareimage()
    {
       $this->_verifyShopRight();
       $path = 'pages/merchant/merchant-detail/merchant-detail?id=' . HRequest::getParameter('shop_id');
       $width = 430;
       $openId = HRequest::getParameter('open_id');
       $savePath = 'qrcode/' . $openId . '.png';
       $qrcode = HClass::quickLoadModel('qrcode');
       $qrcodeRecord = $qrcode->getRecordByWhere('`open_id` = \'' . $openId . '\' AND `model` = \'shop\'');
       if($qrcodeRecord['image_path']) {
            HResponse::json(array('rs' => true, 'data' => $qrcodeRecord['image_path']));
       }else{
            $QrcodeAction = new QrcodeAction();
            $imagePath = $QrcodeAction->createWxaQrcode($path, $savePath, $width); 
            $data = array(
                'open_id'   => $openId,
                'model'     => 'shop',
                'image_path'=> $imagePath
            );
            if(1 > $qrcode->add($data)) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));
            }

            HResponse::json(array('rs' => true, 'data' => $imagePath));
       }
    }

    /**
     * 验证商户权限
     * @return [type] [description]
     */
    protected function _verifyShopRight()
    {
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户id');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openId');
        $shopId = $this->_verifyJSONIsEmpty(HRequest::getParameter('shop_id'), 'shopId');
        $user   = HClass::quickLoadModel('user');
        $userInfo = $user->getRecordByWhere('`id` = ' . $userId . ' AND `open_id` = \'' . $openId . '\'');
        if(!$userInfo || $userInfo['parent_id'] != 3) {
            HResponse::json(array('rs' => false, 'message' => '商户权限不足'));
        }
    }


}