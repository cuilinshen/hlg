<?php 

/**
 * @version			$Id$
 * @create 			2017-08-18 02:08:24 By xjiujiu 
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
class SmstotalPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '短信发送量';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'smstotal';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_sms_total';

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
        ),'vcode' => array(
            'name' => '验证码', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'open_id' => array(
            'name' => 'open_id', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => 'open_id的值','is_show' => true, 
        ),'date' => array(
            'name' => '短信模板ID', 
            'verify' => array('null' => false, 'len' => 20,),
            'comment' => '短信模板ID','is_show' => true, 
        ),'total_num' => array(
            'name' => '总发送量', 
            'verify' => array( 'numeric' => true,),
            'comment' => '总发送量','is_show' => true, 
        ),'sms_send_id' => array(
            'name' => '是否使用', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '是否已经被使用','is_show' => true, 
        ),'type' => array(
            'name' => '发送类型', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '对应的发送类型','is_show' => true, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10','is_show' => true, 
        ),);

}

?>
