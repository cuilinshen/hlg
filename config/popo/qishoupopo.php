<?php 

/**
 * @version			$Id$
 * @create 			2017-08-30 23:08:21 By xjiujiu 
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
class QishouPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '骑手';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'qishou';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'user';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_qishou';

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
            'comment' => '系统编号','is_show' => true, 
        ),'name' => array(
            'name' => '用户名', 
            'verify' => array('null' => false, 'len' => 50,),
            'comment' => '登录系统使用的账号','is_show' => true, 
        ),'parent_id' => array(
            'name' => '所属用户', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属用户','is_show' => true, 
        ),'phone' => array(
            'name' => '电话号码', 
            'verify' => array( 'len' => 255,),
            'comment' => '常用电话号码，方便联系','is_show' => false, 
        ),'total_money' => array(
            'name' => '红包总金额', 'default' => '0.00',
            'verify' => array('null' => false,),
            'comment' => '红包总金额','is_show' => true, 
        ),'total_orders' => array(
            'name' => '总订单', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '总订单','is_show' => true, 
        ),'yes_orders' => array(
            'name' => '昨日订单', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '昨日订单','is_show' => true, 
        ),'area' => array(
            'name' => '管理区域', 
            'verify' => array( 'len' => 255,),
            'comment' => '管理区域','is_show' => true, 
        ),'total_score' => array(
            'name' => '评分', 
            'verify' => array( 'len' => 50,),
            'comment' => '评分','is_show' => true, 
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
