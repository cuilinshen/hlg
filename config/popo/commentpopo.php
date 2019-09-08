<?php 

/**
 * @version			$Id$
 * @create 			2017-08-30 23:08:16 By xjiujiu 
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
class CommentPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '活动评论';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'comment';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_comment';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * 状态Map
     * @var array
     */
    public static $_statusMap   = array(
        array('id' => 1, 'name' => '正常'),
        array('id' => 2, 'name' => '删除')
    );

    /**
     * 评论类型Map
     * @var array
     */
    public static $_typeMap     = array(
        array('id' => 1, 'name' => '用户对商户的评价'),
        array('id' => 2, 'name' => '用户对骑手的评价')
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => '编号', 
            'verify' => array(),
            'comment' => '系统编号','is_show' => true, 'is_order' => 'DESC', 
        ),'name' => array(
            'name' => '评论人', 
            'verify' => array('null' => false,),
            'comment' => '评论人','is_show' => true, 
        ),'parent_id' => array(
            'name' => '评论人ID', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '评论人ID','is_show' => true, 
        ),'content' => array(
            'name' => '评论内容', 
            'verify' => array('null' => false, 'len' => 500,),
            'comment' => '长度255','is_show' => true, 
        ),'product_id' => array(
            'name' => '被评论的店铺', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '被评论的店铺','is_show' => true, 
        ),'status' => array(
            'name' => '状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1, 正常, 2 删除','is_show' => true, 
        ),'order_id' => array(
            'name' => '评论的订单', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '评论的订单','is_show' => true, 
        ),'total_score' => array(
            'name' => '评分', 
            'verify' => array('null' => false,),
            'comment' => '评分','is_show' => true, 
        ),'type' => array(
            'name' => '评价类别', 'default' => 1,
            'verify' => array('null' => false,),
            'comment' => '评价类别','is_show' => true, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '评论提交时间','is_show' => true, 
        ),'author' => array(
            'name' => '维护人', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '信息最后一次维护人','is_show' => true, 
        ),);

}

?>
