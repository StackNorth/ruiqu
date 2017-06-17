<?php
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
include('yiguanjia_const.php');
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'壹橙管家',
    'defaultController'=>'site',
    // preloading 'log' component
    'preload'=>array('log'),
    //中文
    'language' => 'zh_cn',
    // autoloading model and component classes
    'import'=>array(
           'application.models.*',
           'application.models.data.*',
           'application.models.redis.*',
           'application.models.admin.*',
           'application.components.*',
           'ext.YiiMongoDbSuite.*',
           'ext.redis.*',
           'application.vendors.*',
           'application.behaviors.*',
           'application.services.*',
           'application.controllers.*',
           'application.message.*',
           'application.widget.*',
           'application.modules.o2o.models.*',
            'application.modules.tech.models.*',
           'application.extensions.phpexcel.*',


    ),

    'modules'=>array(
        // 'gii'=>array(
        //               'class'=>'system.gii.GiiModule',
        //               'password'=>'admin',
        //               'ipFilters'=>array('127.0.0.   1','::1'),
        //            ),
        'dataview',
        'common',
        'api',
        'o2o',
        'tech',
    ),

    'components'=>array(
        'user'=>array(
            'allowAutoLogin' => true,
            'class' => 'CWebUser'
        ),
        'request' => array(
            'class' => 'ZHttpRequest',
            'enableCsrfValidation'=>false,

        ),
        'cache' => array (
             'class' => 'system.caching.CFileCache',
             'directoryLevel' => 2,
        ),
        'redis' => array(
            'class' => 'ext.redis.ARedisConnection',
            'hostname' => '10.9.131.143',
            'port' => 6379,
            'database' => 0,
            'prefix' => 'Yii.redis.'
        ),
        'mongodb_data' => array(
            'class'            => 'EMongoDB',
            'connectionString' => 'mongodb://root:Yichengguanjia123@10.9.130.50:27017',
            'dbName'           => 'data',
            'fsyncFlag'        => true,
            'safeFlag'         => true,
            'useCursor'        => true
        ),
        'mongodb_o2o' => array(//o2o的数据库
            'class'            => 'EMongoDB',
            'connectionString' => 'mongodb://root:Yichengguanjia123@10.9.130.50:27017',
            'dbName'           => 'fuwu',
            'fsyncFlag'        => true,
            'safeFlag'         => true,
            'useCursor'        => true
        ),
        'mongodb_tech' => array(//o2o的数据库
            'class'            => 'EMongoDB',
            'connectionString' => 'mongodb://root:Yichengguanjia123@10.9.130.50:27017',
            'dbName'           => 'techInfo',
            'fsyncFlag'        => true,
            'safeFlag'         => true,
            'useCursor'        => true
        ),
        'mongodb_ruiqu' => array(//o2o的数据库
            'class'            => 'EMongoDB',
            'connectionString' => 'mongodb://192.168.7.8:27017',
            'dbName'           => 'test',
            'fsyncFlag'        => true,
            'safeFlag'         => true,
            'useCursor'        => true
        ),
        'mongodb' => array(//后台的数据库
            'class'            => 'EMongoDB',
            'connectionString' => 'mongodb://root:Yichengguanjia123@10.9.130.50:27017',
            'dbName'           => 'backend',
            'fsyncFlag'        => true,
            'safeFlag'         => true,
            'useCursor'        => true
        ),
        'authManager'=>array(
            'class'=>'CMongoDbAuthManager',
            //'mongoConnectionId'=>'mongodb', (default)
            //'authFile' => 'mongodb_authmanager' (default, is now the collection name)
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
              array(
                'class'=>'CFileLogRoute',
                'levels'=>'error',
                'filter'=>'CLogFilter',
              ),
            ),
        ),
        'messages'=>array(
           'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages'
        ),
        'coreMessage'=>array(
            'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages'
        )
    ),
    'params'=> array_merge(
        array(
            "pingxx_api_key" => 'sk_live_4qz9i5PGSm9CPaHan1jb1Sm1',
            'qiniuConfig' => array(
                'ak'=>'rjs8hPzTLArsZ7qkDRpEMripCvdDUumMaUWUqtLz',
                'sk'=>'DB79nB81Nd7rGUrxSI228KMLuWzRlb6xUzsnUpEP',
                'icons'=>'icons',
                'pics'=>'pics',
                'avatars'=>'avatars',//用户的头像
                'video'=>'video'//音频文件
            )
        ),
        $wz
    ),
);
