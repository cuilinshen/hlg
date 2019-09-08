<?php 

/**
 * @version			$Id$
 * @create 			2017-09-30 16:09:29 By xjiujiu 
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
class OrderproductPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '订单商品表';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'orderproduct';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_order_product';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '系统编号','is_show' => true, 'is_order' => 'DESC', 
        ),'name' => array(
            'name' => '商品名称', 
            'verify' => array('null' => false, 'len' => 50,),
            'comment' => '商品名称','is_show' => true, 
        ),'parent_id' => array(
            'name' => '所在订单', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '商品的订单','is_show' => true, 
        ),'product_id' => array(
            'name' => '所属商品ID', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属商品ID','is_show' => true, 
        ),'user_id' => array(
            'name' => '所属用户ID', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属用户ID','is_show' => true, 
        ),'comment_id' => array(
            'name' => '所属评论', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属评论','is_show' => true, 
        ),'producttype_id' => array(
            'name' => '所属活动类别', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属活动类别','is_show' => true, 
        ),'number' => array(
            'name' => '产品数量', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '产品数量','is_show' => true, 
        ),'price' => array(
            'name' => '商品价格', 'default' => '0',
            'verify' => array('null' => false,),
            'comment' => '商品价格','is_show' => true, 
        ),'image_path' => array(
            'name' => '图片', 
            'verify' => array( 'len' => 255,),
            'comment' => '请选择允许的类型。','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10 08:09:09',
        ),'author' => array(
            'name' => '维护员', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '上一次修改的管理员','is_show' => true, 
        ),);

}

?>
