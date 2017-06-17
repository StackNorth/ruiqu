<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'components'=>array(
            'redis' => array(
                'class' => 'ext.redis.ARedisConnection',
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' => 0,
                'prefix' => 'Yii.redis.'
            ),
            'mongodb_data' => array(
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://127.0.0.1:27017',
                'dbName'           => 'data',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'mongodb_ruiqu' => array(//o2o的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://127.0.0.1:27017',
                'dbName'           => 'test',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            'mongodb_o2o' => array(//o2o的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://127.0.0.1:27017',
                'dbName'           => 'fuwu',
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
            'mongodb_test' => array(//o2o的数据库
                'class'            => 'EMongoDB',
                'connectionString' => 'mongodb://127.0.0.1:27017',
                'dbName'           => 'test',
                'fsyncFlag'        => true,
                'safeFlag'         => true,
                'useCursor'        => true
            ),
            // 'log'=>array(
            //     'class'=>'CLogRouter',
            //     'routes'=>array(
            //         array(
            //             'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
            //         ),
            //     ),
            // ),
        ),
        'params'=> array_merge(
            array(
                    'qiniuConfig' => array(
                    'ak'=>'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr',
                    'sk'=>'mLtD4GhBjQt_llcgx4rKlhAts9j8iJ0Qa5VmNyi2',
                    'icons'=>'test',
                    'pics'=>'test',
                    'avatars'=>'test',//用户的头像
                    'video'=>'test'//音频文件
                ),
            )
        ),
    )
);
