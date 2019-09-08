<?php 

/**
 * @version			$Id$
 * @create 			2017-08-31 00:08:33 By xjiujiu 
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
class ActivityPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '活动';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'activity';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_activity';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => '编号', 
            'verify' => array(),
            'comment' => '系统自动编号','is_show' => true, 
        ),'name' => array(
            'name' => '名称', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度255','is_show' => true, 
        ),'max' => array(
            'name' => '最大额度', 
            'verify' => array( 'numeric' => true,),
            'comment' => '最大额度','is_show' => true, 
        ),'min' => array(
            'name' => '最小额度', 
            'verify' => array( 'numeric' => true,),
            'comment' => '最小额度','is_show' => true, 
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
