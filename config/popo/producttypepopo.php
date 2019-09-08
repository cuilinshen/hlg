<?php 

/**
 * @version			$Id$
 * @create 			2018-07-29 16:07:33 By xjiujiu 
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
class ProducttypePopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '活动类型';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'producttype';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'product';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_product_type';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * 销售状态
     * @var array
     */
    public static $_statusMap   = array(
        array('id' => 1, 'name' => '热销中'),
        array('id' => 2, 'name' => '已售罄')
    );

    /**
     * 是否收费Map
     * @var array
     */
    public static $_typeMap     = array(
        array('id' => 1, 'name' => '免费'),
        array('id' => 2, 'name' => '收费')  
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '只能是数字','is_show' => true, 'is_order' => 'ASC', 
        ),'name' => array(
            'name' => '标题', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'parent_id' => array(
            'name' => '所属活动', 'default' => '-1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'price' => array(
            'name' => '价格', 
            'verify' => array('null' => false,),
            'comment' => '价格,0为免费','is_show' => true, 
        ),'total_number' => array(
            'name' => '库存数', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '总库存数','is_show' => true, 
        ),'total_orders' => array(
            'name' => '报名数', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '已报名数','is_show' => true, 
        ),'status' => array(
            'name' => '销售状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1为抢票中，2已售罄','is_show' => true, 
        ),'huodong_date' => array(
            'name' => '活动时间', 
            'verify' => array( 'len' => 255,),
            'comment' => '活动时间','is_show' => true, 
        ),'remark' => array(
            'name' => '备注', 
            'verify' => array( 'len' => 255,),
            'comment' => '活动备注','is_show' => true, 
        ),'type' => array(
            'name' => '收费状态', 'default' => 1,
            'verify' => array( 'len' => 255,),
            'comment' => '1为免费2为收费','is_show' => true, 
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
