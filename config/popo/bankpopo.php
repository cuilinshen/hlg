<?php 

/**
 * @version			$Id$
 * @create 			2015-06-22 16:06:36 By xjiujiu 
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

/**
 * 模块工具的基本信息类 
 * 
 * 用于记录单模块的配置信息 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		config.popo
 * @since 			1.0.0
 */
class BankPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '网点管理';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'bank';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'category';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_bank';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('sort_num' => array(
            'name' => '排序', 'default' => '9999',
            'verify' => array('null' => false,),
            'comment' => '只能是数字，默认为：当前时间。','is_show' => true, 'is_order' => 'DESC', 
        ),'id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '只能是数字','is_show' => true, 'is_order' => 'DESC', 
        ),'name' => array(
            'name' => '标题', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'parent_id' => array(
            'name' => '所属分类', 'default' => '-1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'phones' => array(
            'name' => '短信接收者', 'default' => null,
            'verify' => array(),
            'comment' => '短信接收手机号，填写方式："role_1":手机号;"role_2":手机号;"role_3":手机号 ','is_show' => true, 
        ),'comment' => array(
            'name' => '简介', 
            'verify' => array(),
            'comment' => '支行说明',
        ),'contact' => array(
            'name' => '网点联系方式', 'default' => null,
            'verify' => array(),
            'comment' => '网点联系方式', 'is_show' => true,
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10','is_show' => true, 
        ),'author' => array(
            'name' => '负责人', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '用户的ID','is_show' => true, 
        ),);

}

?>
