<?php
/**
 * 订单追加服务表
 */
class AppendOrder extends MongoAr
{
    public $_id;
    public $order;
    public $charge_id;//ping++的chargeId,charge_id即为支付单号
    public $pay_channel;//支付渠道
    public $append_time;//追加时间

    public $products=array(); //订单包含的商品数组   数据库设计  支持多个产品在一个订单

    public $price;  //订单金额

    public $status=0;//状态 0=>待支付  1=>已支付 -1=>已退款
    
    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static $status_option = array(
        0  => array('name' => '待支付'),
        1  => array('name' => '已支付'),
        -1 => array('name' => '已退款'),
    );

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
        return 'append_order';
    }


    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];
        $newRow['order'] = (string)CommonFn::get_val_if_isset($row,'order','');
        $newRow['pay_channel'] = CommonFn::get_val_if_isset($row,'pay_channel','');
        $newRow['charge_id'] = CommonFn::get_val_if_isset($row,'charge_id','');
        $newRow['price'] = CommonFn::get_val_if_isset($row,'price','');
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',0);
        $newRow['status_str'] = self::$status_option[$newRow['status']]['name'];
        if($newRow['status'] == 1){
            $newRow['book_status_str'] = '已支付';
        }elseif ($newRow['status'] == 0) {
            $newRow['book_status_str'] = '未支付';
        }
        $products = array();
        $newRow['products_str'] = '';
        if(isset($row['products'])&&is_array($row['products'])){
            foreach ($row['products'] as $key => $product) {
                $product_obj = Product::get($product['product']);
                $temp_info = $product_obj->parseRow($product_obj);
                $temp_info['count'] = $product['count'];
                $products[] = $temp_info;
                if($key == 0){
                    $newRow['products_str'] .= $temp_info['name'];
                }else{
                    $newRow['products_str'] .= '+'.$temp_info['name'];
                }
            }
        }
        $newRow['products'] = $products;
        if(!isset($newRow['products'])||empty($newRow['products'])){
            $newRow['products']=CommonFn::$empty;
        }
        $newRow['append_time'] = CommonFn::get_val_if_isset($row,'append_time',0);
        $newRow['append_time_str'] = date('n月d日 H:i',$newRow['append_time']);
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