<?php
/**
 * User: charlie
 * 用户模型
 */
class RUser extends MongoAr
{
    public $_id;
    public $user_name;
    public $sex = 3;           			//性别  1男  2女  3不告诉你
    public $avatar = '';        		//头像七牛的地址
    public $channel = '';          			//注册渠道
    public $register_time;    			//注册时间
    public $openid;            //微信和微博授权登陆的用户才有
    public $wx_pub_openid; //微信公众号openid
    public $city_info = array();          ////用户所属的城市信息  不限定城市的圈子,该字段就为空   "province"=>"上海","city"=>"上海","area"=>"浦东"    或者   "province"=>"江苏","city"=>"苏州","area"="昆山"
    public $position=array();   //用户的坐标
    public $unionid;         //微信授权登陆用户才有   用户统一标识。针对一个微信开放平台帐号下的应用，同一用户的unionid是唯一的。
    public $address = array(); //用户地址列表
    public $wx_have_follow = 0; //用户是否关注微信公众号
    public $order_count = 0; // 用户有效订单总数
    public $shop_address = array(); //用户收货地址列表
    public $balance = 0;//账户余额


    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_data'));
        parent::__construct($scenario);
        $this->onBeforeValidate = function($event){
            $model = $event->sender;
        };
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public function getCollectionName()
    {
        return 'users';
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

    /**
     * 根据用户name返回用户
     */
    public static function getUserByName($user_name){
        $criteria = new EMongoCriteria();
        $criteria->user_name('==', $user_name);
        $user = self::model()->find($criteria);
        return $user;
    }

    public function parseRow($row,$output=array(),$no_cache = false){
            $newRow = array();
            $newRow['id'] = (string)$row['_id'];
            $newRow['sex'] = CommonFn::get_val_if_isset($row,'sex',3);
            $newRow['avatar'] = CommonFn::get_val_if_isset($row,'avatar',Yii::app()->params['defaultUserAvatar']);
            if($newRow['avatar']==''){
                $newRow['avatar'] = Yii::app()->params['defaultUserAvatar'];
            }
            $newRow['order_count'] = CommonFn::get_val_if_isset($row, 'order_count', 0);
            $newRow['balance'] = CommonFn::get_val_if_isset($row, 'balance', 0);
            $newRow['openid'] = CommonFn::get_val_if_isset($row,'openid','');
            $newRow['wx_pub_openid'] = CommonFn::get_val_if_isset($row,'wx_pub_openid','');
            $newRow['longitude'] = isset($row['position'][0]) ? floatval($row['position'][0]) : 121;
            $newRow['latitude'] = isset($row['position'][1]) ? floatval($row['position'][1]) : 31;
            $newRow['address'] = CommonFn::get_val_if_isset($row,'address',array());
            if(is_array($newRow['address'])&&count($newRow['address'])){
                $shop_address = array();
                foreach($newRow['address'] as $address){
                    $temp_addr = isset($address['province'])?$address['province']:'';
                    $temp_addr = isset($address['city'])?$temp_addr.$address['city']:$temp_addr;
                    $temp_addr = isset($address['area'])?$temp_addr.$address['area']:$temp_addr;
                    $temp_addr = isset($address['poi']['name'])?$temp_addr.$address['poi']['name']:$temp_addr;
                    $temp_addr = isset($address['detail'])?$temp_addr.$address['detail']:$temp_addr;
                    $address['address_view'] = $temp_addr;
                    $shop_address[] = $address;
                }
                $newRow['address'] = $shop_address;
            }else{
                $newRow['address'] = array();
            }

        $newRow['user_name'] = CommonFn::get_val_if_isset($row,'user_name','');

        $newRow['register_time'] = CommonFn::get_val_if_isset($row,'register_time',time());

        $newRow['register_time_str'] = CommonFn::sgmdate("Y年n月d日", $newRow['register_time'],1);

        $newRow['city_info'] = CommonFn::get_val_if_isset($row,'city_info',array("province"=>"","city"=>"","area"=>""));

        $newRow['shop_address'] = CommonFn::get_val_if_isset($row,'shop_address',array());
        if(is_array($newRow['shop_address'])&&count($newRow['shop_address'])){
            $shop_address = array();
            foreach($newRow['shop_address'] as $address){
                $temp_addr = isset($address['province'])?$address['province']:'';
                $temp_addr = isset($address['city'])?$temp_addr.$address['city']:$temp_addr;
                $temp_addr = isset($address['area'])?$temp_addr.$address['area']:$temp_addr;
                $temp_addr = isset($address['poi']['name'])?$temp_addr.$address['poi']['name']:$temp_addr;
                $temp_addr = isset($address['detail'])?$temp_addr.$address['detail']:$temp_addr;
                $address['address_view'] = $temp_addr;
                $shop_address[] = $address;
            }
            $newRow['shop_address'] = $shop_address;
        }else{
            $newRow['shop_address'] = array();
        }

        if(!isset($newRow['city_info']['province'])){
            $newRow['city_info']['province'] = '';
        }
        if(!isset($newRow['city_info']['city'])){
            $newRow['city_info']['city'] = '';
        }
        if(!isset($newRow['city_info']['area'])){
            $newRow['city_info']['area'] = '';
        }

        if(APPLICATION=='admin'){

            $newRow['channel'] = CommonFn::get_val_if_isset($row,'channel','');
            $newRow['unionid'] = CommonFn::get_val_if_isset($row,'unionid','');
            $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
            $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
            $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");
            //unset($newRow['status']);
        }

        return $this->output($newRow,$output);

    }

}
