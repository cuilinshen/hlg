<?php 

/**
 * @version			$Id$
 * @create 			2018-05-07 16:05:47 By xjiujiu 
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
class CasePopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '商户案例';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'case';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'company';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_case';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

      /**
     * 状态Map
     * @var array
     */
    public static $_statusMap   = array(
        array('id' => '1', 'name' => '销售中'),
        array('id' => '2', 'name' => '下架'),
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '只能是数字','is_show' => true, 'is_order' => 'DESC', 
        ),'name' => array(
            'name' => '案例名称', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'parent_id' => array(
            'name' => '所属商户', 'default' => '-1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'price' => array(
            'name' => '价格', 
            'verify' => array('null' => false,),
            'comment' => '价格',
        ),'marker_price' => array(
            'name' => '市场价格', 
            'verify' => array('null' => false,),
            'comment' => '市场价格',
        ),'image_path' => array(
            'name' => '图片', 
            'verify' => array( 'len' => 255,),
            'comment' => '请选择允许的类型。','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'description' => array(
            'name' => '服务描述', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '服务描述','is_show' => true, 
        ),'total_score' => array(
            'name' => '评分数', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '总评分数','is_show' => true, 
        ),'total_visits' => array(
            'name' => '访问数', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '总访问数','is_show' => true, 
        ),'total_number' => array(
            'name' => '库存数', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '总库存数','is_show' => true, 
        ),'total_comments' => array(
            'name' => '评论数', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '评论数','is_show' => true, 
        ),'status' => array(
            'name' => '产品状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1为上架，2下架','is_show' => true, 
        ),'content' => array(
            'name' => '详细内容', 'default' => null,
            'verify' => array(),
            'comment' => '长度10000字以内。','is_show' => false, 
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
