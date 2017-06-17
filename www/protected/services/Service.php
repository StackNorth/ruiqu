<?php
/**
 * Service
 * 
 */
class Service {
    /**
     * 实例容器 
     */
    private static $_instances = array();
    /**
     * &factory 
     * 工厂方法
     * 
     * @param  mixed $class 
     * @return void
     */
    public static function &factory($class) {
        if (!array_key_exists($class, self::$_instances)) {
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }
}
