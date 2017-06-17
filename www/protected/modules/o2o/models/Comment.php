<?php
class Comment extends MongoAr
{
    public $_id;    //评价的object id

    public $content;//内容

    public $score;//评分

    public $order;//订单object id

    public $time;//评价发表时间

    public $user;//作者object id

    public $pics;

    public $status =1;//状态   1正常   0删除   -1垃圾

    public $type;// 

    public $weight = 0;//评价权重

    public $technician = 0;
    public $technicians = array();//多个保洁师
    public $technician_name = '';

    public $reply = ''; // 客服回复


    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static $status_option = array( 
        1 => array('name' => '正常'),
        0 => array('name' => '删除')
        /*-1 => array('name' => '垃圾')*/
    );



    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'comment';
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
     * 根据OrderId获取评价
     */
    public static function getByOrder($order) {
        if (CommonFn::isMongoId($order)) {
            $criteria = new EMongoCriteria();
            $criteria->order('==', $order);
            $model = self::model()->find($criteria);
            return $model;
        } else {
            return false;
        }
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];

        $newRow['content'] = CommonFn::get_val_if_isset($row,'content','');

        $newRow['score'] = CommonFn::get_val_if_isset($row,'score',5);

        $newRow['order'] = (string)CommonFn::get_val_if_isset($row,'order','');

        $newRow['time'] = CommonFn::get_val_if_isset($row,'time',0);
        $newRow['time_str'] = date('Y-m-d H:i:s',$newRow['time']);
        $newRow['time_str_short'] = date('m-d H:i', $newRow['time']);
        $newRow['type'] = CommonFn::get_val_if_isset($row,'type',1);
        $newRow['weight'] = CommonFn::get_val_if_isset($row,'weight',0);
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',0);
        $newRow['pics'] = CommonFn::get_val_if_isset($row,'pics',array());
        if(empty($newRow['pics'])){
            $newRow['pics'] = CommonFn::$empty;
        }
        $user = array();
        $t_user = new ZUser();
        if(isset($row['user'])){
            $_user = $t_user->get($row['user']);
            $user = RUser::model()->parseRow($_user->attributes,array('user_name','user_type','can_be_message','can_access','level','id','avatar','is_fake_user'));
        }
        $newRow['user'] = $user;

        $newRow['technicians'] = CommonFn::get_val_if_isset($row, 'technicians');
        //$newRow['technician_name'] = '';
        $newRow['tech_info'] = [];
        if ($newRow['technicians']) {
            foreach($newRow['technicians'] as $technicians) {
                $tech_info = TechInfo::get($technicians);
                if ($tech_info) {
                    $newRow['tech_info'][] = TechInfo::model()->parseRow($tech_info, array('id', 'name', 'mobile', 'weixin_userid'));
                    $newRow['technician_name'][] = $tech_info->name;
                }
            }
        }

        $newRow['reply'] = CommonFn::get_val_if_isset($row, 'reply', '');

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

}