<?php
/**
 * Created by JetBrains PhpStorm.
 * User: charlie
 * Date: 13-11-29
 * Time: 下午4:47
 * 服务项目预订
 */
class Booking extends MongoAr
{
    public $_id;

    public $status = 0;					//状态

    public $master;//对应的Master
    public $service_item;//对应的Serive_Item

    public $title;//标题

    public $avatar = '';        		//头像七牛的地址
    public $city_info = array();
    public $desc='';//简介
    public $pics=array();//相册

    public $prize;

    public static $status_option = array(
        1 => array('name' => '正常', 'color' => 'green'),
        0 => array('name' => '暂停服务', 'color' => 'blue'),
        -1 => array('name' => '删除', 'color' => 'red')
    );

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'booking';
    }
}