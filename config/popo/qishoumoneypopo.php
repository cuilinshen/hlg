<?php 

/**
 * @version			$Id$
 * @create 			2017-10-16 14:10:38 By xjiujiu 
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
class QishoumoneyPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '骑手收入明细';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'qishoumoney';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_qishou_money';

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
            'name' => '所属骑手', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属骑手','is_show' => true, 
        ),'money' => array(
            'name' => '骑手收入', 'default' => '0.00',
            'verify' => array('null' => false,),
            'comment' => '骑手收入','is_show' => true, 
        ),'order_id' => array(
            'name' => '订单ID', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '订单ID','is_show' => true, 
        ),'order_code' => array(
            'name' => '订单编号', 
            'verify' => array( 'len' => 255,),
            'comment' => '订单编号','is_show' => true, 
        ),'company_id' => array(
            'name' => '所属店家', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '所属店家','is_show' => true, 
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
