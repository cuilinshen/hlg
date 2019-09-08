<?php 

/**
 * @version			$Id$
 * @create 			2017-08-23 01:08:05 By xjiujiu 
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
class CompanyPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '活动方信息';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'company';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_company';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * 状态Map
     * @var array
     */
    public static $_statusMap   = array(
        array('id' => 1, 'name' => '正在审核'),
        array('id' => 2, 'name' => '申请失败'),
        array('id' => 3, 'name' => '申请成功'),
        array('id' => 4, 'name' => '下架'),
    );

    /**
     * 认证状态
     * @var array
     */
    public static $_isRenZhengMap = array(
        array('id' => 1, 'name' => '未认证'),
        array('id' => 2, 'name' => '已认证')
    );

    /**
     * 是否开张
     * @var array
     */
    public static $_isOpenMap       = array(
        array('id' => 1, 'name' => '推荐'),
        array('id' => 2, 'name' => '不推荐')  
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '只能是数字','is_show' => true, 'is_order' => 'DESC', 
        ),'name' => array(
            'name' => '活动方名称', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'parent_id' => array(
            'name' => '经营分类', 'default' => '-1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'phone' => array(
            'name' => '联系方式', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '联系方式','is_show' => true, 
        ),'username' => array(
            'name' => '联系人', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度255字以内。',
        ),'userid' => array(
            'name' => '所属用户', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属商家','is_show' => true, 
        ),'address' => array(
            'name' => '商家地址', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '商家地址','is_show' => true, 
        ),'image_path' => array(
            'name' => '封面图片', 
            'verify' => array( 'len' => 255,),
            'comment' => '请选择允许的类型。','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'zhuying' => array(
            'name' => '公司简介', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '公司简介','is_show' => true, 
        ),'is_shiming' => array(
            'name' => '是否认证', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1为未认证，2为认证','is_show' => false, 
        ),'province_id' => array(
            'name' => '省份', 
            'verify' => array( 'numeric' => true,),
            'comment' => '所属省份','is_show' => true, 
        ),'city_id' => array(
            'name' => '城市', 
            'verify' => array( 'numeric' => true,),
            'comment' => '所属城市','is_show' => true, 
        ),'total_score' => array(
            'name' => '总评分数', 'default' => '0',
            'verify' => array(),
            'comment' => '总评分数','is_show' => true, 
        ),'total_visits' => array(
            'name' => '访问数', 'default' => '0',
            'verify' => array(),
            'comment' => '总访问数','is_show' => true, 
        ),'total_orders' => array(
            'name' => '总点评数', 'default' => '0',
            'verify' => array(),
            'comment' => '总点评数','is_show' => true, 
        ),'total_collects' => array(
            'name' => '总收藏数', 'default' => '0',
            'verify' => array(),
            'comment' => '总收藏数','is_show' => true, 
        ),'total_products' => array(
            'name' => '总活动数', 'default' => '0',
            'verify' => array(),
            'comment' => '总活动数','is_show' => true, 
        ),'status' => array(
            'name' => '企业状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1为正在审核，2申请失败，3申请成功，4下架','is_show' => true, 
        ),'discount_money' => array(
            'name' => '到店折扣', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '到店折扣，如满1000享受7折，则填写7','is_show' => false, 
        ),'min_discountmoney' => array(
            'name' => '最低折扣金额', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '如满1000享受7折，则填写1000','is_show' => false, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10','is_show' => false, 
        ),'author' => array(
            'name' => '作者', 'default' => '1',
            'verify' => array( 'numeric' => true,),
            'comment' => '用户的ID','is_show' => true, 
        ),'lat' => array(
            'name' => '经度', 'default' => null,
            'verify' => array(),
            'comment' => '地理经度','is_show' => false, 
        ),'longs' => array(
            'name' => '纬度', 'default' => null,
            'verify' => array(),
            'comment' => '地理纬度','is_show' => false, 
        ),'start_date' => array(
            'name' => '营业开始', 'default' => null,
            'verify' => array(),
            'comment' => '营业开始时间，如早上八点半格式为08:30','is_show' => false, 
        ),'end_date' => array(
            'name' => '营业结束', 'default' => null,
            'verify' => array(),
            'comment' => '营业打样时间,如晚上九点格式为21:00','is_show' => false, 
        ),'attrs' => array(
            'name' => '相关标签', 'default' => null,
            'verify' => array(),
            'comment' => '相关标签','is_show' => false, 
        ),'is_open' => array(
            'name' => '是否推荐', 'default' => 1,
            'verify' => array(),
            'comment' => '默认1为推荐，2为不推荐','is_show' => false, 
        ),
        );

}

?>
