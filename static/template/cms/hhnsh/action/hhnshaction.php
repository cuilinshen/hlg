<?php 

/**
 * @version $Id$
 * @author 1171102882 <1171102882@foxmail.com>
 * @description HongJuZi Framework
 * @copyright Copyright (c) 2011-2012 http://www.1171102882.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('app.cms.action.cmsaction');

/**
 * 怀化农商行父动作类
 * 
 * @author 1171102882 <1171102882@foxmail.com>
 * @package theme.default.action
 * @since 1.0.0
 */
class HhnshAction extends CmsAction
{

    public function __construct()
    {
        parent::__construct();
        $this->_isMobile    = $this->isMobile();
        HResponse::setAttribute('isMobile', $this->_isMobile);
    }

    /**
     * 是否属于移动端或pc端
     * @var [type]
     */
    protected $_isMobile;

    protected $_nav;

    protected $_user;

    /**
     * 公共加载
     * 
     * @author 1171102882 <1171102882@foxmail.com>
     * @access protected
     */
    protected function _commAssign()
    {
        parent::_commAssign();
        $this->_assignMenuList();
    }

    /**
     * 加载菜单列表
     * @return [type] [description]
     */
    protected function _assignMenuList()
    {
        $menuMap    = array('news' => array(), 'qiye' => array());
        foreach($menuMap as $identifier => $value) {
            $menuMap[$identifier] = $this->_category->getSubCategoryByIdentifier($identifier, false);
        }

        HResponse::setAttribute('menuMap', $menuMap);
    }


    //判断是否是手机
    private function isMobile()
    {
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            $is_pc = (strpos($agent, 'windows nt')) ? true : false;
            $is_mac = (strpos($agent, 'mac os')) ? true : false;
            $is_iphone = (strpos($agent, 'iphone')) ? true : false;
            $is_android = (strpos($agent, 'android')) ? true : false;
            $is_ipad = (strpos($agent, 'ipad')) ? true : false;
            if($is_pc){
                  return  false;
            }
            if($is_mac){
                  return  true;
            }
            if($is_iphone){
                  return  true;
            }
            if($is_android){
                  return  true;
            }
            if($is_ipad){
                  return  true;
            }
    }
    
