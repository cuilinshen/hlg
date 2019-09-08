<?php 

/**
 * @version			$Id$
 * @create 			2017-11-05 09:11:16 By xjiujiu 
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
class NoticePopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '消息';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'notice';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_notice';

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
            'comment' => '只能是数字','is_show' => true, 'is_order' => 'DESC', 
        ),'name' => array(
            'name' => '用户名', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'parent_id' => array(
            'name' => '接受人', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '消息接受者','is_show' => true, 
        ),'type' => array(
            'name' => '消息类型', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1、为系统2、订单提醒、3为物流信息','is_show' => true, 
        ),'description' => array(
            'name' => '消息内容', 
            'verify' => array(),
            'comment' => '长度255字以内。','is_show' => true, 
        ),'status' => array(
            'name' => '消息状态', 'default' => '1',
            'verify' => array( 'numeric' => true,),
            'comment' => '1为未读2为已读','is_show' => true, 
        ),'rel_id' => array(
            'name' => '关联ID', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '数据关联的ID','is_show' => true, 
        ),'rel_model' => array(
            'name' => '关联model', 
            'verify' => array( 'len' => 255,),
            'comment' => '关联model','is_show' => true, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10','is_show' => true, 
        ),'author' => array(
            'name' => '维护人', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '最后一次维护人员','is_show' => true, 
        ),);

}

?>
