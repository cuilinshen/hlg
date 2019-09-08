<?php 

/**
 * @version			$Id$
 * @create 			2018-07-04 23:07:28 By xjiujiu 
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
class AreaPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '城市';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'area';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_area';

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
        ),'parent_id' => array(
            'name' => '位置', 
            'verify' => array( 'numeric' => true,),
            'comment' => '显示位置','is_show' => true, 
        ),'shortname' => array(
            'name' => '简称', 
            'verify' => array( 'len' => 100,),
            'comment' => '长度范围：2~255。','is_show' => true, 
        ),'name' => array(
            'name' => '标题', 
            'verify' => array( 'len' => 100,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'merger_name' => array(
            'name' => '全城', 
            'verify' => array( 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 
        ),'level' => array(
            'name' => '层级', 
            'verify' => array( 'numeric' => true,),
            'comment' => '1为省2为市3为区县','is_show' => true, 
        ),'pinyin' => array(
            'name' => '拼音', 
            'verify' => array( 'len' => 100,),
            'comment' => '长度255字以内。','is_show' => true, 
        ),'code' => array(
            'name' => '长途区号', 
            'verify' => array( 'len' => 100,),
            'comment' => '长度255字以内。','is_show' => true, 
        ),'zip_code' => array(
            'name' => '邮编', 
            'verify' => array( 'len' => 100,),
            'comment' => '长度255字以内。','is_show' => true, 
        ),'first' => array(
            'name' => '首字母', 
            'verify' => array( 'len' => 50,),
            'comment' => '长度255字以内。','is_show' => true, 
        ),'lng' => array(
            'name' => '经度', 
            'verify' => array( 'len' => 100,),
            'comment' => '长度255字以内。','is_show' => true, 
        ),'lat' => array(
            'name' => '纬度', 
            'verify' => array( 'len' => 100,),
            'comment' => '长度255字以内。','is_show' => true, 
        ),);

}

?>
