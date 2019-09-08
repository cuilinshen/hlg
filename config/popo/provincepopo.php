<?php 

/**
 * @version			$Id$
 * @create 			2017-08-23 01:08:24 By xjiujiu 
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
class ProvincePopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '省份';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'province';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_province';

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
            'name' => '省份名称', 
            'verify' => array('null' => false, 'len' => 15,),
            'comment' => '长度15','is_show' => true, 
        ),'is_open' => array(
            'name' => '是否开启', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1未开启2开启','is_show' => true, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array(),
            'comment' => '时间信息','is_show' => true, 
        ),'author' => array(
            'name' => '维护人', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '编号','is_show' => true, 
        ),);

}

?>
