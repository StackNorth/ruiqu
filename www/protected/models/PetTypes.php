<?php
/**
 * User: charlie
 * 宠物类型模型
 */
class PetTypes extends MongoAr
{
    public $_id;            //宠物类型的object id  如：4e12e3c3912b22d362bdc022
    public $name;           //宠物类型的名字
    public $pic;            //每种类型的宠物都有一张图片
    public $parent;         //父节点的object id  这是树形结构   比如：狗狗=》贵宾
    public $level = 1;      //等级
    public $status=1;       //1 正常  0 删除
    public $common=0;       //常见  1: 常见 0: 不常见
    public $weight=0;       //排序权重

    public static $status_option = array(
        0 => array('name' => '已删除'),
        1 => array('name' => '正常', 'filter' => true)
    );

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'pet_types';
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
     * 父级分类设置
     */
    public static $parent_option = [
        1 => [
            'id' => [
                '54671c4b0eb9fb89048b45f5',     // 狗狗
            ],
            'name' => '狗狗',
        ],
        2 => [
            'id' => [
                '546805e40eb9fb32018b45fe',     // 猫猫
            ],
            'name' => '猫猫',
        ],
        100 => [
            'id' => [
                '56a88bc5a84ea0064e8cdddf',     // 兔子
                '56a88c51a84ea0882f8b61c1',     // 鼠类
                '56a88c7ba84ea0e0478d6d2b',     // 其他
            ],
            'name' => '其他',
        ],
    ];

    /**
     * 利用MongoDB-Distinct获取宠物类型列表
     * 过Redis，过期时间7200秒
     *
     * @return Array $list : 所有在售宠物的类型列表
     */
    public static function getPetTypeIds() {
        $redis_cache = new ARedisCache();
        $cache = $redis_cache->get('pet_type_id_list');
        if (!$cache) {
            $cache_data = [];
        } else {
            $cache_data = unserialize($cache);
            $cache_data['expire'] = CommonFn::getValFromArray($cache_data, 'expire', 0);
        }

        if (!$cache_data || $cache_data['expire'] < time()) {
            $list = [];
        } else {
            $list = $cache_data['data'];
        }

        if (!$list) {
            $distinct_query = ['status' => 1];
            $mongo = new MongoClient(DB_CONNETC);
            $db = $mongo->deal;
            $collection = $db->selectCollection('pets');
            $list = $collection->distinct('pet_type', $distinct_query);

            $cache_data = ['data' => $list, 'expire' => time() + 7200];
            $redis_cache->set('pet_type_id_list', serialize($cache_data));
        }

        return $list;
    }

    /**
     * 获取宠物类型列表
     * 过Redis，过期时间7200秒
     *
     * @param  Int   $page : 页码，默认为1
     * @param  Int   $rows : 每页显示数目，默认为8
     *
     * @return Array $data : 返回结果
     * $data = [
     *     'list'       => $list,       // 类型列表
     *     'page'       => $page,       // 页码
     *     'rows'       => $rows,       // 每页显示数目
     *     'totalCount' => $totalCount, // 总数
     *     'page_count' => $page_count, // 总页数
     * ];
     */
    public static function getPetTypeList($page = 1, $rows = 4) {

        $criteria = new EMongoCriteria;
        $criteria->status('==',1);
        $criteria->_id('in',self::getPetTypeIds());
        $criteria->sort('weight',EMongoCriteria::SORT_DESC);
        $criteria->offset(($page - 1) * $rows);
        $criteria->limit($rows);

        $cursor = self::model()->findAll($criteria);

        $totalCount = $cursor->count();

        $list = [];
        foreach ($cursor as $key => $item) {
            $list[] = self::model()->parseRow($item->attributes);
        }

        $data = [
            'list'       => $list,
            'page'       => $page,
            'rows'       => $rows,
            'totalCount' => $totalCount,
            'page_count' => CommonFn::getPageCount($rows, $totalCount),
        ];
        return $data;
    }

    /**
     * 获取父级分类列表
     * 过Redis，过期时间7200秒
     *
     * @return Array $list : 包含所有父级分类ObjectId的列表
     * $list = [
     *     ObjectId('......'),
     * ];
     */
    public static function getParentPetTypes() {
        $redis_cache = new ARedisCache();
        $cache = $redis_cache->get('parent_pet_types');
        if (!$cache) {
            $cache_data = [];
        } else {
            $cache_data = unserialize($cache);
            $cache_data['expire'] = CommonFn::getValFromArray($cache_data, 'expire', 0);
        }

        if (!$cache_data || $cache_data['expire'] < time()) {

            $criteria = new EMongoCriteria;
            $criteria->status('==',1);
            $criteria->level('==',1);
            $criteria->sort('_id',EMongoCriteria::SORT_ASC);
            $cursor = self::model()->findAll($criteria);

            $list = [];
            foreach ($cursor as $key => $item) {
                $list[] = self::model()->parseRow($item->attributes);
            }

            $cache_data = ['data' => $list, 'expire' => time() + 7200];
            $redis_cache->set('parent_pet_types', serialize($cache_data));
        } else {
            $list = $cache_data['data'];
        }

        return $list;
    }

    //获取宠物类型列表
    public function getPetList($parent = null,$common=100,$level=0){
        $criteria = new EMongoCriteria();
        $criteria->status('==',1);

        if($parent){
            $criteria->parent('==',$parent->_id);
        }
        if($common != 100){
            if($common == 0){
                $criteria->addCond('common', 'or', 0);
                $criteria->addCond('common', 'or', null);
            }else{
                $criteria->common('==',1);
            }
        }

        if($level) {
            $criteria->level('==', $level);
        }

        $criteria->sort('weight',EMongoCriteria::SORT_DESC);

        $cursor = self::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $res_list = array();
        foreach ($rows as $key => $value) {
            $res_list[] = self::parseRow($value);

        }

        return $res_list;
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];
        $newRow['name'] = CommonFn::get_val_if_isset($row,'name','');
        $newRow['pic'] = CommonFn::get_val_if_isset($row,'pic',Yii::app()->params['defaultPetTypePic']);//默认图标
        $newRow['parent'] = (string)CommonFn::get_val_if_isset($row,'parent','');
        $newRow['parent_name'] = '';
        if($newRow['parent']){
           $parent = $this->get($row['parent']);
           $newRow['parent_name'] = $parent->name;
        }

        $newRow['level'] = CommonFn::get_val_if_isset($row,'level',1);
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);
        $newRow['common'] = CommonFn::get_val_if_isset($row, 'common', 0);
        $newRow['weight'] = CommonFn::get_val_if_isset($row, 'weight', 0);
        if(APPLICATION=='api'||APPLICATION=='common'){
            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
            unset($newRow['status']);
        }
        return $this->output($newRow,$output);
    }

}