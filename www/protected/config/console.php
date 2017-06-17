<?php
include('yiguanjia_const.php');
$env = @file_get_contents(__DIR__.DIRECTORY_SEPARATOR."env.txt");
if(trim($env) == 'develop'){// 本地配置
    define('DB_CONNETC', 'mongodb://127.0.0.1:27017');
    define('ENVIRONMENT', 'test');
    return array(
        'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'壹橙管家',
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
        ),
        // application components
        'components'=>array(
            'request' => array(
                'class' => 'ZHttpRequest'
            ),
            'redis' => array(
                'class' => 'ext.redis.ARedisConnection',
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' => 0,
                'prefix' => 'Yii.redis.'
            ),
            'mongodb_data' => array(//管理后台的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://127.0.0.1:27017',
                'dbName'           => 'data',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'mongodb_o2o' => array(//o2o的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://192.168.7.8:27017',
                'dbName'           => 'test',
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
            'mongodb' => array(//管理后台的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://127.0.0.1:27017',
                'dbName'           => 'backend',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'mongodb_tech' => array(//o2o的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://127.0.0.1:27017',
                'dbName'           => 'techInfo',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'mongodb_analysis' => array(//统计分析的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://127.0.0.1:27017',
                'dbName'           => 'analysis',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),

            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                    ),
                    array(
                        'class'=>'CWebLogRoute',
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
            $wz,
            array(
                'app'=>'console',
                'qiniuConfig' => array(
                    'ak'=>'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr',
                    'sk'=>'mLtD4GhBjQt_llcgx4rKlhAts9j8iJ0Qa5VmNyi2',
                    'icons'=>'icons',
                    'pics'=>'pics',//
                    'avatars'=>'avatars',//用户的头像
                    'others'=>'others',//其他类型的图片
                    'voice'=>'voice',//音频文件
                    'video'=>'video',//视频文件
                ),
            )
        )
    );
}elseif(trim($env) == 'product'){
    define('DB_CONNETC', 'mongodb://root:Yichengguanjia123@10.9.130.50:27017');
    define('ENVIRONMENT', 'product');
    return array(
        'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'壹橙管家̨',
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
        ),

        // application components
        'components'=>array(
            'request' => array(
             'class' => 'ZHttpRequest'
            ),

            'redis' => array(
                'class' => 'ext.redis.ARedisConnection',
                'hostname' => '10.9.131.143',
                'port' => 6379,
                'database' => 0,
                'prefix' => 'Yii.redis.'
            ),
            'mongodb_data' => array(//管理后台的数据库
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
            'mongodb' => array(//管理后台的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://root:Yichengguanjia123@10.9.130.50:27017',
                'dbName'           => 'backend',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'mongodb_analysis' => array(//统计分析的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://root:Yichengguanjia123@10.9.130.50:27017',
                'dbName'           => 'analysis',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                    ),
                    array(
                        'class'=>'CWebLogRoute',
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
            $wz,
            array(
                'app'=>'console',
                'qiniuConfig' => array(
                    'ak'=>'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr',
                    'sk'=>'mLtD4GhBjQt_llcgx4rKlhAts9j8iJ0Qa5VmNyi2',
                    'icons'=>'icons',
                    'pics'=>'pics',
                    'avatars'=>'avatars',//用户的头像
                    'others'=>'others',//其他类型的图片
                    'voice'=>'voice',//音频文件
                    'video'=>'video'//音频文件
                )
            )
        )
    );
}else{//测试配置
    define('DB_CONNETC', 'mongodb://root:Yichengguanjia123@10.9.130.50:27017');
    define('ENVIRONMENT', 'test');
    return array(
        'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'壹橙管家̨',
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
        ),
        // application components
        'components'=>array(
            'request' => array(
                'class' => 'ZHttpRequest'
            ),
           'redis' => array(
                'class' => 'ext.redis.ARedisConnection',
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' => 0,
                'prefix' => 'Yii.redis.'
            ),
            'mongodb_data' => array(//管理后台的数据库
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
            'mongodb' => array(//管理后台的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://root:Yichengguanjia123@10.9.130.50:27017',
                'dbName'           => 'backend',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'mongodb_analysis' => array(//统计分析的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://root:Yichengguanjia123@10.9.130.50:27017',
                'dbName'           => 'analysis',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                    ),
                    array(
                        'class'=>'CWebLogRoute',
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
            $wz,
            array(
                'app'=>'console',
                'qiniuConfig' => array(
                    'ak'=>'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr',
                    'sk'=>'mLtD4GhBjQt_llcgx4rKlhAts9j8iJ0Qa5VmNyi2',
                    'icons'=>'icons',//
                    'pics'=>'pics',//
                    'avatars'=>'avatars',//用户的头像
                    'video'=>'video'//视频文件
                )
            )
        )
    );
}
