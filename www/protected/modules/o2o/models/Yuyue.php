<?php
class Yuyue extends MongoAr {

    public $_id;
    public $name;//姓名
    public $type;//类型
    public $desc = '';//描述
    public $city = '';//城市
    public $address = '';//地址
    public $gender = 0;//性别   0=>女   1=>男
    public $status=0;//状态，同User表
    public $mobile = '';//手机号
    

    public static $status_option = [
        1  => ['name' => '已处理'],
        0  => ['name' => '待处理'],
        -1 => ['name' => '已删除'],
    ];

    public function __construct($scenario='insert') {
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public static function get($_id) {
        $criteria = new EMongoCriteria();
        $criteria->_id('==', new MongoId($_id));
        $tech = self::model()->find($criteria);
        return $tech;
    }

    public function getCollectionName() {
        return 'yuyue';
    }

    public function parseRow($row, $output=[]) {
        $newRow = [];

        $newRow['_id']              = strval($row['_id']);
        $newRow['name']             = CommonFn::get_val_if_isset($row, 'name', '');
        $newRow['desc']             = CommonFn::get_val_if_isset($row, 'desc', '');
        $newRow['city']             = CommonFn::get_val_if_isset($row, 'city', '');
        $newRow['type']             = CommonFn::get_val_if_isset($row, 'type', '');
        $newRow['address']             = CommonFn::get_val_if_isset($row, 'address', '');
        $newRow['status']           = CommonFn::get_val_if_isset($row, 'status', 0);
        $newRow['gender']           = CommonFn::get_val_if_isset($row, 'gender', 0);
        $newRow['status_str']       = self::$status_option[$newRow['status']]['name'];
        $newRow['mobile']           = CommonFn::get_val_if_isset($row, 'mobile', '');

        return $this->output($newRow, $output);
    }

}