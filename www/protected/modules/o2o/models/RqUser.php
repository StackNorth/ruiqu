<?php
/**
 * Created by PhpStorm.
 * User: north
 * Date: 2017/6/16
 * Time: 上午11:28
 */
class RqUser extends MongoAr
{
    public $_id;
    public $uid;//用户id 必须
    public $count_money = 0.0;//总收入 必须
    public $pay_money = 0.0;//总支出 必须
    public $post_num = 0;//话题数 必须
    public $flist_count = 0;//动态数 必须
    public $account_money = 0;//钱包余额 必须
    public $phone;//手机号 必须
    public $regist_time_i;//注册时间 必须
    public $user_name;//用户名 必须

    public $user_credit;//可能是信用度
    public $post;//可能是发帖数
    public $use_exp;//不知道干嘛的
    public $save_time_i;//不知道干嘛的
    public $last_time;//最后登录时间
    public $user_level;//用户登记
    public $dynamic;//不知道
    public $tx_id;//不知道
    public $pm;//不知道


    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_ruiqu'));
        parent::__construct($scenario);
    }


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'user';
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
        $newRow['uid'] = CommonFn::get_val_if_isset($row,'uid','');
        $newRow['user_credit'] = CommonFn::get_val_if_isset($row,'user_credit','');
        $newRow['post'] = CommonFn::get_val_if_isset($row,'post','');
        $newRow['phone'] = CommonFn::get_val_if_isset($row,'phone','');
        $newRow['regist_time_i'] = CommonFn::get_val_if_isset($row,'regist_time_i','');
        $newRow['user_name'] = CommonFn::get_val_if_isset($row,'user_name','');
        $newRow['use_exp'] = CommonFn::get_val_if_isset($row,'use_exp','');
        $newRow['save_time_i'] = CommonFn::get_val_if_isset($row,'save_time_i','');
        $newRow['last_time'] = CommonFn::get_val_if_isset($row,'last_time','');
        $newRow['tx_id'] = CommonFn::get_val_if_isset($row,'tx_id','');
        $newRow['user_level'] = CommonFn::get_val_if_isset($row,'user_level','');
        $newRow['dynamic'] = CommonFn::get_val_if_isset($row,'dynamic','');
        $newRow['pm'] = CommonFn::get_val_if_isset($row,'pm','');
        $newRow['count_money'] = CommonFn::get_val_if_isset($row,'count_money',0);
        $newRow['pay_money'] = CommonFn::get_val_if_isset($row,'pay_money',0);
        $newRow['post_num'] = CommonFn::get_val_if_isset($row,'post_num',0);
        $newRow['flist_count'] = CommonFn::get_val_if_isset($row,'flist_count',0);
        $newRow['account_money'] = CommonFn::get_val_if_isset($row,'account_money',0);
        if(APPLICATION=='admin'){
            $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
            $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
            $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");

        }
        return $this->output($newRow,$output);
    }


}
