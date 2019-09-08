<?php 

/**
 * @version			$Id$
 * @create 			2015-05-31 23:05:33 By xjiujiu 
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
class MusicPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '音乐库';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'music';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'category';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_music';

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
            'name' => '标题', 
            'verify' => array('null' => false,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'parent_id' => array(
            'name' => '所属分类', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'url' => array(
            'name' => '链接', 
            'verify' => array( 'len' => 255,),
            'comment' => '访问链接','is_show' => true, 
        ),'content' => array(
            'name' => '内容介绍', 
            'verify' => array('null' => false,),
            'comment' => '长度10000字以内。',
        ),'tags' => array(
            'name' => '标签', 
            'verify' => array( 'len' => 255,),
            'comment' => '信息包含的标签分类','is_show' => false, 
        ),'tags_name' => array(
            'name' => '标签名称缓存', 
            'verify' => array( 'len' => 255,),
            'comment' => '标签名称缓存','is_show' => true, 
        ),'image_path' => array(
            'name' => '图片', 
            'verify' => array( 'len' => 255,),
            'comment' => '请选择允许的类型。','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'total_visits' => array(
            'name' => '阅读数', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '总到访数','is_show' => true, 
        ),'total_comments' => array(
            'name' => '总评论数', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '只能是数字','is_show' => true, 
        ),'extend' => array(
            'name' => '扩展数据', 
            'verify' => array( 'len' => 255,),
            'comment' => '如艺人的相关信息','is_show' => false, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10','is_show' => true, 
        ),'author' => array(
            'name' => '维护人', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '最近一次维护人员','is_show' => true, 
        ),);

}

?>
