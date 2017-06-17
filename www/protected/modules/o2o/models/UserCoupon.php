<?php
/**
 * User: charlie
 * 用户的代金券
 */
class UserCoupon extends MongoAr
{
    public $_id;

    public $start_time;//代金券的有效开始时间
    public $end_time;//代金券的有效结束时间

    public $coupon;//Coupon 的  mongoid
    public $user;  //对应的RUser 的mongoid
    public $user_device_id;  //对应的RUser 的user_device_id
    public $use_time;

    public $status=0;// 0=>暂停使用  1=>待使用 -1=>已使用 -2=>已过期

    public static $status_option = array(
        0 => array('name' => '暂停使用'),
        1 => array('name' => '待使用'),
        -1 => array('name' => '已使用'),
        -2 => array('name' => '已过期'),
    );

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
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
    

    public function getCollectionName()
    {
        return 'user_coupons';
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];

        $newRow['start_time'] = CommonFn::get_val_if_isset($row,'start_time',0);
        $newRow['end_time'] = CommonFn::get_val_if_isset($row,'end_time',0);

        $newRow['start_time_str'] =  date('Y.n.d',$newRow['start_time']);
        $newRow['end_time_str'] = date('Y.n.d',$newRow['end_time']);

        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);

        $user = array();
        $t_user = new ZUser();
        if(isset($row['user'])){
            $_user = $t_user->get($row['user']);
            if($_user){
                $user = RUser::model()->parseRow($_user->attributes,array('user_name','id','avatar'));
            }
        }
        $newRow['user'] = $user;

        $coupon = array();
        if(isset($row['coupon'])){
            $criteria = new EMongoCriteria();
            $criteria->_id('==', $row['coupon']);
            $_coupon = Coupon::model()->find($criteria);
            $coupon = Coupon::model()->parseRow($_coupon->attributes,array('name','id','value','type','alias_name','time_limit_str','type_str','workday_limit','time_limit_start','time_limit_end','workday_limit_str','min_price','status'));
        }
        $newRow['coupon'] = $coupon;

        return $this->output($newRow,$output);
    }

}