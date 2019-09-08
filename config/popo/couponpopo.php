<?php 

/**
 * @version			$Id$
 * @create 			2018-03-26 21:03:39 By xjiujiu 
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
class CouponPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '优惠券';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'coupon';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_coupon';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * 状态Map
     * @var array
     */
    public static $_statusMap   = array(
        '1' => array('id' => 1, 'name' => '正常'),
        '2' => array('id' => 2, 'name' => '过期'),
        '3' => array('id' => 3, 'name' => '失效')
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => '编号', 
            'verify' => array(),
            'comment' => '系统自动编号','is_show' => true, 
        ),'name' => array(
            'name' => '优惠券名称', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度255','is_show' => true, 
        ),'max' => array(
            'name' => '满的金额', 
            'verify' => array( 'numeric' => true,),
            'comment' => '满的金额','is_show' => true, 
        ),'song' => array(
            'name' => '减的金额', 
            'verify' => array( 'numeric' => true,),
            'comment' => '减的金额','is_show' => true, 
        ),'total_num' => array(
            'name' => '发放总数量', 
            'verify' => array( 'numeric' => true,),
            'comment' => '发放总数量','is_show' => true, 
        ),'level_num' => array(
            'name' => '剩余总数量', 
            'verify' => array( 'numeric' => true,),
            'comment' => '剩余总数量','is_show' => true, 
        ),'start_time' => array(
            'name' => '开始时间', 
            'verify' => array(),
            'comment' => '开始时间','is_show' => true, 
        ),'end_time' => array(
            'name' => '到期时间', 
            'verify' => array(),
            'comment' => '到期时间','is_show' => true, 
        ),'product_id' => array(
            'name' => '所属产品', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属产品','is_show' => true, 
        ),'company_id' => array(
            'name' => '所属公司', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属公司','is_show' => true, 
        ),'description' => array(
            'name' => '描述', 
            'verify' => array( 'len' => 255,),
            'comment' => '角色介绍信息',
        ),'status' => array(
            'name' => '状态', 
            'verify' => array( 'len' => 255,),
            'comment' => '优惠券状态','is_show' => true, 
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
