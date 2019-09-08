<?php 

/**
 * @version			$Id$
 * @create 			2015-03-01 10:03:17 By xjiujiu 
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
class UserPopo extends HPopo
{

    /**
     * @var string $modelZhName 模块中文名称 
     */
    public $modelZhName         = '用户';

    /**
     * @var string $modelEnName 模块英文名称 
     */
    public $modelEnName         = 'user';

    /**
     * @var string $_parentTable 父表名 
     */
    protected $_parent          = 'actor';

    /**
     * @var string $_table 模块表名 
     */
    protected $_table           = '#_user';

    /**
     * @var string $primaryKey 表主键
     */
    public $primaryKey          = 'id';

    /**
     * Sex Map
     * @var array
     */
    public static $_sexMap      = array(
        '1' => array('id' => '1', 'name' => '男'),
        '2' => array('id' => '2', 'name' => '女'),
        '3' => array('id' => '3', 'name' => '其他')
    );

    /**
     * Sex Map
     * @var array
     */
    public static $_isRenZhengMap      = array(
        '1' => array('id' => '1', 'name' => '未申请'),
        '2' => array('id' => '2', 'name' => '等待审核'),
        '3' => array('id' => '3', 'name' => '认证失败'),
        '4' => array('id' => '4', 'name' => '认证成功')
    );    

    /**
     * @var array $_fields 模块字段配置 
     */
    protected $_fields          = array('id' => array(
            'name' => 'ID', 
            'verify' => array(),
            'comment' => '系统编号','is_show' => true, 
        ),'name' => array(
            'name' => '用户名', 
            'verify' => array('null' => false, 'len' => 50,),
            'comment' => '登录系统使用的账号','is_show' => true, 
        ),'password' => array(
            'name' => '密码', 
            'verify' => array( 'len' => 32,),
            'comment' => '登录系统密码',
        ),'sex' => array(
            'name' => '性别', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '用户性别：1，男；2，女；3. 其它。','is_show' => true, 
        ),'parent_id' => array(
            'name' => '所属角色', 'default' => '1',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '用户所具有的权限','is_show' => true, 
        ),'open_id' => array(
            'name' => 'openid', 
            'verify' => array( 'len' => 255,),
            'comment' => '所属openid',
        ),'image_path' => array(
            'name' => '头像', 
            'verify' => array( 'len' => 255,),
            'comment' => '用户头像,支持jpg','is_show' => true, 'is_file' => true, 'zoom' => array('small' => array(100, 120)), 'type' => array('.png', '.jpg', '.gif'), 'size' => 0.5
        ),'phone' => array(
            'name' => '电话号码', 
            'verify' => array( 'len' => 255,),
            'comment' => '常用电话号码，方便联系','is_show' => true, 
        ),'email' => array(
            'name' => '邮箱', 'default' => null,
            'verify' => array( 'len' => 255,),
            'comment' => '邮箱','is_show' => false, 
        ),'birthday' => array(
            'name' => '生日', 
            'verify' => array( 'len' => 255,),
            'comment' => '生日','is_show' => false, 
        ),'total_money' => array(
            'name' => '红包总金额', 
            'verify' => array( 'len' => 255,),
            'comment' => '红包总金额','is_show' => false, 
        ),'total_jifen' => array(
            'name' => '积分总金额', 
            'verify' => array( 'len' => 255,),
            'comment' => '积分总金额','is_show' => false, 
        ),'total_redpack' => array(
            'name' => '红包总数', 'default' => 0,
            'verify' => array( 'len' => 255,),
            'comment' => '红包总数','is_show' => false, 
        ),'is_renzheng' => array(
            'name' => '是否认证', 'default' => 1,
            'verify' => array( 'len' => 255,),
            'comment' => '1为未认证通过，2为认证通过','is_show' => false, 
        ),'token' => array(
            'name' => '认证token', 
            'verify' => array( 'len' => 255,),
            'comment' => 'token值','is_show' => false, 
        ),'hash' => array(
            'name' => '哈希', 
            'verify' => array( 'len' => 60,),
            'comment' => '记录用户的登陆状态',
        ),'login_time' => array(
            'name' => '编辑时间', 
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '最近一次修改时间',
        ),'ip' => array(
            'name' => '登陆IP', 'default' => '127.0.0.1',
            'verify' => array('null' => false, 'len' => 32,),
            'comment' => '上一次登陆IP','is_show' => false, 
        ),'city' => array(
            'name' => '城市', 'default' => null,
            'verify' => array(),
            'comment' => '常用城市','is_show' => true, 
        ),'province' => array(
            'name' => '省份', 'default' => null,
            'verify' => array(),
            'comment' => '省份','is_show' => true, 
        ),'create_time' => array(
            'name' => '创建时间', 
            'verify' => array('null' => false,),
            'comment' => '格式：2013-04-10 08:09:09',
        ),'author' => array(
            'name' => '维护员', 'default' => '0',
            'verify' => array('null' => false, 'numeric' => true,),
            'comment' => '上一次修改的管理员','is_show' => true, 
        ),);

}

?>
