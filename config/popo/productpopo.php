<?php 

/**
 * @version			$Id$
 * @create 			2017-08-31 00:08:01 By xjiujiu 
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
class ProductPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '活动';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'product';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_product';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * 状态Map
     * @var array
     */
    public static $_statusMap   = array(
        array('id' => '1', 'name' => '进行中'),
        array('id' => '2', 'name' => '待进行'),
        array('id' => '3', 'name' => '下架'),
        array('id' => '4', 'name' => '已完成'),
    );

     /**
     * 推荐状态Map
     * @var array
     */
    public static $_isRecommendMap   = array(
        array('id' => '1', 'name' => '不推荐'),
        array('id' => '2', 'name' => '推荐'),
    );

    /**
     * 是否认证Map
     * @var array
     */
    public static $_isRenZhengMap   = array(
        array('id' => '1', 'name' => '未认证'),
        array('id' => '2', 'name' => '审核中'),  
        array('id' => '3', 'name' => '已认证'),  
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '只能是数字','is_show' => true, 'is_order' => 'DESC', 
        ),'name' => array(
            'name' => '活动名称', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'parent_id' => array(
            'name' => '所属分类', 'default' => '-1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'tpl_id' => array(
            'name' => '所属模板', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => false, 
        ),'company_id' => array(
            'name' => '所属公司', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'price' => array(
            'name' => '价格', 
            'verify' => array('null' => false,),
            'comment' => '价格','is_show' => true, 
        ),'marker_price' => array(
            'name' => '市场价格', 'default' => 0,
            'verify' => array('null' => false,),
            'comment' => '市场价格','is_show' => false, 
        ),'address' => array(
            'name' => '活动地点', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '活动地点','is_show' => true, 
        ),'image_path' => array(
            'name' => '封面图片', 
            'verify' => array( 'len' => 255,),
            'comment' => '请选择允许的类型。','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120), 'middle' => array(200, 240)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'description' => array(
            'name' => '活动简介', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '活动简介','is_show' => true, 
        ),'content' => array(
            'name' => '详细内容', 
            'verify' => array(),
            'comment' => '长度10000字以内。',
        ),'attrs' => array(
            'name' => '相关标签', 'default' => null,
            'verify' => array('null' => true, 'len' => 255,),
            'comment' => '相关标签，格式为["标签1","标签2"]', 'is_show' => true, 
        ),'start_date' => array(
            'name' => '活动开始时间', 'default' => null,
            'verify' => array('null' => true, 'len' => 255,),
            'comment' => '活动开始时间','is_show' => true, 
        ),'end_date' => array(
            'name' => '活动结束时间', 'default' => null,
            'verify' => array('null' => true, 'len' => 255,),
            'comment' => '活动结束时间','is_show' => true, 
        ),'province_id' => array(
            'name' => '省份ID', 'default' => 0,
            'verify' => array( ),
            'comment' => '省份ID','is_show' => false, 
        ),'city_id' => array(
            'name' => '所属城市', 'default' => 0,
            'verify' => array( 'numeric' => true,),
            'comment' => '城市ID','is_show' => true, 
        ),'total_score' => array(
            'name' => '评分数', 'default' => '0',
            'verify' => array(),
            'comment' => '总评分数','is_show' => true, 
        ),'total_collect' => array(
            'name' => '收藏数', 'default' => '0',
            'verify' => array(),
            'comment' => '总收藏数','is_show' => true, 
        ),'total_visits' => array(
            'name' => '访问数', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '总访问数','is_show' => true, 
        ),'total_number' => array(
            'name' => '库存数', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '总库存数','is_show' => true, 
        ),'total_orders' => array(
            'name' => '订单数', 'default' => '0',
            'verify' => array(),
            'comment' => '总订单数','is_show' => true, 
        ),'total_comments' => array(
            'name' => '总评论数', 'default' => '0',
            'verify' => array(),
            'comment' => '总评论数','is_show' => true, 
        ),'status' => array(
            'name' => '产品状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1为销售中，2下架','is_show' => true, 
        ),'is_recommend' => array(
            'name' => '推荐状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1为不推荐，2推荐','is_show' => true, 
        ),'latitude' => array(
            'name' => '地图经度', 'default' => null,
            'verify' => array(),
            'comment' => '地图经度','is_show' => false, 
        ),'longitude' => array(
            'name' => '地图纬度', 'default' => null,
            'verify' => array(),
            'comment' => '地图纬度','is_show' => false, 
        ),'is_renzheng' => array(
            'name' => '官方认证', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1为未认证，2审核中，3已认证','is_show' => true, 
        ),'hash' => array(
            'name' => '相册编号', 
            'verify' => array('null' => false, 'len' => 32,),
            'comment' => '相册校验码','zoom' => array('small' => array(300, 320)), 'type' => array('.png', '.jpg', '.gif', '.bmp'), 'size' => 0.5,
            'limit' => 5
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
