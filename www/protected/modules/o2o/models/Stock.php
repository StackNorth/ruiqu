<?php 
/**
 * 物资领取情况模型
 * @author     2015-09-18
 */
class Stock extends MongoAr {
    public $_id;
    public $mid;            // 对应物资的id
    public $mname;          // 对应物资的name
    public $user;           // 对应的User 的id
    public $username;       // 对应的Username
    public $time;           // 操作的时间
    public $operate;        // 操作的类型 0=>减少 1=>增加
    public $operate_str;    // 操作的类型
    public $num;            // 数量
    public $tot_price;      // 总价
    public $lastStock;      // 操作前库存数
    public $newStock;       // 操作后库存数
    public $remarks;        // 本次操作的备注信息
    public $object;         // 本次操作的对象ID
    public $objectName;     // 本次操作对象的name
    public $station;        // 领取人员所在区域
    public $stationName;    // 领取人员所在区域的名称

    public static $operate_option = array(
        1 => array('name' => '入库'),
        0 => array('name' => '出库')
    );

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static function model($className=__CLASS__) {
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

    public function getCollectionName () {
        return 'stock';
    }

    public function parseRow($row, $output = array()) {
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];
        $newRow['mid'] = (string)$row['mid'];
        $newRow['mname'] = CommonFn::get_val_if_isset($row, 'mname', '');
        $newRow['user'] = CommonFn::get_val_if_isset($row, 'user', '');
        $newRow['username'] = CommonFn::get_val_if_isset($row, 'username', '');
        $newRow['time'] = date('Y-m-d H:i:s', intval(CommonFn::get_val_if_isset($row, 'time', 0)));
        $newRow['operate'] = intval(CommonFn::get_val_if_isset($row,'operate',1));
        $newRow['operate_str'] = CommonFn::get_val_if_isset($row,'operate_str', '');
        $newRow['num'] = intval(CommonFn::get_val_if_isset($row, 'num', 0));
        $newRow['tot_price'] = CommonFn::get_val_if_isset($row,'tot_price', 0);
        $newRow['lastStock'] = intval(CommonFn::get_val_if_isset($row, 'lastStock', 0));
        $newRow['newStock'] = intval(CommonFn::get_val_if_isset($row, 'newStock', 0));
        $newRow['remarks'] = CommonFn::get_val_if_isset($row,'remarks', '');
        
        $newRow['object'] = CommonFn::get_val_if_isset($row, 'object', '');
        $newRow['objectName'] = CommonFn::get_val_if_isset($row, 'objectName', '');

        $newRow['station'] = isset($row['station']) ? (string)$row['station'] : 'noStation';
        $newRow['stationName'] = CommonFn::get_val_if_isset($row, 'stationName', '');

        $newRow['action_user'] = CommonFn::get_val_if_isset($row, 'action_user', '');
        $newRow['action_time'] = CommonFn::get_val_if_isset($row, 'action_time', '');
        $newRow['action_log'] = CommonFn::get_val_if_isset($row, 'action_log', '');

        return $this->output($newRow, $output);
    }
}

?>