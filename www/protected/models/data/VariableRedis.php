<?php
/**
 * VariableRedis 常用变量
 * 用法:
 *     //首先得到key,(可以为多个参数)
 *     $Key = HelperKey::generateRedisKey($variable_name);
 *     // get
 *     $indexSlideData = VariableRedis::get($Key);
 *     // set
 *     $rs = VariableRedis::set($Key, $data);
 * $Id$
 */
class VariableRedis extends RedisAr{
    /**
     * _prefix 
     * 前缀
     * 
     * @var mixed
     */
    public static $_prefix = 'variable_';

    /**
     * get 
     * 
     * @param  mixed $key 
     * @return void
     */
    public static function get($key) {
        try {
            return Yii::app()->redis->getClient()->get($key);
        } catch (Exception $e) {
            throw new CException(Yii::t('model', $e->getMessage()));
        }
    }

    /**
     * set
     * 
     * @param  string $data 
     * @return void
     * @param $time 在redis里的有效时间
     */
    public static function set($key, $data, $time = 86400) {
        try {
            $return = Yii::app()->redis->getClient()->set($key, $data);
            if($time != null){
                return Yii::app()->redis->getClient()->expire($key, $time);
            }else{
                return $return;
            }
        } catch (Exception $e) {
            throw new CException(Yii::t('model', $e->getMessage()));
        }
    }

    public static function remove($key) {
        try {
            Yii::app()->redis->getClient()->del($key);
            return true;
        } catch (Exception $e) {
            throw new CException(Yii::t('model', $e->getMessage()));
        }
    }
}
