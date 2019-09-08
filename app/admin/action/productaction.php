<?php

/**
 * @version			$Id$
 * @create 			2017-08-31 00:08:01 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.productpopo, app.admin.action.AdminAction, model.productmodel');

/**
 * 产品的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class ProductAction extends AdminAction
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
        $this->_popo        = new ProductPopo();
        $this->_model       = new ProductModel($this->_popo);
        $this->_company     = HClass::quickLoadModel('company');
    }

    /**
     * 公司对象
     * @var [type]
     */
    private $_company;

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
            HArray::turnItemValueAsKey(ProductPopo::$_statusMap, 'id')
        );  
        HResponse::registerFormatMap(
            'is_recommend',
            'name',
            HArray::turnItemValueAsKey(ProductPopo::$_isRecommendMap, 'id')
        );
        HResponse::registerFormatMap(
            'tpl_id',
            'name',
            HArray::turnItemValueAsKey($this->_getTplList(), 'id')
        );  
        HResponse::registerFormatMap(
            'company_id',
            'name',
             HArray::turnItemValueAsKey($this->_getCompanyList($list), 'id')
        );  
        HResponse::registerFormatMap(
            'is_renzheng',
            'name',
             HArray::turnItemValueAsKey(ProductPopo::$_isRenZhengMap, 'id')
        );  
        HResponse::registerFormatMap(
            'city_id',
            'name',
            HArray::turnItemValueAsKey($this->_getAreaList(), 'id')
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
        HResponse::setAttribute('status_list', ProductPopo::$_statusMap);
        HResponse::setAttribute('tpl_id_list', $this->_getTplList());
        HResponse::setAttribute('company_id_list', $this->_getCompanyList());
        HResponse::setAttribute('is_recommend_list', ProductPopo::$_isRecommendMap);
        HResponse::setAttribute('is_renzheng_list', ProductPopo::$_isRenZhengMap);
        HResponse::setAttribute('city_id_list', $this->_getAreaList());
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
        HResponse::setAttribute('status_list', ProductPopo::$_statusMap);
        HResponse::setAttribute('tpl_id_list', $this->_getTplList());
        HResponse::setAttribute('company_id_list', $this->_getCompanyList());
        HResponse::setAttribute('is_recommend_list', ProductPopo::$_isRecommendMap);
        HResponse::setAttribute('is_renzheng_list', ProductPopo::$_isRenZhengMap);
        HResponse::setAttribute('city_id_list', $this->_getAreaList());
        $this->_assignAlbum();
    }

    /**
     * 得到分类列表
     * @return [type] [description]
     */
    public function _getParentIdList()
    {
        $list = $this->_category->getSubCategoryByIdentifier('product-cat', false);

        return $list;
    }

    /**
     * 得到模板列表
     * @return [type] [description]
     */
    public function _getTplList()
    {
        $list = $this->_category->getSubCategoryByIdentifier('dianpu-cat', false);

        return $list;
    }

    /**
     * 得到公司列表
     * @return [type] [description]
     */
    public function _getCompanyList($list)
    {
        if($list) {
            $where  = HSqlHelper::whereInByListMap('id', 'company_id', $list);
        }else{
            $where  = '1=1';
        }
        $list   = HClass::quickLoadModel('company')->getAllRowsByFields('`id`, `name`', $where);

        return $list;
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
        $where      = $this->_getDateWhere();
        if(1 < intval(HRequest::getParameter('type'))) {
            array_push($where, $this->_getParentWhere(HRequest::getParameter('type')));
        }
        if(HRequest::getParameter('lang_id')) {
            array_push($where, '`lang_id` = \'' . HRequest::getParameter('lang_id') . '\'');
        }
        $keyword    = HRequest::getParameter('keywords');
        if($keyword && '关键字...' !== $keyword) {
            array_push($where, $this->_getSearchWhere($keyword));
        }
        if(HRequest::getParameter('status')) {
            array_push($where, '`status` = ' . HRequest::getParameter('status'));
        }
        
        return !$where ? null : implode(' AND ', $where);
    }

    /**
     * 得到城市列表
     * @return [type] [description]
     */
    private function _getAreaList()
    {
        $list   = HClass::quickLoadModel('area')->getAllRowsByFields('`id`, `name`', '`level` = 2');

        return $list;
    }

    /**
     * 活动采集
     * @return [type] [description]
     */
    public function caiji()
    {
        HResponse::setAttribute('city_id_list', $this->_getAreaList());
        $categoryRecord = $this->_category->getRecordByWhere('`identifier` = \'product-cat\'');
        HResponse::setAttribute('category_list', $this->_category->getAllRowsByFields('`id`, `name`', '`parent_id` = ' . $categoryRecord['id']));

        $this->_render('product/caiji');
    }

    /**
     * 执行采集
     * @return [type] [description]
     */
    public function docaiji()
    {
        HClass::import('hongjuzi.filesystem.hdir');
        set_time_limit(0);
        //获取文件列表
        $type = HRequest::getParameter('type');
        $cityId = HRequest::getParameter('city_id');
        $cateId = HRequest::getParameter('cate_id');
        if($type == 1) {
            $dirPath = $this->_parseLuJing($cityId, $cateId);
            $filesList = HDir::getFiles($dirPath);
            //需要做排序
            arsort($filesList);
            $sucss = 0;
            foreach($filesList as $key => $item) {
                 $sucss += $this->_readImagesList($item);
            }

            HResponse::json(array('rs' => true, 'data' => $sucss));
        }
        if($type == 2) {

        }
    }

    /**
     * 读取图片文件列表
     * @param  [type] $filePath [description]
     * @return [type]           [description]
     */
    private function _readImagesList($filePath)
    {
        $result   = HFile::read($filePath);
        $result   = json_decode($result, true);
        $sucss = 0;
        if($result['result']) {
            foreach($result['result'] as $key => $item) {
                if($this->_saveCompanyData($item)) {
                    $sucss ++;
                }
            }
        }

        return $sucss;
    }

    /**
     * 保持公司信息
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function _saveCompanyData($item)
    {
        if(!$item['orgname'] || !$item['orgid']) {
            return $this->_saveProductData($item, 0);
        }
        $record = $this->_company->getRecordByWhere('`orgid` = ' . $item['orgid']);
        if($record) {
             return $this->_saveProductData($item, $record['id']);
        }
        $companySaveDir = 'uploadfiles/company/';
        $data['name'] = $item['orgname'];
        $data['orgid'] = $item['orgid'];
        $data['parent_id'] = 658;
        $data['username'] = '管理员';
        $data['userid'] = 1;
        $data['address'] = $item['address'];
        if($item['orglogo']) {
            $imagename = HRequest::download($item['orglogo'], ROOT_DIR . $companySaveDir);
            $data['image_path'] = $companySaveDir . $imagename;   
        }
        $id = $this->_company->add($data);
        if(1 > $id) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
        }

        return $this->_saveProductData($item, $id);
    }

    /**
     * 保持产品信息
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function _saveProductData($item, $companyId)
    {
        if(!$item['id']) {
            return ;
        }
        $productSaveDir = 'uploadfiles/product/';
        $data['name'] = $item['title'];
        $data['parent_id'] = HRequest::getParameter('cate_id');
        $data['tpl_id'] = 658;
        $data['company_id'] = $companyId;
        if($item['pricearea'] == '免费') {
            $data['price'] = 0;
            $data['marker_price'] = 0;    
        }else{
            $preg = '(\d+\.?\d+)';
            preg_match($preg, $item['pricearea'], $matches);
            $data['price'] = $matches[0];
            $data['marker_price'] = $data['price'];
        }
        $data['address'] = $item['address'];
        $data['attrs'] = '[' . $item['tagstr'] . ']';
        $data['huodong_id'] = $item['id'];
        $data['share_url'] = $item['shareurl'];
        $data['status'] = $item['status'];
        $data['total_number'] = $item['likenum'];
        $data['total_collect'] = $item['likenum'];
        $data['total_visits'] = $item['likenum'];
        $data['is_recommend'] = 2;
        $data['city_id'] = HRequest::getParameter('city_id');
        $data['description'] = $data['name'];
        $data['start_date'] = strtr($item['startutc'], array('T' => ' ', 'Z' => ' '));
        $data['end_date']  = strtr($item['endutc'], array('T' => ' ', 'Z' => ' '));
        $record = $this->_model->getRecordByWhere('`huodong_id` = ' . $item['id']);
        if($record) {
            if(1 > $this->_model->editByWhere($data, '`id` = ' . $record['id'])) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));
            } 
            $id = $record['id'];
        }else{
            if($item['logo']) {
                $imagename = HRequest::download($item['logo'], ROOT_DIR . $productSaveDir);
                $data['image_path'] = $productSaveDir . $imagename;   
            }
            $id = $this->_model->add($data);    
            if(1 > $id) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));
            }
        }
        sleep(1);

        return $id;
    }

    /**
     * 城市Map
     * @var array
     */
    private $_cityMap = array(
        '2' => 'beijing',
    );

    /**
     * 分类Map
     * @var array
     */
    private $_cateMap = array(
        '655' => 'jingxuan',
        '500' => 'hangye',
        '510' => 'shenghuo',
        '556' => 'xuexi',
        '599' => 'qinzi'
    );

    /**
     * 解析路径
     * @return [type] [description]
     */
    private function _parseLuJing($cityId, $cateId)
    {
        $dirPath = ROOT_DIR . 'data'; 
        if(!isset($this->_cityMap[$cityId])) {
            HResponse::json(array('rs' => false, 'message' => '此城市暂未支持数据抓取'));
        }
        $dirPath .= '/' . $this->_cityMap[$cityId] . '/' . $this->_cateMap[$cateId];

        return $dirPath;
    }

}

?>
