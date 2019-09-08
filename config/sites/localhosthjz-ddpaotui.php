<?php 
/*请设置“http://localhost/hjz-ddpaotui/”的配置信息*/

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
    'dbName' => 'ydt_paotui',
    'dbUserName' => 'root',
    'dbUserPassword' => '123456',
  ),
  'STATIC_URL' => 'http://localhost/hjz-ddpaotui/',
  'CDN_URL' => 'http://localhost/hjz-ddpaotui/vendor/',
  'SALT' => '6e46a9f1ea8840c595a46ff3f0b877b1',
  'TIME_ZONE' => 'Asia/Shanghai',
  'RES_DIR' => 'static/uploadfiles/sites/localhosthjz-ddpaotui/'
); ?>
