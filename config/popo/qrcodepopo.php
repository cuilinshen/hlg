<?php 

/**
 * @version			$Id$
 * @create 			2018-05-11 14:05:20 By xjiujiu 
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
class QrcodePopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '二维码';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'qrcode';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = '';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_qrcode';

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
            'comment' => '系统编号','is_show' => true, 'is_order' => 'DESC', 
        ),'open_id' => array(
            'name' => '所属人', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所属人','is_show' => true, 
        ),'model' => array(
            'name' => '所在模型', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '所在模型','is_show' => true, 
        ),'image_path' => array(
            'name' => '二维码图片', 
            'verify' => array( 'len' => 255,),
            'comment' => '请选择允许的类型。','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '评论提交时间','is_show' => true, 
        ),);

}

?>
