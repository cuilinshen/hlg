<?php 

/**
 * @version			$Id$
 * @create 			2018-05-10 12:05:35 By xjiujiu 
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
class ZizhiPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '资质';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'zizhi';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_zizhi';

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
        ),'user_name' => array(
            'name' => '法人姓名', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'parent_id' => array(
            'name' => '所属店铺', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属店铺','is_show' => true, 
        ),'user_icard' => array(
            'name' => '法人身份证', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '法人身份证','is_show' => true, 
        ),'face_image' => array(
            'name' => '法人身份证正面照', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '法人身份证正面照','is_show' => true, 
        ),'licence_name' => array(
            'name' => '执照名称', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度255字以内。','is_show' => true, 
        ),'image_path' => array(
            'name' => '执照图片', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '请选择允许的类型。','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'licence_number' => array(
            'name' => '执照注册号', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '数据所在的网站范围','is_show' => true, 
        ),'licence_address' => array(
            'name' => '执照所在地', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '数据所在的网站范围','is_show' => true, 
        ),'licence_type' => array(
            'name' => '执照类型', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '数据所在的网站范围','is_show' => true, 
        ),'licence_time' => array(
            'name' => '执照有效期', 'default' => '20',
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '数据所在的网站范围','is_show' => true, 
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
