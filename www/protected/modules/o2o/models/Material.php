<?php 
/**
 * 物资
 * @author zhouxuchen 2015-09-16
 */
class Material extends MongoAr {

    public $_id;
    public $name;           // 物资的名称
    public $unit_str;       // 物资的单位（文字）
    public $unit;           // 物资的单位
    public $price;          // 物资的单价
    public $stock;          // 物资的库存
    public $stockWarnLine;  // 库存警戒线
    public $addTime;        // 物资加入的时间
    public $status;         // 物资的库存状态
    public $status_str;     // 物资的库存状态（文字）
    public $enable;         // 是否启用此物资
    public $enable_str;     // 是否启用此物资
    public $remarks;        // 该物资的备注

    public static $status_option = array(
        0 => array('name' => '无库存'),
        1 => array('name' => '紧张'),
        2 => array('name' => '充足'),
        3 => array('name' => '未知')
    );

    public static $enable_option = array(
        0 => array('name' => '停用'),
        1 => array('name' => '启用')
    );

    public static $unit_option = array(
        1 => array('name' => '瓶'),
        2 => array('name' => '袋'),
        3 => array('name' => '盒'),
        4 => array('name' => '台'),
        5 => array('name' => '件'),
        6 => array('name' => '双'),
        7 => array('name' => '只'),
        8 => array('name' => '个'),
        9 => array('name' => '套'),
        10 => array('name' => '副'),
        11 => array('name' => '毫升')
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

    public function getCollectionName () {
        return 'material';
    }

    public function parseRow($row, $output = array()) {
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];
        $newRow['name'] = CommonFn::get_val_if_isset($row, 'name', '');
        $newRow['unit_str'] = CommonFn::get_val_if_isset($row, 'unit_str', '');
        $newRow['unit'] = CommonFn::get_val_if_isset($row, 'unit', 0);
        $newRow['price'] = CommonFn::get_val_if_isset($row, 'price', 0.00);
        $newRow['stock'] = CommonFn::get_val_if_isset($row, 'stock', 0);
        $newRow['stockWarnLine'] = CommonFn::get_val_if_isset($row, 'stockWarnLine', 0);
        $newRow['addTime'] = date('Y-m-d H:i', CommonFn::get_val_if_isset($row, 'addTime', 0));
        $newRow['status_str'] = CommonFn::get_val_if_isset($row, 'status_str', '');
        $newRow['status'] = CommonFn::get_val_if_isset($row, 'status', 0);

        $newRow['enable'] = CommonFn::get_val_if_isset($row, 'enable', 0);
        $newRow['enable_str'] = CommonFn::get_val_if_isset($row, 'enable_str', 0);
        $newRow['action_user'] = CommonFn::get_val_if_isset($row, 'action_user', '');
        $newRow['action_time'] = CommonFn::get_val_if_isset($row, 'action_time', '');
        $newRow['action_log'] = CommonFn::get_val_if_isset($row, 'action_log', '');

        $newRow['material_remarks'] = CommonFn::get_val_if_isset($row, 'remarks', '');

        return $this->output($newRow, $output);
    }
}
?>