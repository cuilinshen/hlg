<?php

/**
 * @version			$Id$
 * @create 			2013-06-18 10:06:22 By luoxinhua
 * @copyRight 		Copyright (c) 2013-2017 http://www.1171102882@qq.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.productpopo, model.productmodel');
HClass::import(HResponse::getCurThemePath() . '.action.HhnshAction');

/**
 * 产品的动作类 
 * 
 * 主要处理主页的相关请求动作 
 * 
 * @author 			luoxinhua <1171102882@qq.com.com>
 * @package 		app.front.action
 * @since 			1.0.0
 */
class ProductAction extends HhnshAction 
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
        $this->_popo        = new ProductPopo();
        $this->_model       = new ProductModel($this->_popo);
        $this->_linkedData  = HClass::quickLoadModel('linkeddata');
        $this->_linkedData->setRelItemModel('user', 'product');
    }

    /**
     * linkeddata
     * @var [type]
     */
    private $_linkedData;

    /**
     * 产品详情
     * @return [type] [description]
     */
    public function index()
    {
        $id     = HRequest::getParameter('id');
        $this->_verifyJSONIsEmpty($id, '编号ID');
        $record     = $this->_model->getRecordByWhere('`id` = ' . $id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '记录不存在'));
        }
        $record['image_path'] = HString::formatImage($record['image_path']);
        if($record['company_id'] > 0) {
            $companyInfo    = HClass::quickLoadModel('company')->getRecordById($record['company_id']);
            $companyInfo['image_path'] = HString::formatImage($companyInfo['image_path']);
            $companyInfo['total_huodong'] = $this->_getHuoDongTotal($companyInfo['id']);
            $companyInfo['is_collect'] = $this->_isDianPuFocus(HRequest::getParameter('user_id'), $companyInfo['id']);
        }
        $record['is_collect'] = $this->_isCollect(HRequest::getParameter('user_id'), $record['id']);
        $record['album']= $this->_getProductAlbumList($record['hash']);
        array_push($record['album'], array('image_path' => $record['image_path']));
        $record['total_message'] = $this->_getProductTotalMessage($record['id']);
		$record['content'] = HString::decodeHtml($record['content']);
        $result     = array(
            'record' => $record,
            'companyInfo' => $companyInfo
        );
        $this->_addUserVisist($record['id'], HRequest::getParameter('user_id'));
        if(1 > $this->_model->editByWhere(array('total_visits' => $record['total_visits'] + 1), '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
        }

        HResponse::json(array('rs' => true, 'data' => $result));
    }

    /**
     * 得到活动总个数
     * @return [type] [description]
     */
    private function _getHuoDongTotal($id)
    {
        return $this->_model->getTotalRecords('`company_id` = ' . $id);
    }

    /**
     * 得到产品总咨询数
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function _getProductTotalMessage($id)
    {   
       return HClass::quickLoadModel('message')->getTotalRecords('`product_id` = ' . $id);
    }

    /**
     * 得到产品列表
     * @return [type] [description]
     */
    private function _getProductList($id)
    {
        if(!$id) {
            return;
        }
        $list = HClass::quickLoadModel('product')->getAllRows('`parent_id` = ' . $id);

        return $list;
    }

    /**
     * 验证添加的数据
     * @return [type] [description]
     */
    protected function _verifyAddData()
    {
        $data       = array();
        $this->_user         = HClass::quickLoadModel('user');
        $userId     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户id');
        $userInfo = $this->_verifyUserOpenId(HRequest::getParameter('open_id'), $userId);
        $data['name']        = $this->_verifyJSONIsEmpty(HRequest::getParameter('name'), '活动名称');
        $data['address']     = $this->_verifyJSONIsEmpty(HRequest::getParameter('address'), '活动地址');
        $data['parent_id']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('parent_id'), '所属分类');
        $data['start_date']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('start_date'), '活动开始时间');
        $data['end_date']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('end_date'), '活动结束时间');
        $data['price']      = HRequest::getParameter('price');
        $data['description']= HRequest::getParameter('description');
        $data['latitude']    = $this->_verifyJSONIsEmpty(HRequest::getParameter('latitude'), '地图经度');
        $data['longitude']   = $this->_verifyJSONIsEmpty(HRequest::getParameter('longitude'), '地图纬度');
        $data['status']      = HRequest::getParameter('status');
        $data['city_id']     = HRequest::getParameter('city_id') ? HRequest::getParameter('city_id') : 0;
        if(HRequest::getParameter('company_id')) {
            $data['company_id'] = HRequest::getParameter('company_id');
        }

        return $data;
    }

      /**
     * 更新完成后其他数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function _updateFinishedData($id)
    {
        $record     = $this->_model->getRecordById($id);
        $company    = HClass::quickLoadModel('company');
        if(HRequest::getParameter('company_id')) {
            $companyInfo = $company->getRecordByWhere('`id` = ' . HRequest::getParameter('company_id'));    
        }
        $userInfo   = $this->_user->getRecordByWhere('`id` = ' . HRequest::getParameter('user_id'));
        $name       = $this->_verifyJSONIsEmpty(HRequest::getParameter('company_name'), '活动方名称');
        $username   = $this->_verifyJSONIsEmpty(HRequest::getParameter('username'), '联系人');
        $phone      = $this->_verifyJSONIsEmpty(HRequest::getParameter('phone'), '联系方式');
        if($companyInfo) {
            if(1 > $company->editByWhere(array('total_products' => $companyInfo['total_products'] + 1), '`id` = ' . $companyInfo['id'])) {
                HResponse::json(array('rs' => false, 'message' => '更新活动数失败'));
            }
        }else{
            $companyInfo         = array(
                'name' =>   $name,
                'parent_id' => 566,
                'username' => $userInfo['name'],
                'userid'   => $userInfo['id'],
                'address'  => $record['address'],
                'total_products' => 1,
                'username' => $username,
                'phone' => $phone,
                'image_path'=> $data['image_path'],
            );
            $companyId = $company->add($companyInfo);
            if(1 > $companyId) {
                HResponse::json(array('rs' => false, 'message' => '添加活动方失败'));
            }
            if(1 > $this->_model->editByWhere(array('company_id' => $companyId), '`id` = ' . $id)) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));
            }
        }
    }   

    /**
     * 加载额外的数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _assignRecordOtherInfo($record)
    {
        $record['attrs']    = json_decode($record['attrs'], true);
        $record['image_path'] = HString::formatImage($record['image_path']);
        $companyInfo    = HClass::quickLoadModel('company')->getRecordById($record['company_id']);
        $arriveTime    = $this->_getArriveTime($companyInfo);
        $record['qishou_money']     = $companyInfo['qishou_money'];
        $record['companyInfo']  = $companyInfo;
        $record['arriveTime']   = $arriveTime;

        return $record;
    }    

    /**
     * 得到达到的时间
     * @param  [type] $companyInfo [description]
     * @return [type]              [description]
     */
    private function _getArriveTime($companyInfo)
    {
        $curTime = date('H:i', time());
        $default = 1800; //默认半个小时时间差到达
        $weekarray=array("日","一","二","三","四","五","六"); //先定义一个数组
        //1800 半个小时之内到达
        //在服务时间区间之内
        if(strtotime($curTime) > strtotime($companyInfo['start_date']) && strtotime($curTime) < strtotime($companyInfo['end_date'])) {
            $arriveTime = date('H:i', strtotime($curTime) + $default);
            $date   = date('m月d日') . '(周'.$weekarray[date("w")] . ')' . $arriveTime;
        }
        //还没到服务开始时间
        if(strtotime($curTime) < strtotime($companyInfo['start_date'])) {
            $arriveTime = date('H:i', strtotime($companyInfo['start_date']) + $default);
            $date   = date('m月d日') . '(周'.$weekarray[date("w")] . ')' . $arriveTime;   
        }
        //已经过了服务结束时间
        if(strtotime($curTime) > strtotime($companyInfo['end_date'])) {
            //第二天服务开始时间
            $arriveTime = date('H:i', strtotime($companyInfo['start_date']) + $default);   
            $date   = date('m月d日') . '(周'.$weekarray[date("w") + 1] . ')' . $arriveTime;
        }

        return $date;
    }

    /**
     * 得到分类子菜单
     * @return [type] [description]
     */
    public function subcategory()
    {
        $id     = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), '分类ID');
        $isParent = HRequest::getParameter('is_parent');
        if($isParent) {
            $list   = $this->_category->getSubCategoryByRows($id);
            foreach($list as $key => &$item) {
                $item['image_path'] = HResponse::url() . $item['image_path'];
                $item['is_parent'] = 0;
                if($item['id'] == $id) {
                    $item['is_parent'] = 1;
                }
            }
        }else{
            $record = $this->_category->getRecordById($id);
            if(!$record) {
                HResponse::json(array('rs' => false, 'message' => '记录不存在'));
                exit();
            }
            $list   = $this->_category->getSubCategoryByRows($record['parent_id']);
            foreach($list as $key => &$item) {
                $item['is_parent'] = 0;
                $item['image_path'] = HResponse::url() . $item['image_path'];
                if($item['id'] == $record['parent_id']) {
                    $item['is_parent'] = 1;
                }
            }
        }
        $list = HArray::arraySort($list, 'is_parent', 'desc');
        $result = array_values($list);
        
        HResponse::json(array('rs' => true, 'data' => $result));
    }

    /**
     * 得到购物车下的商品列表
     * @return [type] [description]
     */
    public function shopcartlist()
    {
        $ids    = trim($this->_verifyJSONIsEmpty(HRequest::getParameter('ids'), '产品ID列表'));
        $userId = HRequest::getParameter('user_id');
        if($ids == '*') {
            HResponse::json(array('rs' => false, 'message' => '请求错误'));
            return;
        }
        $where  = '`id` in (' . $ids . ')';
        $record   = $this->_model->getRecordByWhere($where);
        $record['image_path'] = HString::formatImage($record['image_path']);
        $record['start_date']       = $this->_formatProductDate($record['start_date'], $record['end_date']);
        $productTypeList = HClass::quickLoadModel('producttype')->getAllRows('`parent_id` = ' . $record['id']);
        $orderProduct = HClass::quickLoadModel('orderproduct');
        $order        = HClass::quickLoadModel('order');
        foreach($productTypeList as &$productType) {
            $productType['select'] = false;
            $productType['num']    = 1;
            //判断有没有已购票
            if($userId > 0) {
                $orderProductRecord = $orderProduct->getRecordByWhere('`user_id` = ' . $userId . ' AND `producttype_id` = ' . $productType['id']);
                if($orderProductRecord) {
                    $orderRecord = $order->getRecordByWhere('`id` = ' . $orderProductRecord['parent_id']);
                    if(in_array($orderRecord['status'], array(2,3,4,5,7,8))) {
                        $productType['status'] = 3;
                    }
                }
            }
            
        }
        $record['productTypeList'] = $productTypeList;

        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 得到列表数据
     * @return [type] [description]
     */
    public function lists()
    {
        $where  = $this->_getListWhere();
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 0;
        $list   = $this->_model->getListByFields('*', $where, $page, 10);
        $list   = $this->_formatList($list);
        $advList = $this->_getAdvList();

        HResponse::json(array('rs' => true, 'data' => array('productList' => $list, 'advList' => $advList)));
    }   

    /**
     * 单一的关键词搜索
     * @return [type] [description]
     */
    public function searchbykeyword()
    {
        $where = '`status` = 1 ';
        //城市搜索
        if(HRequest::getParameter('city')) {
            $area = HClass::quickLoadModel('area')->getRecordByWhere('`name` = \'' . HRequest::getParameter('city') . '\' AND `level` = 2');
            if($area) {
                $where .= ' AND `city_id` = ' . $area['id'];
            }
        } 
        //关键词搜索
        if(HRequest::getParameter('keyword')) {
            $where .= ' AND `name` LIKE \'%' . HRequest::getParameter('keyword') . '%\'';
        }
        $list   = $this->_model->getAllRowsByFields('*', $where);
        $list   = $this->_formatList($list);
        $this->_addUserKeyword(HRequest::getParameter('keyword'), $list);

        HResponse::json(array('rs' => true, 'data' => $list));
    }

     /**
     * 添加用户的关键词搜索记录
     * @param [type] $keyword [description]
     */
    private function _addUserKeyword($keyword, $list)
    {
        if(!$keyword) {
            return;
        }
        $userId = HRequest::getParameter('user_id');
        $relId = '';
        $temp  = '';
        foreach($list as $key => $item) {
            $relId .= $temp . $item['name'];
            $temp   = ',';
        }
        $data = array(
            'name' => $keyword,
            'parent_id' => $userId,
            'city' => HRequest::getParameter('city'),
            'rel_id' => $relId
        );
        if(1 > HClass::quickLoadModel('userkeyword')->add($data)) {
            HResponse::json(array('rs' => false, 'message' => '关键词记录失败'));
        }
        $hotkeyword = HClass::quickLoadModel('hotkeyword');
        $record = $hotkeyword->getRecordByWhere('`name` = \'' . $keyword . '\'');
        if($record) {
            if(1 > $hotkeyword->editByWhere(array('total_visits' => $record['total_visits'] + 1), '`id` = ' . $record['id'])) {
                HResponse::json(array('rs' => false, 'message' => '更新热搜失败'));
            }
        }else{
            if(1 > $hotkeyword->add(array('name' => $keyword, 'total_visits' => 1))) {
                HResponse::json(array('rs' => false, 'message' => '更新热搜失败'));
            }
        }

    }


    /**
     * 搜索列表
     * @return [type] [description]
     */
    public function search()
    {
        $where = $this->_getSearchWhere();
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 0;
        $list   = $this->_model->getListByFields('*', $where, $page, 10);
        $list   = $this->_formatList($list);
        $advList = $this->_getAdvList();
        $catList = $this->_getSearchCategoryList();

        HResponse::json(array('rs' => true, 'data' => array('productList' => $list, 'advList' => $advList, 'catList' => $catList)));
    }

     /**
     * 得到列表的查询条件
     * @return [type] [description]
     */
    protected function _getSearchWhere()
    {
        //搜索条件
        $where = '`status` = 1 ';
        //分类搜索
        if(HRequest::getParameter('parent_id')) {
            $parentId   = HRequest::getParameter('parent_id');
            $where      = HSqlHelper::whereInByListMap(
                'parent_id',
                'id',
                $this->_category->getSubCategoryByRows($parentId)            
            );    
        }
        //城市搜索
        if(HRequest::getParameter('city')) {
            $area = HClass::quickLoadModel('area')->getRecordByWhere('`name` = \'' . HRequest::getParameter('city') . '\' AND `level` = 2');
            if($area) {
                $where .= ' AND `city_id` = ' . $area['id'];
            }
        } 
        //关键词搜索
        if(HRequest::getParameter('keyword')) {
            $where .= ' AND `name` LIKE \'%' . HRequest::getParameter('keyword') . '%\'';
        }
        //价格搜索 免费
        if(HRequest::getParameter('price') && HRequest::getParameter('price') == 1) {
            $where .= ' AND `price` = 0';
        }
        //价格搜索 收费
        if(HRequest::getParameter('price') && HRequest::getParameter('price') == 2) {
            $where .= ' AND `price` > 0';   
        }
        //时段搜索
        if(HRequest::getParameter('date')) {
            //今天
            if(HRequest::getParameter('date') == 1) {

            }
            //明天
            if(HRequest::getParameter('date') == 2) {
                
            }
            //本周
            if(HRequest::getParameter('date') == 3) {

            }
            //本周末
            if(HRequest::getParameter('date') == 4) {

            }
            //本月
            if(HRequest::getParameter('date') == 5) {

            }
        }
        //排序条件
        if(HRequest::getParameter('orderBy')) {
            $this->_popo->setFieldAttribute('id', 'is_order', null);
            $this->_popo->setFieldAttribute(HRequest::getParameter('orderBy'), 'is_order', 'desc');
        }
        return $where;
    }

    /**
     * 得到搜索的分类列表
     * @return [type] [description]
     */
    private function _getSearchCategoryList()
    {
        $record = $this->_category->getRecordByWhere('`identifier` = \'product-cat\'');
        $jingXuanCat = $this->_category->getRecordByWhere('`identifier` = \'jingxuan-cat\'');
        $list   = $this->_category->getSomeRowsByFields(4, '`id`, `name`', '`parent_id` = ' . $record['id'] . ' AND `id` !=' . $jingXuanCat['id']);
        array_unshift($list, array('id' => 0, 'name' => '全部'));

        $allList = $this->_category->getAllRowsByFields('`id`, `name`', '`parent_id` = ' . $record['id'] . ' AND `id` !=' . $jingXuanCat['id']);
        array_unshift($allList, array('id' => 0, 'name' => '全类型'));        

        return array('list' => $list, 'alllist' => $allList);
    }

       /**
     * 得到广告列表
     * @return [type] [description]
     */
    private function _getAdvList()
    {
        $adv    = HClass::quickLoadModel('adv');
        if(!HRequest::getParameter('parent_id')) {
            return;
        }
        $record = $this->_category->getRecordByWhere('`id` = ' . HRequest::getParameter('parent_id'));
        $advList = $adv->getSomeRowsByFields(4, '*', '`parent_id` = ' . $record['id']);
        foreach($advList as $key => &$item) {
            $item['image_path']     = HString::formatImage($item['image_path']);
        }

        return $advList;
    }

    /**
     * 得到列表的查询条件
     * @return [type] [description]
     */
    protected function _getListWhere()
    {
        $parentId   = $this->_verifyJSONIsEmpty(HRequest::getParameter('parent_id'), '所属分类');
        $where      = HSqlHelper::whereInByListMap(
            'parent_id',
            'id',
            $this->_category->getSubCategoryByRows($parentId)            
        );
         if(HRequest::getParameter('city')) {
            $area = HClass::quickLoadModel('area')->getRecordByWhere('`name` = \'' . HRequest::getParameter('city') . '\' AND `level` = 2');
            if($area) {
                $where .= ' AND `city_id` = ' . $area['id'];
            }
        } 

        return $where;
    }

    /**
     * 格式化列表数据
     * @param  [type] $list [description]
     * @return [type]       [description]
     */
    private function _formatList($list)
    {
        $companyMap = HArray::turnItemValueAsKey(
            HClass::quickLoadModel('company')->getAllRowsByFields(
                '`id`, `name`, `address`', 
                HSqlHelper::whereInByListMap('id', 'company_id', $list)), 
            'id'
        );
        $areaMap = HArray::turnItemValueAsKey(HClass::quickLoadModel('area')->getAllRowsByFields(
            '`id`, `name`', 
            HSqlHelper::whereInByListMap('id', 'city_id', $list)
        ), 'id');
        foreach($list as $key => &$item) {
            $item['image_path'] = HString::formatImage($item['image_path']);
            $item['attrs'] = json_decode(HString::decodeHtml($item['attrs']), true);
            $item['shop']  = $companyMap[$item['company_id']]['name'];
            $item['address'] = $companyMap[$item['company_id']]['address'];
            $item['start_date']       = $this->_formatProductDate($item['start_date'], $item['end_date']);
            $item['area']   = $areaMap[$item['city_id']]['name'];
        }

        return $list;
    }

    /**
     * 认证活动
     * @return [type] [description]
     */
    public function renzheng()
    {
        $where  = '`is_renzheng` = 3 AND `status` = 1';
         if(HRequest::getParameter('city')) {
            $area = HClass::quickLoadModel('area')->getRecordByWhere('`name` = \'' . HRequest::getParameter('city') . '\' AND `level` = 2');
            if($area) {
                $where .= ' AND `city_id` = ' . $area['id'];
            }
        } 
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 0;
        $list   = $this->_model->getListByFields('*', $where, $page, 5);
        foreach($list as $key => &$item) {
            $item['attrs'] = json_decode(HString::decodeHtml($item['attrs']), true);
            $item['image_path'] = HString::formatImage($item['image_path']);
            $item['start_date'] = $this->_formatProductDate($item['start_date'], $item['end_date']);
        }

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 用户浏览列表
     * @return [type] [description]
     */
    public function visists()
    {
        $userid     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $this->_linkedData->setRelItemModel('product', 'user');
        $list       = $this->_linkedData->getAllRows('`rel_id` = ' . $userid);
        $where      = HSqlHelper::whereInByListMap('id', 'item_id', $list);
        $productMap = HArray::turnItemValueAsKey(
            $this->_model->getAllRowsByFields('*', $where),
            'id'
        );
        $result  = array();
        $today   = date('Y-m-d', time());
        $yestoday= date('Y-m-d', time() - 24*3600);
        foreach($list as $key => &$item) {
            $date = date('Y-m-d', strtotime($item['create_time']));
            $result[$date]['list'] = array();
            $result[$date]['date']      = $date;
            switch ($date) {
                case $today:
                    $result[$date]['date_name'] = '今日';
                    break;
                case $yestoday:
                    $result[$date]['date_name'] = '昨日';       
                    break;
                default:
                    $result[$date]['date_name'] = $date;       
                    break;
            }

        }
        $result = HArray::arraySort($result, 'date', 'desc');
        foreach($list as $key => &$item) {
            $product = $productMap[$item['item_id']];
            $product['attrs'] = json_decode(HString::decodeHtml($product['attrs']), true);
            $product['image_path'] = HString::formatImage($product['image_path']);
            $product['start_date'] = $this->_formatProductDate($product['start_date'], $product['end_date']);
            $item['product']    = $product;
            $date = date('Y-m-d', strtotime($item['create_time']));
            array_push($result[$date]['list'], $item);
        }

        HResponse::json(array('rs' => true, 'data' => $result));
    }

    /**
     * 添加用户的访问情况
     * @param [type] $productId [description]
     */
    private function _addUserVisist($productId, $userId)
    {
        $this->_linkedData->setRelItemModel('product', 'user');
        $today   = date('Y-m-d', time());
        $tomorrow = date('Y-m-d', time() + 24*3600);
        $where  = '`item_id` = ' . $productId . ' AND `rel_id` = ' . $userId . ' AND `create_time` > \'' . $today . '\' AND `create_time` < \'' . $tomorrow . '\'';
        $record = $this->_linkedData->getRecordByWhere($where);
        if($record) {
            if(1 > $this->_linkedData->editByWhere(array('extend' => $record['extend'] + 1, 'create_time' => date('Y-m-d H:i:s', time())), '`id` = ' . $record['id'])) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));
            } 
        }else{
            $data = array('item_id' => $productId, 'rel_id' => $userId, 'extend' => 1, 'create_time' => date('Y-m-d H:i:s', time()));
            if(1 > $this->_linkedData->add($data)) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));  
            }
        }
    }

    /**
     * 用户收藏列表
     * @return [type] [description]
     */
    public function collect()
    {
        $userid     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $list       = $this->_linkedData->getAllRows('`item_id` = ' . $userid . ' AND `extend` = 1');
        $where      = HSqlHelper::whereInByListMap('id', 'rel_id', $list);
        $productMap = HArray::turnItemValueAsKey(
            $this->_model->getAllRowsByFields('*', $where),
            'id'
        );
        foreach($list as $key => &$item) {
            $product = $productMap[$item['rel_id']];
            $product['attrs'] = json_decode(HString::decodeHtml($product['attrs']), true);
            $product['image_path'] = HString::formatImage($product['image_path']);
            $product['start_date'] = $this->_formatProductDate($product['start_date'], $product['end_date']);
            $item['product']    = $product;
        }

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 添加收藏夹
     * @return [type] [description]
     */
    public function addcollect()
    {
        $userid     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), 'id');
        $extend     = HRequest::getParameter('extend');
        $record     = $this->_model->getRecordById($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '产品记录不存在'));
            return ;
        }
        $where      = '`item_id` = ' . $userid . ' AND `rel_id` = ' . $id;
        $linkeddata = $this->_linkedData->getRecordByWhere($where);
        if($linkeddata) {
            if(1 > $this->_linkedData->editByWhere(array('extend' => $extend), '`id` = ' . $linkeddata['id'])) {
                HResponse::json(array('rs' => false, 'message' => '产品收藏修改失败'));
            }
        }else{
            $data   = array(
                'item_id'   => $userid,
                'rel_id'    => $id,
                'extend'    => $extend
            );
            if(1 > $this->_linkedData->add($data)) {
                HResponse::json(array('rs' => false, 'message' => '产品收藏添加失败'));
            }
        }
        $record['total_collect'] = $extend > 0 ? $record['total_collect'] + 1 : $record['total_collect'] - 1;
        if(1 > $this->_model->editByWhere($record, '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '产品收藏数修改失败'));
        }

        HResponse::json(array('rs' => true, 'data' => array('is_collect' => $extend, 'total_collect' => $record['total_collect'])));
    }

    /**
     * 判断产品是否已经被关注
     * @param  [type]  $userid [description]
     * @param  [type]  $id     [description]
     * @return boolean         [description]
     */
    private function _isCollect($userid, $id)
    {
        $where      = '`item_id` = ' . $userid . ' AND `rel_id` = ' . $id;
        $linkeddata = $this->_linkedData->getRecordByWhere($where);
        if($linkeddata && $linkeddata['extend'] > 0) {
            return 1;
        }

        return 0;
    }

      /**
     * 添加关注
     * @return [type] [description]
     */
    public function addfocus()
    {
        $userid     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '用户ID');
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), 'id');
        $extend     = HRequest::getParameter('extend');
        $record     = HClass::quickLoadModel('company')->getRecordById($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '店铺记录不存在'));
            return ;
        }
        $where      = '`item_id` = ' . $userid . ' AND `rel_id` = ' . $id;
        $this->_linkedData->setRelItemModel('user', 'focus');
        $linkeddata = $this->_linkedData->getRecordByWhere($where);
        if($linkeddata) {
            if(1 > $this->_linkedData->editByWhere(array('extend' => $extend), '`id` = ' . $linkeddata['id'])) {
                HResponse::json(array('rs' => false, 'message' => '店铺关注修改失败'));
            }
        }else{
            $data   = array(
                'item_id'   => $userid,
                'rel_id'    => $id,
                'extend'    => $extend
            );
            if(1 > $this->_linkedData->add($data)) {
                HResponse::json(array('rs' => false, 'message' => '店铺关注添加失败'));
            }
        }
        $record['total_collects'] = $extend > 0 ? $record['total_collects'] + 1 : $record['total_collects'] - 1;
        if(1 > HClass::quickLoadModel('company')->editByWhere($record, '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '店铺收藏数修改失败'));
        }

        HResponse::json(array('rs' => true, 'data' => array('is_collect' => $extend, 'total_collects' => $record['total_collects'])));
    }

    /**
     * 判断店铺是否已经被关注
     * @param  [type]  $userid [description]
     * @param  [type]  $id     [description]
     * @return boolean         [description]
     */
    private function _isDianPuFocus($userid, $id)
    {
        $where      = '`item_id` = ' . $userid . ' AND `rel_id` = ' . $id;
        $this->_linkedData->setRelItemModel('user', 'focus');
        $linkeddata = $this->_linkedData->getRecordByWhere($where);
        if($linkeddata && $linkeddata['extend'] > 0) {
            return 1;
        }

        return 0;
    }

      /**
     * 得到相册列表
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function _getProductAlbumList($hash)
    {
        $this->_linkedData->setRelItemModel('product', 'resource');
        $list = $this->_linkedData->getAllRows('`rel_id` = \'' . $hash . '\'');
        $resourceMap = HArray::turnItemValueAsKey(HClass::quickLoadModel('resource')->getAllRowsByFields(
            '`id`, `path`, `name`', 
            HSqlHelper::whereInByListMap('id', 'item_id', $list)
        ), 'id');
        foreach($list as $key => &$item) {
            $item['image_path'] = HString::formatImage($resourceMap[$item['item_id']]['path']);
        }

        return $list;
    }

    /**
     * 商品上下架
     * @return [type] [description]
     */
    public function shangxiajia()
    {
        $this->_user= HClass::quickLoadModel('user');
        $openid     = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), 'openid');
        $userid     = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), 'userid');
        $id         = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), 'id');
        $status     = $this->_verifyJSONIsEmpty(HRequest::getParameter('status'), '状态值');
        $userInfo   = $this->_verifyUserOpenId($openid, $userid);
        $companyInfo= HClass::quickLoadModel('company')->getRecordByWhere('`userid` = ' . $userInfo['id']);
        $record     = $this->_model->getRecordById($id);
        if($record['company_id'] != $companyInfo['id']) {
            HResponse::json(array('rs' => false, 'message' => '对不起您的权限不够'));
            exit();
        }
        if(!in_array($status, array(1,3))) {
            HResponse::json(array('rs' => false, 'message' => '状态值不够'));
            exit();
        }
        //$status = $status > 1 ? 1 : 2;
        if(1 > $this->_model->editByWhere(array('status' => $status),  '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
            exit();
        }

        HResponse::json(array('rs' => true));
    }

    /**
     * 人气榜列表
     * @return [type] [description]
     */
    public function rank()
    {
        $where = '`status` = 1';
       if(HRequest::getParameter('city')) {
            $area = HClass::quickLoadModel('area')->getRecordByWhere('`name` = \'' . HRequest::getParameter('city') . '\' AND `level` = 2');
            if($area) {
                $where .= ' AND `city_id` = ' . $area['id'];
            }
        } 
        if(HRequest::getParameter('parent_id')) {
            $where .= ' AND ' . HSqlHelper::whereInByListMap('parent_id', 'id', $this->_category->getSubCategory(HRequest::getParameter('parent_id')));
        }
        $sortType = HRequest::getParameter('sort_type') ? HRequest::getParameter('sort_type') : 'total_visits';
        $areaList = $this->_getAreaList();
        $catList  = $this->_getCatListByRank();
        $this->_popo->setFieldAttribute('id', 'is_order', null);
        $this->_popo->setFieldAttribute($sortType, 'is_order', 'desc');
        $list = $this->_model->getSomeRowsByFields(10, '*', $where);
        $list = $this->_formatList($list);
        $result = array(
            'areaList'  => $areaList,
            'catList'   => $catList,
            'list'      => $list
        );

        HResponse::json(array('rs' => true, 'data' => $result));
    }

    /**
     * 得到人气榜城市的列表
     * @return [type] [description]
     */
    private function _getAreaList()
    {
        $list = $this->_model->getGroupListBySql('select city_id from hjz_product GROUP BY city_id');
        $areaList = HClass::quickLoadModel('area')->getAllRowsByFields('`id`, `name`', HSqlHelper::whereInByListMap('id', 'city_id', $list));

        return $areaList;
    }

    /**
     * 得到分类列表
     * @return [type] [description]
     */
    private function _getCatListByRank()
    {
        $record = $this->_category->getRecordByWhere('`identifier` = \'product-cat\'');
        $jingXuanCat = $this->_category->getRecordByWhere('`identifier` = \'jingxuan-cat\'');
        if($record) {
            return $this->_category->getAllRowsByFields('`id`, `name`', '`parent_id` = ' . $record['id'] . ' AND `id` != ' . $jingXuanCat['id']);
        }
    }   

    /**
     * 活动日历单击某一天搜索
     * @return [type] [descripdetion]
     */
    public function daysearch()
    {
        $year   = $this->_verifyJSONIsEmpty(HRequest::getParameter('year'), '年');
        $month  = intval($this->_verifyJSONIsEmpty(HRequest::getParameter('month'), '月'));
        $day    = intval($this->_verifyJSONIsEmpty(HRequest::getParameter('day'), '日')); 
        $where  = $this->_getDayWhere($year, $month, $day);
        $list   = $this->_model->getAllRowsByFields('*', $where);
        foreach($list as $key => &$item) {
            $item['attrs'] = json_decode(HString::decodeHtml($item['attrs']), true);
            $item['image_path'] = HString::formatImage($item['image_path']);
            $item['start_date'] = date('m/d', strtotime($item['start_date']));
            $item['end_date']   = date('m/d', strtotime($item['end_date']));
        }
        $dayArr = range(1, 31);
        $dayMap = array();
        foreach($dayArr as $key => $day) {
            $where  = $this->_getDayWhere($year, $month, $day);
            $total  = $this->_model->getTotalRecords($where);
            $dayMap[$day] = $total;
        }

        HResponse::json(array('rs' => true, 'data' => array('list' => $list, 'daymap' => $dayMap)));
    }

    /**
     * 得到日期时间条件
     * @param  [type] $year  [description]
     * @param  [type] $month [description]
     * @param  [type] $day   [description]
     * @return [type]        [description]
     */
    private function _getDayWhere($year, $month, $day)
    {   
        $month  = $month < 10 ? '0' . $month : $month;
        $curDay = $day < 10 ? '0' . $day : $day; //当天凌晨
        $nextDay= ($day + 1); //明天凌晨
        $nextDay= $nextDay < 10 ? '0' . $nextDay : $nextDay;
        //$where  = '`start_date` > \'' . $year . '-' . $month . '-' . $curDay . '\' AND `start_date` < \'' . $year . '-' . $month . '-' . $nextDay . '\'';
        $where  = '`start_date` > \'' . $year . '-' . $month . '-' . $curDay . '\' AND `end_date` > \'' . $year . '-' . $month . '-' . $curDay . '\'';
        if(HRequest::getParameter('city')) {
            $area = HClass::quickLoadModel('area')->getRecordByWhere('`name` = \'' . HRequest::getParameter('city') . '\' AND `level` = 2');
            if($area) {
                $where .= ' AND `city_id` = ' . $area['id'];
            }
        } 

        return $where;
    }

    /**
     * 活动日历单击某一个月搜索
     * @return [type] [description]
     */
    public function monthsearch()
    {
        $year   = $this->_verifyJSONIsEmpty(HRequest::getParameter('year'), '年');
        $month  = intval($this->_verifyJSONIsEmpty(HRequest::getParameter('month'), '月'));
        $dayArr = range(1, 31);
        $dayMap = array();
        foreach($dayArr as $key => $day) {
            $where  = $this->_getDayWhere($year, $month, $day);
            $total  = $this->_model->getTotalRecords($where);
            $dayMap[$day] = $total;
        }

        HResponse::json(array('rs' => true, 'data' => array('daymap' => $dayMap)));
    }

    /**
     * 我的产品列表
     * @return [type] [description]
     */
    public function mylist()
    {
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '所属用户');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), '权限');
        $this->_user = HClass::quickLoadModel('user');
        $this->_verifyUserOpenId($openId, $userId);
        $companyInfo = HClass::quickLoadModel('company')->getRecordByWhere('`userid` = ' . $userId);
        if(!$companyInfo) {
            HResponse::json(array('rs' => false, 'message' => '对不起您不是活动举办方'));
        }
        $where  = '`company_id` = ' . $companyInfo['id'];
        if(HRequest::getParameter('status')) {
            $where .= ' AND `status` = ' . HRequest::getParameter('status');
        }
        $page   = HRequest::getParameter('page') ? HRequest::getParameter('page') : 0;
        $list   = $this->_model->getListByFields('*', $where, $page, 10);
        $list   = $this->_formatList($list);

        HResponse::json(array('rs' => true, 'data' => $list));
    }

    /**
     * 验证删除数据权限
     * @return [type] [description]
     */
    protected function _verifyDelData($record)
    {
        $userId = $this->_verifyJSONIsEmpty(HRequest::getParameter('user_id'), '所属用户');
        $openId = $this->_verifyJSONIsEmpty(HRequest::getParameter('open_id'), '权限');
        $this->_user = HClass::quickLoadModel('user');
        $this->_verifyUserOpenId($openId, $userId);
        $companyInfo = HClass::quickLoadModel('company')->getRecordByWhere('`userid` = ' . $userId);
        if($companyInfo['id'] != $record['company_id']) {
            HResponse::json(array('rs' => false, 'message' => '对不起您不是活动举办方'));
        }
    }

}