<?php 
/*请设置“http://localhost/hjz-shangmeng/”的配置信息*/

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
    'dbName' => 'ydt_shangmeng',
    'dbUserName' => 'root',
    'dbUserPassword' => '123456',
  ),
  'STATIC_URL' => 'http://localhost/hjz-shangmeng/',
  'CDN_URL' => 'http://localhost/hjz-shangmeng/vendor/',
  'SALT' => 'e74badc1f1d943a98d7678f2a3be00a1',
  'TIME_ZONE' => 'Asia/Shanghai',
  'RES_DIR' => 'static/uploadfiles/sites/localhosthjz-shangmeng/'
); ?>
