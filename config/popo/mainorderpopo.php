<?php 

/**
 * @version			$Id$
 * @create 			2017-10-22 14:10:30 By xjiujiu 
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
class MainorderPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '主订单表';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'mainorder';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_main_order';

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
        ),'code' => array(
            'name' => '编号', 
            'verify' => array('null' => false, 'len' => 50,),
            'comment' => '查询编号','is_show' => true, 
        ),'name' => array(
            'name' => '用户名', 
            'verify' => array('null' => false, 'len' => 50,),
            'comment' => '登录系统使用的账号','is_show' => true, 
        ),'phone' => array(
            'name' => '电话号码', 
            'verify' => array( 'len' => 30,),
            'comment' => '常用电话号码，方便联系','is_show' => false, 
        ),'parent_id' => array(
            'name' => '所属用户', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '订单所属用户','is_show' => true, 
        ),'status' => array(
            'name' => '状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1正在处理2完成3不通过','is_show' => false, 
        ),'total_money' => array(
            'name' => '总金额', 'default' => '0.00',
            'verify' => array('null' => false,),
            'comment' => '总金额','is_show' => true, 
        ),'pay_id' => array(
            'name' => '支付ID', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '支付ID','is_show' => true, 
        ),'pay_status' => array(
            'name' => '支付状态', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '支付状态','is_show' => true, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10 08:09:09',
        ),'author' => array(
            'name' => '维护员', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '上一次修改的管理员','is_show' => true, 
        ),'qishou_money' => array(
            'name' => '快递费', 'default' => '0',
            'verify' => array(),
            'comment' => '快递费','is_show' => true, 
        ),'pay_time' => array(
            'name' => '支付成功时间', 
            'verify' => array( 'len' => 255,),
            'comment' => '格式：2013-04-10 08:09:09','is_show' => true, 
        ),);

}

?>
