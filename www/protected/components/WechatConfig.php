<?php 
/**
 * 微信各项设置
 * @author     2015-11-30
 */
class WechatConfig {

    private static $_instance;

    private function __construct() {}

    public static function getIns() {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        } else {
            self::$_instance = new self;
            return self::$_instance;
        }
    }

    /* QyWechat初始化参数
    array(
        'token'          => 'token',            // 自定义Token
        'encodingaeskey' => 'encodingaeskey',   // 自定义EncodingAESKey
        'appid'          => 'appid',            // 微信提供的CropID

    );
     */
    //wx08ddb347281baf39 此处为壹橙管家 wx2b458d9de41d0622
    private static $link_option = array(
        // 'token' => 'm7NWCmUCejq89IFumv8cFr8SK',
        // 'encodingaeskey' => '6ENIkUF7aFnieirYATM5jN1lEVGZeNP9OapdHWuC3UQ',
        'token' => '',
        'encodingaeskey' => '',
        // 'appid' => 'wx163c14f99d0b75bb',
        'appid' => 'wx2b458d9de41d0622',
    );


    /* 管理组对应的Secret
    array(
        管理组名称 => 对应的Secret
    );
     */
    //K_UHVyO8f9Cu8cX1llltDFjlCiUZ89swAinsCaH3TtFKlyLLsPlvzUD8H0Lqx4vh 替换 qFKNg7Hh4x56aQJBYSOVbfQzLh6Vnq0WCWXC6cIjDfGEWiRVNkSzs3P3O16kapVj 此处为壹橙管家
    private static $secret_options = array(
        'admin_test' => '2v3iS_1ep38zGMOZBNAUM55lmdrsZ-yhoWEIkQwv9aNtREC96SQPXx-OJa_kEDkg',
        'admin_dev'  => 'qFKNg7Hh4x56aQJBYSOVbfQzLh6Vnq0WCWXC6cIjDfGEWiRVNkSzs3P3O16kapVj',
    );

    public static function getLinkOption() {
        return self::$link_option;
    }

    public static function getSecret($admin) {
        return self::$secret_options[$admin];
    }
}