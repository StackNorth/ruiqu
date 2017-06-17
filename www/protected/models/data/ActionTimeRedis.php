<?php
/**
 * ActionTimeRedis
 * 用法:
 *     //首先得到key,(可以为多个参数)
 *     $Key = HelperKey::generateActionTimeKey(ActionTimeRedis::TYPE_GET_GROUP);
 *     // get
 *     $indexSlideData = GroupTopicRedis::get($Key);
 *     // set
 *     $rs = GroupTopicRedis::set($Key, $data);
 * $Id$
 */
class ActionTimeRedis extends RedisAr{
    /**
     *  操作类型:groupinfo
     */
    const TYPE_GET_GROUP = 'group_info';

    const TYPE_GET_INDEX = 'get_index';
    const TYPE_GET_CITY_TOPIC = 'get_city_topics';
    const TYPE_GET_RECOMMEND = 'get_recommend';
    /**
     *  操作类型:topicinfo
     */
    const TYPE_GET_TOPIC = 'topic_info';

    const TYPE_GET_SCORELOG = 'score_log';
    /**
     *  操作类型:subjecttopic
     */
    const TYPE_GET_SUBJECT_TOPIC = 'subject_topic';
    /**
     * _prefix 
     * 前缀
     * 
     * @var mixed
     */
    public static $_prefix = 'atime_';

    /**
     * get 
     * 
     * @param  mixed $key 
     * @return void
     */
    public static function get($key) {
        try {
            return Yii::app()->redis->getClient()->hgetall($key);
        } catch (Exception $e) {
            throw new CException(Yii::t('model', $e->getMessage()));
        }
    }

    /**
     * set
     * 
     * @param  mixed $data 
     * @return void
     * @param $time 在redis里的有效时间
     */
    public static function set($key, $data, $time = 86400) {
        try {
            $return = Yii::app()->redis->getClient()->hmset($key, $data);
            if($time != null){
                return Yii::app()->redis->getClient()->expire($key, $time);
            }else{
                return $return;
            }
        } catch (Exception $e) {
            throw new CException(Yii::t('model', $e->getMessage()));
        }
    }

    /**
     * remove 
     * hDel每次只能删除单个Key,
     * 所以此处需要一个循环来逐个删除字段
     * 
     * @param  mixed $key 
     * @return void
     */
    public static function remove($key) {
        try {
            $keys = Yii::app()->redis->getClient()->hkeys($key);
            foreach ($keys as $k) {
                Yii::app()->redis->getClient()->hdel($key, $k);
            }
            return true;
        } catch (Exception $e) {
            throw new CException(Yii::t('model', $e->getMessage()));
        }
    }
}
