<?php

/**
 * @version			$Id$
 * @create 			2017-08-23 01:08:05 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.companypopo, app.admin.action.AdminAction, model.companymodel');

/**
 * 店铺信息的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class CompanyAction extends AdminAction
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
        $this->_popo        = new CompanyPopo();
        $this->_model       = new CompanyModel($this->_popo);
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
            HArray::turnItemValueAsKey(CompanyPopo::$_statusMap, 'id')
        );  
        HResponse::registerFormatMap(
            'userid',
            'name',
             HArray::turnItemValueAsKey($this->_getUserList(), 'id')
        );  
        HResponse::registerFormatMap(
            'province_id',
            'name',
             HArray::turnItemValueAsKey($this->_getProvinceList($list), 'id')
        );  
        HResponse::registerFormatMap(
            'city_id',
            'name',
             HArray::turnItemValueAsKey($this->_getCityList($list), 'id')
        );
        HResponse::registerFormatMap(
            'is_open',
            'name',
             HArray::turnItemValueAsKey(CompanyPopo::$_isOpenMap, 'id')
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
        HResponse::setAttribute('status_list', CompanyPopo::$_statusMap);
        HResponse::setAttribute('userid_list', $this->_getUserList());
        HResponse::setAttribute('province_id_list', $this->_getProvinceList());
        HResponse::setAttribute('city_id_list', $this->_getCityList());
        HResponse::setAttribute('is_open_list', CompanyPopo::$_isOpenMap);
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
        HResponse::setAttribute('status_list', CompanyPopo::$_statusMap);
        HResponse::setAttribute('userid_list', $this->_getUserList());
        HResponse::setAttribute('province_id_list', $this->_getProvinceList());
        HResponse::setAttribute('city_id_list', $this->_getCityList());
        HResponse::setAttribute('is_open_list', CompanyPopo::$_isOpenMap);
    }

    /**
     * 得到分类列表
     * @return [type] [description]
     */
    public function _getParentIdList()
    {
        $list = $this->_category->getSubCategoryByIdentifier('dianpu-cat', false);

        return $list;
    }

    private function _getUserList($list)
    {
        if($list) {
            $where  = HSqlHelper::whereInByListMap('id', 'userid', $list);
        }else{
            $where = '1=1';
        }
        $list   = HClass::quickLoadMOdel('user')->getAllRowsByFields('`id`, `name`', $where);

        return $list;
    }

    /**
     * 得到省份列表
     * @param  [type] $list [description]
     * @return [type]       [description]
     */
    private function _getProvinceList($list)
    {
        if($list) {
            $where  = HSqlHelper::whereInByListMap('id', 'province_id', $list);
        }else{
            $where = '1=1';
        }
        $list   = HClass::quickLoadMOdel('province')->getAllRowsByFields('`id`, `name`', $where);

        return $list;
    }

    /**
     * 得到城市列表
     * @param  [type] $list [description]
     * @return [type]       [description]
     */
    private function _getCityList($list)
    {
        if($list) {
            $where  = HSqlHelper::whereInByListMap('id', 'city_id', $list);
        }else{
            $where = '1=1';
        }
        $list   = HClass::quickLoadMOdel('city')->getAllRowsByFields('`id`, `name`', $where);

        return $list;
    }

}

?>
