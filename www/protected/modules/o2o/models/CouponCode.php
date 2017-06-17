<?php
class CouponCode extends MongoAr
{
    public $_id;    //object id

    public $code;   //兑换码 string

    public $channel; //使用渠道  唯一批次标记

    public $desc; //批次说明

    public $coupons = array();

    public $stop_time;//过期时间

    public $use_time;//使用时间

    public $user;//使用者

    public $user_device_id;//领用用户的device_id or unionid

    public $alway = 0; //是否可以重复使用

    public $status = 0; //状态 0为未使用，1为已使用

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
        $time = time();
        $this->code = substr($time,2);
    }

    public static $status_option = array(
        0 => array('name' => '未使用'),
        1 => array('name' => '已使用'),
    );

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'coupon_code';
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];

        $newRow['code'] = CommonFn::get_val_if_isset($row,'code','');
        $newRow['channel'] = CommonFn::get_val_if_isset($row,'channel','');
        $newRow['desc'] = CommonFn::get_val_if_isset($row,'desc','');

        $newRow['user_device_id'] = CommonFn::get_val_if_isset($row,'user_device_id','');

        $user = array();
        if(isset($row['user'])){
            $_user = RUser::get($row['user']);
            if($_user){
                $user = $_user->parseRow($_user->attributes,array('user_name','id','avatar'));
            }

        }
        $newRow['user'] = $user;

        $newRow['stop_time'] = CommonFn::get_val_if_isset($row,'stop_time');
        if($newRow['stop_time']){
            $newRow['stop_time_str'] = CommonFn::bgmdate("Y年n月d日", $newRow['stop_time'],1);
        }


        $newRow['use_time'] = CommonFn::get_val_if_isset($row,'use_time');
        if($newRow['use_time']) {
            $newRow['use_time_str'] = CommonFn::bgmdate("Y年n月d日", $newRow['use_time'], 1);
        }

        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',0);
        $newRow['always'] = CommonFn::get_val_if_isset($row,'always',0);

        $coupons = array();
        if(isset($row['coupons']) && !empty($row['coupons'])){
            foreach($row['coupons'] as $coupon_id){
                $_coupon = Coupon::get($coupon_id);
                if($_coupon){
                    $coupons[] = Coupon::model()->parse($_coupon->attributes,false,array('id','name'));
                }else{
                    continue;
                }
            }
        }
        $newRow['coupons'] = $coupons;

        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");

        if(APPLICATION=='api'){
            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
        }

        return $this->output($newRow,$output);
    }

    public static function get($_id) {
        if(CommonFn::isMongoId($_id)){
            $criteria = new EMongoCriteria();
            $criteria->_id('==', $_id);
            $model = self::model()->find($criteria);
            return $model;
        }else{
            return false;
        }
    }

}