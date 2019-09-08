<?php 
/*请设置“http://localhost/hjz-diaocha/”的配置信息*/

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
    'dbName' => 'ydt_diaocha',
    'dbUserName' => 'root',
    'dbUserPassword' => '123456',
  ),
  'STATIC_URL' => 'http://localhost/hjz-diaocha/',
  'CDN_URL' => 'http://localhost/hjz-diaocha/vendor/',
  'SALT' => '4c115640a1bc44ca993917cfbd1bfcda',
  'TIME_ZONE' => 'Asia/Shanghai',
  'RES_DIR' => 'static/uploadfiles/sites/localhosthjz-diaocha/'
); ?>
