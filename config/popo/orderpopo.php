<?php 

/**
 * @version         $Id$
 * @create          2017-08-31 00:08:39 By xjiujiu 
 * @description     HongJuZi Framework
 * @copyRight       Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

/**
 * 模块工具的基本信息类 
 * 
 * 用于记录单模块的配置信息 
 * 
 * @author          xjiujiu <xjiujiu@foxmail.com>
 * @package         config.popo
 * @since           1.0.0
 */
class OrderPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '订单';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'order';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'user';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_order';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * 状态Map
     * @var array
     */
    public static $_statusMap   = array(
        array('id' => 1, 'name' => '待支付'),
        array('id' => 2, 'name' => '待审核'), 
        array('id' => 3, 'name' => '待参加'),
        array('id' => 4, 'name' => '待评价'),
        array('id' => 5, 'name' => '退款审核中'),
        array('id' => 6, 'name' => '退款成功'),
        array('id' => 7, 'name' => '退款失败'),
        array('id' => 8, 'name' => '已评价'),
        array('id' => 9, 'name' => '用户已取消')
    );

    /**
     * 支付状态Map
     * @var array
     */
    public static $_payStatusMap = array(
        array('id' => 0, 'name' => '未支付'),
        array('id' => 1, 'name' => '支付成功'), 
        array('id' => 2, 'name' => '支付失败'), 
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '系统编号','is_show' => true, 'is_order' => 'DESC', 
        ),'code' => array(
            'name' => '编号', 
            'verify' => array('null' => false, 'len' => 50,),
            'comment' => '查询编号','is_show' => true, 
        ),'name' => array(
            'name' => '收货人', 
            'verify' => array('null' => false, 'len' => 50,),
            'comment' => '登录系统使用的账号','is_show' => true, 
        ),'phone' => array(
            'name' => '收货电话', 
            'verify' => array( 'len  ' => 30,),
            'comment' => '常用电话号码，方便联系','is_show' => true, 
        ),'main_order_id' => array(
            'name' => '所属主订单', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属主订单','is_show' => false, 
        ),'parent_id' => array(
            'name' => '所属用户', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属用户','is_show' => true, 
        ),'address_id' => array(
            'name' => '所属上门地址', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属上门地址','is_show' => false, 
        ),'comment_id' => array(
            'name' => '所属评论', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属评论','is_show' => false, 
        ),'company_id' => array(
            'name' => '所属公司', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所在所属公司','is_show' => true, 
        ),'remark' => array(
            'name' => '备注', 
            'verify' => array(),
            'comment' => '备注','is_show' => false, 
        ),'status' => array(
            'name' => '状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1正在处理2完成3不通过','is_show' => true, 
        ),'total_money' => array(
            'name' => '总金额', 'default' => '0',
            'verify' => array('null' => false,),
            'comment' => '总金额','is_show' => true, 
        ),'youhuijuan_id' => array(
            'name' => '优惠券ID', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '优惠券ID','is_show' => false, 
        ),'pay_id' => array(
            'name' => '支付ID', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '支付ID','is_show' => false, 
        ),'pay_status' => array(
            'name' => '支付状态', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '支付状态','is_show' => true, 
        ),'pay_time' => array(
            'name' => '支付成功时间', 
            'verify' => array(),
            'comment' => '格式：2013-04-10 08:09:09',
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10 08:09:09',
        ),'author' => array(
            'name' => '维护员', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '上一次修改的管理员','is_show' => false, 
        ),);

}

?>
