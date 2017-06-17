<?php
/**
 * User: charlie
 * 首页推荐的幻灯片
 */
class Slide extends MongoAr
{
    public $_id;    //帖子的object id
    public $title;   //  标题
    public $pic=""; //图片的七牛地址
    public $type='topic';   //  链接类型  'topic'  'group'  'url'  'subject'
    public $obj;    //链接的对象的objectid
    public $data=array();//补充数据
    public $status=1;//状态   1正常   0删除
    public $order;//排序权重
    public $city_info=array(); //省份
    public $start_time;//轮播图上线时间戳
    public $end_time;//轮播图结束时间戳


    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_app'));
        parent::__construct($scenario);
    }

    public static $status_option = array(
        1 => array('name' => '正常'),
        0 => array('name' => '删除')
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

    public static function getIndexSlide($province){
        $cache = new ARedisCache();
        $key = 'data_cache_'.__CLASS__.md5($province);
        $data_cache = $cache->get($key);
        $res = array();
        if($data_cache){
            $res = array_values(unserialize($data_cache));
        }else{
            $criteria = new EMongoCriteria();
            $criteria->status('==', 1);
            $criteria->end_time('>=', time());
            $criteria->sort('order', EMongoCriteria::SORT_DESC);
            $cursor = Slide::model()->findAll($criteria);
            $rows = CommonFn::getRows($cursor); 
            foreach ($rows as $key => $value) {
                if($province != 'no' && isset($value['city_info'])&&isset($value['city_info']['province'])&&$value['city_info']['province']!=$province&&$value['city_info']['province']!=''){
                    unset($rows[$key]);
                }
            }
            $res = Slide::model()->parse($rows);
            $res = array_values($res);
            $cache->set($key,serialize($res),1800);
        }
        return $res;
    }

    public function getCollectionName()
    {
        return 'slide';
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);
        $newRow['title'] = CommonFn::get_val_if_isset($row,'title','');
        $newRow['pic'] = CommonFn::get_val_if_isset($row,'pic','');
        $newRow['type'] = CommonFn::get_val_if_isset($row,'type','topic');
        $newRow['city_info'] = CommonFn::get_val_if_isset($row,'city_info',array("city"=>"","area"=>"","province"=>""));
        $newRow['start_time'] = CommonFn::get_val_if_isset($row,'start_time','');
        $newRow['end_time'] = CommonFn::get_val_if_isset($row,'end_time','');

        $newRow['data'] = CommonFn::get_val_if_isset($row,'data',array());
        $newRow['order'] = CommonFn::get_val_if_isset($row,'order',1);

        if($newRow['type']=='topic'){
            $type = 'ZTopic';
            $model = 'Topic';
        }elseif($newRow['type']=='group'){
            $type = 'ZGroup';
            $model = 'Group';
        }
        if(isset($type) && isset($model) && !empty($model) && !empty($type)){
            $z_type = new $type;
            $obj = $z_type->idExist($row['obj']);
            $newRow['obj'] = $model::model()->parseRow($obj);
        }elseif(isset($newRow['type']) && $newRow['type'] == 'url'){
            $newRow['obj'] = $row['obj'];
        // 暂时不上线 2015-12-24    
            if(empty($newRow['obj']['url'])){
                $newRow['obj']['url'] = 'http://www.yiguanjia.me';
                $newRow['obj']['id'] = 'http://www.yiguanjia.me';
            } else {
                $newRow['obj']['id'] = $newRow['obj']['url'];
            }
        }elseif(isset($newRow['type']) && $newRow['type'] == 'subject') {
            $_subject = Subject::get($row['obj']);
            $newRow['obj'] = $_subject->parseRow($_subject->attributes);
        }else{
            $newRow['obj'] = (object)array();
        }

        if(APPLICATION=='api'||APPLICATION=='common'){
            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
            unset($newRow['order']);
            unset($newRow['id']);
            unset($newRow['status']);
        }

        return $this->output($newRow,$output);
    }

}