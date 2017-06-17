<?php
define('APP_PATH', dirname(dirname(__FILE__)));
define('APPLICATION', 'console');
// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';
$env = file_get_contents(__DIR__."/config/env.txt");
if (trim($env) == 'develop'){
	$environment = 'develop';
    defined('YII_DEBUG') or define('YII_DEBUG',true);
}elseif(trim($env) == 'product'){
    defined('YII_DEBUG') or define('YII_DEBUG',false);
    $environment = 'product';
}else{
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    $environment = 'test';
}
require_once($yiic);
