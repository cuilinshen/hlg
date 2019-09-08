<?php 

/**
 * @version			$Id$
 * @create 			2017-08-18 02:08:51 By xjiujiu 
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
class SmssendPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '短信发送';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'smssend';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_sms_send';

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
        ),'tpl_id' => array(
            'name' => '短信模板ID', 
            'verify' => array('null' => false, 'len' => 20,),
            'comment' => '短信模板ID','is_show' => true, 
        ),'phone' => array(
            'name' => '手机号码', 
            'verify' => array( 'len' => 255,),
            'comment' => '手机号码','is_show' => true, 
        ),'content' => array(
            'name' => '短信内容', 
            'verify' => array( 'len' => 255,),
            'comment' => '短信内容','is_show' => true, 
        ),'is_use' => array(
            'name' => '是否使用', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '是否已经被使用','is_show' => true, 
        ),'is_express' => array(
            'name' => '是否过期', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '半个小时过期','is_show' => true, 
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
