<?php 

/**
 * @version			$Id$
 * @create 			2015-05-03 20:05:59 By luodao 
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.luodao.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

/**
 * 模块工具的基本信息类 
 * 
 * 用于记录单模块的配置信息 
 * 
 * @author 			luodao <luodao@foxmail.com>
 * @package 		config.popo
 * @since 			1.0.0
 */
class MessagePopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '咨询';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'message';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'user';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_message';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * 状态Map
     * @var array
     */
    public static $_statusMap   = array(
        array('id' => 1, 'name' => '待处理'),
        array('id' => 2, 'name' => '已处理')
    );

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '只能是数字','is_show' => true, 'is_order' => 'DESC', 
        ),'name' => array(
            'name' => '称呼', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '长度范围：2~255。','is_show' => true, 'is_search' => true, 
        ),'image_path' => array(
            'name' => '头像', 
            'verify' => array( 'len' => 255,),
            'comment' => '用户头像,支持jpg','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'title' => array(
            'name' => '提问问题', 
            'verify' => array( 'len' => 255,),
            'comment' => '标题','is_show' => true, 
        ),'parent_id' => array(
            'name' => '所属用户', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'product_id' => array(
            'name' => '所属活动', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '请正确选取','is_show' => true, 
        ),'status' => array(
            'name' => '状态', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '1正在未读,2已读','is_show' => true, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10','is_show' => true, 
        ),'author' => array(
            'name' => '维护人', 'default' => '-1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '最后一次修改人员','is_show' => true, 
        ),);

}

?>
