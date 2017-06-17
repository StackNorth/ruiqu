<?php
/**
 * HelperKey   rediskey 相关方法
 * @author   >
 */
class HelperKey {
    /**
     * @return string
     */
    public static function generateActionTimeKey() {
        return ActionTimeRedis::$_prefix . join("_", func_get_args());
    }
    /**
     * @return string
     */
    public static function generateUserActionKey() {
        return ActionTimeRedis::$_prefix . join("_", func_get_args());
    }
    /**
     * @return string
     */
    public static function generateRedisKey() {
        return  join("_", func_get_args());
    }

}
