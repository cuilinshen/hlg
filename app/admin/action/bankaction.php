<?php

/**
 * @version			$Id$
 * @create 			2015-06-22 16:06:07 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('config.popo.bankpopo, app.admin.action.AdminAction, model.bankmodel');

/**
 * 支行管理的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.admin.action
 * @since 			1.0.0
 */
class BankAction extends AdminAction
{

    /**
     * 前驱方法
     * @return [type] [description]
     */
    public function beforeAction()
    {
        $this->_hverifyUserActor();
    }

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
        $this->_popo        = new BankPopo();
        $this->_model       = new BankModel($this->_popo);
    }

    /**
     * 验证用户操作权限
     * @return [type] [description]
     */
    private function _hverifyUserActor()
    {
        $actor  = $this->_getUserActor();
        if('editor' == $actor['identifier']) {
            throw new HRequestException('对不起您没有该权限');
        }
        //角色判断
        if(in_array($actor['identifier'], array('root', 'city_member'))) {
            return ;
        }
        if(HRequest::getParameter('id')) {
            $record     = $this->_model->getRecordById(HRequest::getParameter('id'));
            if(!$record) {
                throw new HRequestException('该记录不存在');
            }
            if('county_member' == $actor['identifier'] && $record['parent_id'] != HSession::getAttribute('county_id', 'user')) {
                throw new HRequestException('对不起您没有该权限');
            }
        }
    }

    /**
     * 得到权限查询条件
     * @return [type] [description]
     */
    private function _getActorWhere()
    {
        $actor  = $this->_getUserActor();
        if(in_array($actor['identifier'], array('root', 'city_member'))) {
            return '1 = 1';
        }
        if('county_member' == $actor['identifier']) {
            return '`parent_id` = ' . HSession::getAttribute('county_id', 'user');
        }
    }

    public function index()
    {
        $this->_search($this->_getActorWhere());

        $this->_render('list');
    }

    /**
     * 搜索方法 
     * 
     * @access public
     */
    public function search()
    {
        $where  = $this->_combineWhere();
        $this->_search($where);

        $this->_render('list');
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
     * 添加
     * 
     * {@inheritdoc}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     */
    protected function _otherJobsAfterAddView()
    {
        parent::_otherJobsAfterAddView();
        $this->_assignUserList();
    }

    /**
     * 编辑
     * 
     * {@inheritdoc}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     */
    protected function _otherJobsAfterEditView()
    {
        parent::_otherJobsAfterEditView();
        $this->_assignUserList();
    }

    /**
     * 加载用户列表
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _assignUserList()
    {
        $user   = HClass::quickLoadModel('user');
        HResponse::setAttribute('author_list', $user->getAllRowsByFields('`id`, `name`'));
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
        $actor  = $this->_getUserActor();
        if('county_member' == $actor['identifier']) {
            $list   = $this->_category->getSubCategory(HSession::getAttribute('county_id', 'user'), true);
        }else{
            $list   = $this->_category->getSubCategoryByIdentifier('area', false);
        }
        
        HResponse::setAttribute(
            'parent_id_list', 
            $list
        );
    }

}

?>
