<?php 

/**
 * @version			$Id$
 * @create 			2017-09-05 23:09:00 By xjiujiu 
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
class OrderaddressPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '收货地址';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'orderaddress';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_order_address';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * 性别Map
     * @var array
     */
    public static $_sexMap      = array(
        array('id' => 1, 'name' => '男'),
        array('id' => 2, 'name' => '女')
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => '编号', 
            'verify' => array(),
            'comment' => '系统自动编号','is_show' => true, 'is_order' => 'DESC', 
        ),'parent_id' => array(
            'name' => '所属用户', 'default' => '-1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => false, 
        ),'name' => array(
            'name' => '联系人', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度255','is_show' => true, 
        ),'phone' => array(
            'name' => '联系电话', 
            'verify' => array( 'numeric' => true,),
            'comment' => '联系电话','is_show' => true, 
        ),'province_id' => array(
            'name' => '所属省份', 
            'verify' => array( 'numeric' => true,),
            'comment' => '所属省份','is_show' => true, 
        ),'city_id' => array(
            'name' => '所属城市', 
            'verify' => array( 'numeric' => true,),
            'comment' => '所属城市','is_show' => true, 
        ),'address' => array(
            'name' => '详细地址', 
            'verify' => array( 'numeric' => true,),
            'comment' => '详细地址','is_show' => true, 
        ),'sex' => array(
            'name' => '性别', 'default' => 1,
            'verify' => array(),
            'comment' => '性别','is_show' => true, 
        ),'status' => array(
            'name' => '默认状态', 'default' => '1',
            'verify' => array( 'numeric' => true,),
            'comment' => '1为非默认状态2为默认状态','is_show' => true, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10','is_show' => true, 
        ),'author' => array(
            'name' => '作者', 'default' => '1',
            'verify' => array( 'numeric' => true,),
            'comment' => '用户的ID','is_show' => true, 
        ),);

}

?>
