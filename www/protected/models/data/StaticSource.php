<?php 
/**
 * 静态资源模型
 */
class StaticSource extends MongoAr {

    public $_id;
    public $key;
    public $title;
    public $content;
    public $remark;

    public function __construct($scenario='insert') {
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_data'));
        parent::__construct($scenario);
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function getCollectionName() {
        return 'static_sources';
    }

    public static function get($_id) {
        if (CommonFn::isMongoId($_id)) {
            $criteria = new EMongoCriteria();
            $criteria->_id('==', $_id);
            $model = self::model()->find($criteria);
            return $model;
        } else {
            return false;
        }
    }

    public static function getByKey($key) {
        $criteria = new EMongoCriteria();
        $criteria->key('==', $key);
        $model = self::model()->find($criteria);
        return $model;
    }

    public function parseRow($row, $output = []) {
        $newRow = [];

        $newRow['id'] = (string)$row['_id'];
        $newRow['key'] = CommonFn::get_val_if_isset($row, 'key', '');
        $newRow['title'] = CommonFn::get_val_if_isset($row, 'title', '');
        $newRow['content'] = CommonFn::get_val_if_isset($row, 'content', '');
        $newRow['content_short'] = '#内容#';
        $newRow['remark'] = CommonFn::get_val_if_isset($row, 'remark', '');
        return $this->output($newRow, $output);
    }

}