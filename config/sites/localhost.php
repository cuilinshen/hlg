<?php 
/*请设置“http://localhost/”的配置信息*/

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
  'STATIC_URL' => 'http://localhost/',
  'CDN_URL' => 'http://localhost/vendor/',
  'SALT' => '259ee4d3e09944228675321dd8b32221',
  'TIME_ZONE' => 'Asia/Shanghai',
  'RES_DIR' => 'static/uploadfiles/sites/localhost/'
); ?>
