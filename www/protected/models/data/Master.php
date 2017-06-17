<?php
/**
 * Created by JetBrains PhpStorm.
 * User: charlie
 * Date: 13-11-29
 * Time: 下午4:47
 * 保洁师、摄影师、训犬师等
 */
class Master extends MongoAr
{
    public $_id;

    public $status = 0;		//状态

    public $user;//对应的后台帐号
    public $ruser;//对应的前端帐号

    public $type; //"beautician"=> 保洁师

    public $name;//名字
    public $city_info = array();          ////用户所属的城市信息  "province"=>"上海","city"=>"上海","area"=>"浦东"    或者   "province"=>"江苏","city"=>"苏州","area"="昆山"
    public $position=array();   //用户的坐标
    public $mobile = '';             //手机号
    public $address = '';                    //地址
    public $sex = 3;           			//性别  1男  2女  3不告诉你
    public $avatar = '';        		//头像七牛的地址

    public $desc='';//简介
    public $pics=array();//相册

    public $coverage = array();//服务范围


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
        return 'master';
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];


        $newRow['desc'] = CommonFn::get_val_if_isset($row,'desc','');
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',0);

        $newRow['type'] = CommonFn::get_val_if_isset($row,'type','');
        $newRow['name'] = CommonFn::get_val_if_isset($row,'name','');
        $newRow['mobile'] = CommonFn::get_val_if_isset($row,'mobile','');
        $newRow['address'] = CommonFn::get_val_if_isset($row,'address','');
        $newRow['sex'] = CommonFn::get_val_if_isset($row,'sex',3);

        $newRow['city_info'] = CommonFn::get_val_if_isset($row,'city_info',array("province"=>"","city"=>"","area"=>""));
        if(!isset($newRow['city_info']['province'])){
            $newRow['city_info']['province'] = '';
        }
        if(!isset($newRow['city_info']['city'])){
            $newRow['city_info']['city'] = '';
        }
        if(!isset($newRow['city_info']['area'])){
            $newRow['city_info']['area'] = '';
        }

        $newRow['longitude'] = isset($row['position'][0]) ? floatval($row['position'][0]) : 121;
        $newRow['latitude'] = isset($row['position'][1]) ? floatval($row['position'][1]) : 31;

        $newRow['avatar'] = CommonFn::get_val_if_isset($row,'avatar',Yii::app()->params['defaultUserAvatar']);
        if($newRow['avatar']==''){
            $newRow['avatar'] = Yii::app()->params['defaultUserAvatar'];
        }

        $newRow['pics'] = CommonFn::get_val_if_isset($row,'pics',array());
        if(empty($newRow['pics'])){
            $newRow['pics'] = CommonFn::$empty;
        }


        $user = array();
        $t_user = new ZUser();
        if(isset($row['user'])){
            $_user = $t_user->get($row['user']);
            $user = RUser::model()->parseRow($_user->attributes,array('user_name','level','id','avatar','is_fake_user'));
        }
        $newRow['user'] = $user;



        return $this->output($newRow,$output);
    }
}