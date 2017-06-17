<?php
date_default_timezone_set('PRC');
$yii = __DIR__.'/../framework/yii.php';
$config = __DIR__.'/protected/config/main.php';
define('APP_PATH', dirname(__FILE__));
$env = file_get_contents(__DIR__."/protected/config/env.txt");
// var_dump(__DIR__."/protected/config/env.txt");exit;
$server_name = $_SERVER['SERVER_NAME'];
$application = '';
$app_name =explode(".",$server_name)[0];
if(preg_match('/admin/',$app_name)){
    $application = 'admin';
}

if (trim($env) == 'develop'){
    //ini_set('mongo.long_as_object',1);
    define('DB_CONNETC', 'mongodb://127.0.0.1:27017');
    $config=dirname(__FILE__).'/protected/config/develop.php';
    $environment = 'develop';
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

}elseif(trim($env) == 'product'){
    defined('YII_DEBUG') or define('YII_DEBUG',false);
    define('DB_CONNETC', 'mongodb://root:Yichengguanjia123@10.9.130.50:27017');
    $environment = 'product';
}else{
    define('DB_CONNETC', 'mongodb://root:Yiguanjia6101@10.9.198.18:27017');
    $config=dirname(__FILE__).'/protected/config/test.php';
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    $environment = 'test';
}

$is_spider = false;
$spider_user_agent="/Googlebot|msnbot|spider|archiver|slurp|YoudaoBot|Nutch|Crawler|bingbot/i";
if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match($spider_user_agent, $_SERVER['HTTP_USER_AGENT'])){
    $is_spider = true;
}
define('ISSPIDER', $is_spider);
define('ENVIRONMENT', $environment);
define('APPLICATION', $application);
define('SERVERNAME', $server_name);
define('DEBUG',true);
require_once($yii);

ob_start();

Yii::createWebApplication($config)->run();

die;
