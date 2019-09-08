<?php 
/*请设置“http://localhost/hjz-huodong/”的配置信息*/

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
    'dbName' => 'ydt_huodong',
    'dbUserName' => 'root',
    'dbUserPassword' => '',
  ),
  'STATIC_URL' => 'http://localhost/hjz-huodong/',
  'CDN_URL' => 'http://localhost/hjz-huodong/vendor/',
  'SALT' => '4a3f82d37a554e22a2f8e94ee1c582ce',
  'TIME_ZONE' => 'Asia/Shanghai',
  'RES_DIR' => 'static/uploadfiles/sites/localhosthjz-huodong/'
); ?>
