<?php 
/*请设置“http://linksman.ru/”的配置信息*/

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
  'STATIC_URL' => 'http://linksman.ru/',
  'CDN_URL' => 'http://linksman.ru/vendor/',
  'SALT' => '5e410c23c10f40998775a7f4ae44660f',
  'TIME_ZONE' => 'Asia/Shanghai',
  'RES_DIR' => 'static/uploadfiles/sites/linksman-ru/'
); ?>
