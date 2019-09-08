<?php 

/**
 * @version			$Id$
 * @create 			2018-08-02 18:08:51 By xjiujiu 
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
class UserkeywordPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '用户搜索关键词';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'userkeyword';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'user';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_user_keyword';

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
            'name' => '搜索关键词', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '搜索关键词','is_show' => true, 
        ),'parent_id' => array(
            'name' => '所属用户', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '所属用户','is_show' => true, 
        ),'city' => array(
            'name' => '所属城市', 'default' => null,
            'verify' => array( 'len' => 255,),
            'comment' => '所属城市','is_show' => true, 
        ),'rel_id' => array(
            'name' => '搜索出来关联的活动', 'default' => null,
            'verify' => array( 'len' => 255,),
            'comment' => '关联的活动','is_show' => true, 
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
