<?php 
/*请设置“http:///”的配置信息*/

return array (
  'DEF_APP' => 'cms',
  'DATABASE' => 
  array (
    'tablePrefix' => 'xxx_',
    'dbHost' => 'xxx.xxx.xxx.xxx',
    'dbPort' => '3306',
    'dbType' => 'mysql',
    'dbDriver' => 'mysqli',
    'dbCharset' => 'utf8',
    'dbName' => 'xxxx',
    'dbUserName' => 'xxxxx',
    'dbUserPassword' => 'xxxx',
  ),
  'STATIC_URL' => 'http:///',
  'CDN_URL' => 'http:///vendor/',
  'SALT' => '12f81a4b5c9944e6a214c1cdcd5aa4fb',
  'TIME_ZONE' => 'Asia/Shanghai',
  'RES_DIR' => 'static/uploadfiles/sites//'
); ?>
