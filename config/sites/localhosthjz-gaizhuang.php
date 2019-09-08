<?php 
/*请设置“http://localhost/hjz-gaizhuang/”的配置信息*/

return array (
  'DEF_APP' => 'cms',
  'DATABASE' => 
  array (
    'tablePrefix' => 'hjz_',
    'dbHost' => 'localhost',
    'dbPort' => '3306',
    'dbType' => 'mysql',
    'dbDriver' => 'mysqli',
    'dbCharset' => 'utf8',
    'dbName' => 'ydt_gaizhuang',
    'dbUserName' => 'root',
    'dbUserPassword' => '123456',
  ),
  'STATIC_URL' => 'http://localhost/hjz-gaizhuang/',
  'CDN_URL' => 'http://localhost/hjz-gaizhuang/vendor/',
  'SALT' => '06e2ed6a39b04bd7bd31f8b8a7958800',
  'TIME_ZONE' => 'Asia/Shanghai',
  'RES_DIR' => 'static/uploadfiles/sites/localhosthjz-gaizhuang/'
); ?>