    /**
     * 更新应用总访问量
     * @return [type] [description]
     */
    protected function _updateInformationTotalVisits()
    {
        $information = HClass::quickLoadModel('information');
        $record = $information->getRecordByWhere('`id` = 1');
        if(1 > $information->editByWhere(array('total_visits' => $record['total_visits'] + 1), '`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '同步总访问量错误'));
        }
    }


    /**
     * 验证JSON数据是否为空
     * @param  [type] $value [description]
     * @param  [type] $name  [description]
     * @return [type]        [description]
     */
    protected function _verifyJSONIsEmpty($value, $name)
    {
        if(!$value) {
            echo HResponse::json(array('rs' => false, 'message' =>  $name . '不能为空'));
            exit();
        }

        return $value;
    }

    /**
     * 验证JSON数据是手机号码
     * @param  [type] $phone [description]
     * @return [type]        [description]
     */
    protected function _verifyJSONIsPhone($phone)
    {
        if(!preg_match('/^1[3|4|5|7|8][0-9]{9}$/', $phone)) {
            echo HResponse::json(array('rs' => false, 'message' =>  '手机号码不合法'));
            exit();   
        }

        return $phone;
    }

     /**
     * 验证openid操作对象
     * @param  [type] $openid [description]
     * @param  [type] $userid [description]
     * @return [type]         [description]
     */
    protected function _verifyUserOpenId($openid, $userid)
    {
        $userInfo   = $this->_user->getRecordById($userid);
        if($userInfo['open_id'] != $openid) {
            HResponse::json(array('rs' => false, 'message' => '对不起您没有权限'));
            exit();
        }

        return $userInfo;
    }

    /**
     * 得到一条记录数据
     * @return [type] [description]
     */
    public function record()
    {
        $id     = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), 'ID');
        $record = $this->_model->getRecordById($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '记录不存在'));    
        }else{
            $this->_verifyRecordData($record);
            $record     = $this->_assignRecordOtherInfo($record);
            HResponse::json(array('rs' => true, 'data' => $record));
        }
    }

    /**
     * 验证记录数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _verifyRecordData($record)
    {   
        
    }

    /**
     * 加载额外的记录数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _assignRecordOtherInfo($record)
    {
            return $record;
    }

    /**
     * 删除一条记录
     * @return [type] [description]
     */
    public function delrecord()
    {
        $id     = $this->_verifyJSONIsEmpty(HRequest::getParameter('id'), 'ID');
        $record = $this->_model->getRecordById($id);
        if(!$record) {
            HResponse::json(array('rs' => false, 'message' => '记录不存在'));    
            return;
        }
        $this->_verifyDelData($record);
       if(1 > $this->_model->deleteByWHere('`id` = ' . $record['id'])) {
            HResponse::json(array('rs' => false, 'message' => '删除失败'));    
       }
       $this->_deleteRecordOtherInfo($record);

       HResponse::json(array('rs' => true));
    }

    /**
     * 验证删除数据权限
     * @return [type] [description]
     */
    protected function _verifyDelData($record)
    {

    }

    /**
     * 删除额外的数据
     * @param  [type] $record [description]
     * @return [type]         [description]
     */
    protected function _deleteRecordOtherInfo($record)
    {

    }

    /**
     * 添加/修改公司信息
     */
    public function add()
    {
        $data   = $this->_verifyAddData();
        $id     = HRequest::getParameter('id');
        if($id) {
            $record     = $this->_model->getRecordById($id);
            if(!$record) {
                HResponse::json(array('rs' => false, 'message' => '记录不存在'));
                exit();
            }
            if(1 > $this->_model->editByWhere($data, '`id` = ' . $id)) {
                HResponse::json(array('rs' => false, 'message' => '操作失败'));
                exit();
            }
            $this->_updateFinishedData($id);

            HResponse::json(array('rs' => true, 'data' => $record));
            exit();
        }
        $id     = $this->_model->add($data);
        if(1 > $id) {
            HResponse::json(array('rs' => false, 'message' => '操作失败'));
            exit();
        }
        $record     = $this->_model->getRecordById($id);
        $this->_updateFinishedData($id);
        HResponse::json(array('rs' => true, 'data' => $record));
    }

    /**
     * 验证添加/修改数据
     * @return [type] [description]
     */
    protected function _verifyAddData()  
    {

    }

    /**
     * 更新完成后其他数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function _updateFinishedData($id)
    {

    }

    /**
     * 验证用户与商家的关系
     * @param  [type] $userId    [description]
     * @param  [type] $companyId [description]
     * @return [type]            [description]
     */
    protected function _verifyUserCompany($userId, $companyId)
    {
        $record = HClass::quickLoadModel('company')->getRecordById($companyId);
        if($record['userid'] != $userId) {
            HResponse::json(array('rs' => false, 'message' => '您对该店铺没有权限'));
        }

        return $record;
    }

     /**
     * 计算两点地理坐标之间的距离
     * @param  Decimal $longitude1 起点经度
     * @param  Decimal $latitude1  起点纬度
     * @param  Decimal $longitude2 终点经度 
     * @param  Decimal $latitude2  终点纬度
     * @param  Int     $unit       单位 1:米 2:公里
     * @param  Int     $decimal    精度 保留小数位数
     * @return Decimal
     */
    protected function _getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=1, $decimal=2){

        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if($unit==2){
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);

    } 

     /**
     * 格式化活动的日期
     * @return [type] [description]
     */
    protected function _formatProductDate($startDate, $endDate)
    {
        $weekarray=array("日","一","二","三","四","五","六");
        if(date('Y-m-d', strtotime($startDate)) == date('Y-m-d', strtotime($endDate))) {
            return date('m/d', strtotime($startDate))  . " 周".$weekarray[date("w",strtotime($startDate))];
        }


        return date('m/d', strtotime($startDate))  .'-'. date('m/d', strtotime($endDate));
    }
    
}
