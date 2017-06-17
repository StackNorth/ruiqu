<?php
class Tech extends MongoAr {

    public $_id;
    public $name;//姓名
    public $desc = '';//描述
    public $city = '';//城市
    //public $avatar;//头像
    public $status;//状态，同User表
    public $mobile = '';//手机号
    public $service_type = [];//服务类型
    //public $position ='';//职位
    public $contact_name = '';
    public $contact_phone = '';
    public $img_upper_body;//上半身正面照片
    public $img_handheld_card;//手持身份证
    public $img_card_front;//身份证正面照片
    public $img_card_back;//身份证反面照片
    public $invitor;//邀请者
    public $id_num;//身份证号
    public static $status_option = [
        1  => ['name' => '通过'],
        0  => ['name' => '待审核'],
        -1 => ['name' => '未通过'],
    ];

    public function __construct($scenario='insert') {
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_tech'));
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


    public static function getByMobile($mobile) {
        $criteria = new EMongoCriteria();
        $criteria->mobile = $mobile;
        $tech = self::model()->find($criteria);
        return $tech;
    }

    public function getCollectionName() {
        return 'tech_info';
    }

    public function parseRow($row, $output=[]) {
        $newRow = [];

        $newRow['_id']              = strval($row['_id']);
        $newRow['name']             = CommonFn::get_val_if_isset($row, 'name', '');
        $newRow['desc']             = CommonFn::get_val_if_isset($row, 'desc', '');
        $newRow['city']             = CommonFn::get_val_if_isset($row, 'city', '');
        $newRow['invitor']             = CommonFn::get_val_if_isset($row, 'invitor', '');
        $newRow['id_num']             = CommonFn::get_val_if_isset($row, 'id_num', '');
        //$newRow['avatar']           = CommonFn::get_val_if_isset($row, 'avatar', '');

        $newRow['status']           = CommonFn::get_val_if_isset($row, 'status', 0);
        $newRow['status_str']       = self::$status_option[$newRow['status']]['name'];

        $newRow['mobile']           = CommonFn::get_val_if_isset($row, 'mobile', '');
        $newRow['service_type']     = CommonFn::get_val_if_isset($row, 'service_type', []);

        $newRow['contact_name']       = CommonFn::get_val_if_isset($row, 'contact_name', []);
        $newRow['contact_phone']       = CommonFn::get_val_if_isset($row, 'contact_phone', []);
        //$newRow['position']       = CommonFn::get_val_if_isset($row, 'position','');
        $newRow['img_upper_body']    = CommonFn::get_val_if_isset($row, 'img_upper_body', '');
        $newRow['img_handheld_card']      = CommonFn::get_val_if_isset($row, 'img_handheld_card','');
        $newRow['img_card_front']    = CommonFn::get_val_if_isset($row, 'img_card_front', '');
        $newRow['img_card_back']      = CommonFn::get_val_if_isset($row, 'img_card_back','');


        return $this->output($newRow, $output);
    }

}