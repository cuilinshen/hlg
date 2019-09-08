<?php 

/**
 * @version			$Id$
 * @create 			2018-05-11 01:05:35 By xjiujiu 
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
class ShopsumPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '店铺今日运营数据';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'shopsum';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'company';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_shop_sum';

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
            'name' => '所属店铺', 'default' => '0',
            'verify' => array( 'numeric' => true,),
            'comment' => '所属骑手','is_show' => true, 
        ),'cur_visits' => array(
            'name' => '今日访问量', 
            'verify' => array('null' => false, 'len' => 255,),
            'comment' => '今日访问量','is_show' => true, 
        ),'cur_collects' => array(
            'name' => '总收藏量', 'default' => '0',
            'verify' => array( 'len' => 255,),
            'comment' => '总收藏量','is_show' => true, 
        ),'cur_comments' => array(
            'name' => '总好评量', 'default' => '0',
            'verify' => array( 'len' => 255,),
            'comment' => '总好评量','is_show' => true, 
        ),'cur_date' => array(
            'name' => '当天日期', 
            'verify' => array( 'len' => 255,),
            'comment' => '长度255字以内。','is_show' => true, 
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
