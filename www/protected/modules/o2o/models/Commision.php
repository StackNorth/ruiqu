<?php 
/**
 * 保洁师提成模型
 * @author     2015-12-01
 */
class Commision extends MongoAr {

    public $_id;
    public $time;           // 订单完成时间
    public $booking_time;   // 预定时间
    public $user;           // 保洁师ID
    public $order;          // 订单ID
    public $commision;      // 提成价格
    public $type;           // 订单类型
    /*

    */

    const MAIN   = 0;
    const APPEND = 1;

    public static $type_option = array(
        0  => array('name' => '普通'),
        1  => array('name' => '追加'),
        -1 => array('name' => '手动添加'),
    );

    public function __construct($scenario='insert') {
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static function model($className = __CLASS__) {
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

    /**
     * 根据订单ID获得commision对象
     * @param  MongoId           $id        : 订单ID
     * @return Commision|Boolean $commision : Commision对象或false
     */
    public static function getByOrder($id) {
        if (CommonFn::isMongoId($id)) {
            $criteria = new EMongoCriteria();
            $criteria->order('==', $id);
            $commision = Commision::model()->find($criteria);

            if (empty($commision)) {
                return false;
            } else {
                return $commision;
            }
        } else {
            return true;
        }
    }

    public function getCollectionName () {
        return 'commision';
    }

    /**
     * 计算单个订单内保洁师提成
     * @param  Object  $order       : 订单Object(AppendOrder或ROrder)
     * @param  Boolean $type        : 订单类型
     * @param  String  $scheme_name : 提成方案
     * @return Float   $commision   : 计算所得提成
     */
    public static function getCommision($order, $type = self::MAIN, $scheme_name = 'scheme_30') {
        $commision = 0.0;

        // 订单内服务数量统计
        $products = array();
        $count = array();
        $price = array();
        foreach ($order->products as $key => $row) {
            $products[] = $row['product'];
            $product_id = (string)$row['product'];
            $count[$product_id] = $row['count'];
            $price[$product_id] = $row['price'];
        }

        // 订单内所有服务详细信息
        $criteria = new EMongoCriteria();
        $criteria->_id('in', $products);
        $cursor = Product::model()->findAll($criteria);

        // 提成方案
        $scheme_name = $scheme_name == 'no_scheme' ? 'scheme_30' : $scheme_name;
        $scheme = self::$scheme_list[$scheme_name];
        // 默认百分比
        $default = floatval(substr($scheme_name, strlen($scheme_name) - 2))/100;
        foreach ($cursor as $key => $row) {
            foreach ($scheme as $k => $value) {
                $pattern = '/'.$value['name'].'/';
                $subject = $row->name;
                // 正则匹配字典中的name字段与服务名
                if (preg_match($pattern, $subject) > 0) {
                    if ($value['fixed'] != false) {
                        $commision += $value['fixed'] * $count[(string)$row->_id];
                        break;
                    } else {
                        $commision += floatval($price[(string)$row->_id]) * $count[(string)$row->_id] * $value['percentage'];
                        break;
                    }
                } else {
                    if ($k == count($scheme)) {
                        $commision += floatval($price[(string)$row->_id]) * $count[(string)$row->_id] * $default;
                        break;
                    } else {
                        continue;
                    }
                }
            }
        }

        return $commision;
    }

    /**
     * 整理函数，用于后台管理显示
     */
    public function parseRow($row, $output=array()) {
        $newRow = array();

        $newRow['id']       = (string)$row['_id'];
        $newRow['time']     = CommonFn::get_val_if_isset($row, 'time', 0);
        $newRow['time_str'] = $newRow['time'] == 0? '': date('Y-m-d H:i', $newRow['time']);

        $newRow['user'] = CommonFn::get_val_if_isset($row, 'user', '');
        if ($newRow['user'] != '') {
            //$user = User::get($newRow['user']);
            $newRow['user_str'] = $newRow['user'];
        } else {
            $newRow['user_str'] = '';
        }

        $newRow['order']     = (string)CommonFn::get_val_if_isset($row, 'order', '');
        $newRow['commision'] = CommonFn::get_val_if_isset($row, 'commision', 0);

        $newRow['type']      = CommonFn::get_val_if_isset($row, 'type', -1);
        $type_option         = self::$type_option;
        $newRow['type_str']  = $type_option[$newRow['type']]['name'];

        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log']  = CommonFn::get_val_if_isset($row,'action_log',"");

        $newRow['booking_time'] = CommonFn::get_val_if_isset($row, 'booking_time', 0);
        if ($newRow['booking_time']) {
            if (date('Y', time()) == date('Y', $newRow['booking_time'])) {
                $newRow['booking_time_str'] = date('m月d日 H:i', $newRow['booking_time']);
            } else {
                $newRow['booking_time_str'] = date('Y年m月d日 H:i', $newRow['booking_time']);
            }
        } else {
            $newRow['booking_time_str'] = '';
        }

        return $this->output($newRow, $output);
    }

    /**
     * 提成方案设置
     */
    public static $scheme_option = array(
        -1 => array('name' => '未选择方案',  'alias' => 'no_scheme'),
        0  => array('name' => '提成方案60%', 'alias' => 'scheme_60'),
        1  => array('name' => '提成方案30%', 'alias' => 'scheme_30'),
        2  => array('name' => '提成方案50%', 'alias' => 'scheme_50'),
        3  => array('name' => '提成方案80%', 'alias' => 'scheme_80'),
    );

    /**
     * 提成方案字典
     * key : 方案名称scheme_百分比
     * @param String         $name       : 类别的名称
     * @param Float          $percentage : 提成百分比
     * @param Boolean|Number $fixed      : 是否采用固定提成价格，否则填false，是则填相应数字
     */
    public static $scheme_list = array(
        /* 提成方案60% */
        'scheme_60' => array(
            1  => array('name' => '日常清洁',     'percentage' => 0.6, 'fixed' => false),
            2  => array('name' => '深度清洁',     'percentage' => 0.6, 'fixed' => false),
            3  => array('name' => '除螨杀菌',     'percentage' => 0.6, 'fixed' => false),
            4  => array('name' => '家电清洗',     'percentage' => 0.6, 'fixed' => false),
            5  => array('name' => '民宿保洁',      'percentage' => 0.6, 'fixed' => false),
            6  => array('name' => '新居开荒',     'percentage' => 0.6, 'fixed' => false),
            7  => array('name' => '母婴房清洁',     'percentage' => 0.6, 'fixed' => false),
            15 => array('name' => '租房清洁',   'percentage' => 0.6, 'fixed' => false),
            8  => array('name' => '擦玻璃',   'percentage' => 0.6, 'fixed' => false),
        ),
        /* 提成方案30% */
        'scheme_30' => array(
            1  => array('name' => '日常清洁',     'percentage' => 0.3, 'fixed' => false),
            2  => array('name' => '深度清洁',     'percentage' => 0.3, 'fixed' => false),
            3  => array('name' => '除螨杀菌',     'percentage' => 0.3, 'fixed' => false),
            4  => array('name' => '家电清洗',     'percentage' => 0.3, 'fixed' => false),
            5  => array('name' => '民宿保洁',      'percentage' => 0.3, 'fixed' => false),
            3  => array('name' => '新居开荒',     'percentage' => 0.5, 'fixed' => false),
            7  => array('name' => '母婴房清洁',     'percentage' => 0.3, 'fixed' => false),
            15 => array('name' => '租房清洁',   'percentage' => 0.3, 'fixed' => false),
            8  => array('name' => '擦玻璃',   'percentage' => 0.3, 'fixed' => false),
        ),
        /* 提成方案50% */
        'scheme_50' => array(
            1  => array('name' => '日常清洁',     'percentage' => 0.5, 'fixed' => false),
            2  => array('name' => '深度清洁',     'percentage' => 0.5, 'fixed' => false),
            3  => array('name' => '除螨杀菌',     'percentage' => 0.5, 'fixed' => false),
            4  => array('name' => '家电清洗',     'percentage' => 0.5, 'fixed' => false),
            5  => array('name' => '民宿保洁',      'percentage' => 0.5, 'fixed' => false),
            6  => array('name' => '新居开荒',     'percentage' => 0.5, 'fixed' => false),
            7  => array('name' => '母婴房清洁',     'percentage' => 0.5, 'fixed' => false),
            15 => array('name' => '租房清洁',   'percentage' => 0.5, 'fixed' => false),
            8  => array('name' => '擦玻璃',   'percentage' => 0.5, 'fixed' => false),
        ),
        /* 提成方案80% */
        'scheme_80' => array(
            1  => array('name' => '日常清洁',     'percentage' => 0.8, 'fixed' => false),
            2  => array('name' => '深度清洁',     'percentage' => 0.8, 'fixed' => false),
            3  => array('name' => '除螨杀菌',     'percentage' => 0.8, 'fixed' => false),
            4  => array('name' => '家电清洗',     'percentage' => 0.6, 'fixed' => false),
            5  => array('name' => '民宿保洁',      'percentage' => 0.8, 'fixed' => false),
            6  => array('name' => '新居开荒',     'percentage' => 0.8, 'fixed' => false),
            7  => array('name' => '母婴房清洁',     'percentage' => 0.8, 'fixed' => false),
            15 => array('name' => '租房清洁',   'percentage' => 0.8, 'fixed' => false),
            8  => array('name' => '擦玻璃',   'percentage' => 0.8, 'fixed' => false),
        ),
    );

}