<?php
/**
 * User: charlie
 * 商品/服务
 */
class Product extends MongoAr
{
    public $_id;
    public $name;//服务的名字
    public $status=0;//产品状态 0=>暂停使用  1=>正常使用   -1=>已删除
    public $order=0;//产品权重
    public $type;//服务的适用类型
    public $is_extra=0;
    public $desc;//图文介绍   json格式  [{  // 图文详情"type": 1,  //  1: 图片url, 2: 纯文本 "content": "http://a.big.jpg"}, {"type": 1,"content": "http://b.big.jpg"}, {"type": 2,"content": "描述文案..."},  // ... ]
    public $pics=array();//七牛的地址  array('url'=>'http://xxx.qiniudn.com/1414476356856.jpg','height'=>1180,'width'=>2340)

    public $price=0;//商品的单价  单位：元

    public $extra=array();//array('types'=>array(array('type'=>'一室一卫','price'=>180),array('type'=>'二室一卫','price'=>280)))

    public static $status_option = array(
        0 => array('name' => '暂停使用'),
        1 => array('name' => '正常使用'),
        -1 => array('name' => '已删除')
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
        return 'products';
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];
        $newRow['price'] = CommonFn::get_val_if_isset($row,'price',0);

        $newRow['name'] = CommonFn::get_val_if_isset($row,'name','');
        $newRow['desc'] = CommonFn::get_val_if_isset($row,'desc','');

        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);
        $newRow['is_extra'] = CommonFn::get_val_if_isset($row,'is_extra',0);


        $newRow['order'] = CommonFn::get_val_if_isset($row,'order',1);

        $newRow['type'] = CommonFn::get_val_if_isset($row,'type',1);
        $newRow['type_str'] = Yii::app()->params['o2o_service'][$newRow['type']]['name'];

        $newRow['extra'] = CommonFn::get_val_if_isset($row,'extra',array());

        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");

        $newRow['pics'] = CommonFn::get_val_if_isset($row,'pics',array());
        if(empty($newRow['pics'])){
            $newRow['pics'] = CommonFn::$empty;
        }


        if(APPLICATION=='api'){
            //unset($newRow['status']);
            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
        }

        return $this->output($newRow,$output);
    }

}
