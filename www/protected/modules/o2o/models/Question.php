<?php
class Question extends MongoAr
{
    public $_id;    //问题的object id
    public $content;//内容
    public $time;//问题发表时间

    public $user;//作者object id
    public $quote;//引用的问题object id
    public $type;//
    public $status=1;//状态   1正常   0删除   -1垃圾
    public $weight=0;//问题的权重

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_data'));
        parent::__construct($scenario);
    }

    public static $status_option = array(
        1 => array('name' => '正常'),
        0 => array('name' => '删除'),
        -1 => array('name' => '垃圾')
    );

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'question';
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

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];
        $newRow['content'] = CommonFn::get_val_if_isset($row,'content','');

        $newRow['time'] = CommonFn::get_val_if_isset($row,'time',time());
        $newRow['time_str'] = CommonFn::sgmdate("Y年n月d日", $newRow['time'],1);

        $user = array();
        if(isset($row['user'])){
            $_user = RUser::get($row['user']);
            $user = RUser::model()->parseRow($_user->attributes,array('user_name','certify_status','certify_info','user_type','can_be_message','can_access','level','id','avatar','is_fake_user'));
        }
        $newRow['user'] = $user;

        $quote = array();
        if(!empty($row['quote'])){
            $_question = self::get($row['quote']);
            $quote['id'] = (string)$row['quote'];
            $quote['content'] = $_question->content;
            $quote['status'] = $_question->status;
            $quote['time'] = $_question->time;
            $_user = RUser::get($_question->user);
            $puser = RUser::model()->parse($_user->attributes,false,array('id','certify_status','certify_info','user_type','can_be_message','can_access','user_name'));
            $quote['user'] = $puser;
        }else{
            $quote = (object)$quote;
        }
        $newRow['quote'] = $quote;
        $newRow['weight'] = CommonFn::get_val_if_isset($row,'weight',0);
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);
        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");
        if(APPLICATION=='api'||APPLICATION=='common'){
            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
        }
        return $this->output($newRow,$output);
    }

}