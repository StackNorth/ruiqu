<?php 
/**
 * 宠物模型
 */
class DealPet extends MongoActiveRecord {
    public $_id;
    public $is_one_pay = 0;             //一元购产品 是否为一元购产品  默认0 不是  1 是
    public $one_pay_counts = 0;         //如果是一元购产品  总份数
    public $name;               // String   : 名称
    public $status = 0;         // Int      : 状态
    public $price;              // Int      : 价格
    public $pics = [];          // Array    : 图片，参考Topic模型pics字段
    public $root_parent;        // ObjectId : 父分类id
    public $kennel;             // ObjectId : 所属商家
    public $pet_type;           // ObjectId : 宠物类型
    public $birth_date;         // Int      : 出生日期时间戳
    public $gender;             // Int      : 性别
    public $tags = [];          // Array    : 标签
    public $father_info;        // Object   : 父亲信息
    public $mother_info;        // Object   : 母亲信息
    public $desc;               // String   : 说明文字
    public $vaccine_info;       // Array    : 疫苗信息  数组 包含brand和time
    public $add_time;           // Int      : 添加时间
    public $sort_weight = 0;    // int      : 排序权重
    //public $delivery_time;      // Int      : 交货时间，时间范围，取值范围0-7
    //public $delivery_date;      // 发货日期
    public $deworming_info;     // array   : 驱虫信息 数组 包含brand和time
    //public $hair_color;         // Stirng   : 毛色
    public $video;              // Object   : 视频 {url:'', length:0, 'avatar':''}
    public $carriage;           // Object   : 运费 {nonlocal:0, local:0}
    public $last_modify;        // Int      : 最后一次修改时间
    public $view_count = 0;         // Int      : 查看次数
    public $contact_count = 0;      // Int      : 咨询数
    public $region;             // Object   : 冗余字段，用于地区显示及筛选，同kennel
    public $last_deny_reason;   // String   : 最后一次审核未通过理由
    public $recommend=0;          // Int 是否推荐  1推荐  0不推荐
    public $recommend_time;//推荐的截至时间  时间戳
    public $reply_count = 0;//回复数
    public $all_reply_count = 0;//所有的回复数
    public $last_post_time;//最后回复时间
    public $count = 1;//现存数量   针对小宠
    public $from;//数据来源   wozhua xinchong chongwushichang

    public static $status_option = [
        -100 => ['name' => '信息不完善'],
        -2   => ['name' => '审核未通过'],
        -1   => ['name' => '已删除'],
        0    => ['name' => '待审核'],
        1    => ['name' => '待售'],
        2    => ['name' => '待支付'],
        3    => ['name' => '已预订'],
        4    => ['name' => '已交易'],
    ];

    public static $gender_option = [
        0 => ['name' => '未知'],
        1 => ['name' => 'DD'],
        2 => ['name' => 'MM'],
    ];

    public static $from_option = [
        'wozhua' => ['name' => 'wozhua'],
        'xinchong' => ['name' => 'xinchong'],
        'shichang' => ['name' => 'chang'],
    ];

    public static $tag_option = [
        1 => ['name' => '30天保障'],
        2 => ['name' => '①宠①拍'],
        3 => ['name' => '先行赔付']
    ];

    public function __construct($scenario = 'insert') {
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public function getCollectionName() {
        return 'pets';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getNewPet(){
        $cache = new ARedisCache();
        $key = 'data_cache_new_pet_list_'.__CLASS__;
        $data_cache = $cache->get($key);
        $res = array();
        if($data_cache){
            $res = unserialize($data_cache);
        }else{
            $data = array();
            
            $criteria = new EMongoCriteria();
            $criteria->root_parent('==',new MongoId('54671c4b0eb9fb89048b45f5'));//狗狗
            $criteria->status('==',1);
            $criteria->limit(4); 
            $criteria->offset(rand(0,100));
            $cursor = self::model()->findAll($criteria);
            foreach ($cursor as $key => $value) {
                $data[] = $value;
            }
            $dogs = self::model()->parseIndexList($cursor);
            
            $criteria = new EMongoCriteria();
            $criteria->root_parent('==',new MongoId('546805e40eb9fb32018b45fe'));//猫猫
            $criteria->status('==',1);
            $criteria->limit(5); 
            $criteria->offset(rand(0,100));
            $cursor = self::model()->findAll($criteria);
            foreach ($cursor as $key => $value) {
                $data[] = $value;
            }

           // $criteria = new EMongoCriteria();
           // $criteria->root_parent('notin',[new MongoId('546805e40eb9fb32018b45fe'),new MongoId('54671c4b0eb9fb89048b45f5')]);//其他
           // $criteria->status('==',1);
           // $criteria->limit(3); 
           // $criteria->offset(rand(0,10));
           // $cursor = self::model()->findAll($criteria);
           // foreach ($cursor as $key => $value) {
           //     $data[] = $value;
           // }
            foreach ($data as $key => $obj) {
                $temp['id'] = (string)$obj->_id;
                $temp['name'] = $obj->name;
                $temp['price'] = $obj->price;
                $pics = $obj->pics;
                $temp['pic'] = (object)array();
                if($pics){
                    $temp['pic'] = $pics[0];
                }
                $temp['pet_type_info'] = '';
                $temp['pet_type_str'] = '';
                $temp['pet_type_parent'] = '';
                if (CommonFn::isMongoId($obj->pet_type)) {
                    $pet_type = PetTypes::get($obj->pet_type);
                    if ($pet_type) {
                        $temp['pet_type_info'] = PetTypes::model()->parseRow($pet_type);
                        $temp['pet_type_str'] = $pet_type->name;
                        $temp['pet_type_parent'] = (string)$pet_type->parent;
                    }
                }
                $res[] = $temp;
            }
            $cache->set($key,serialize($res),86400);
        }
        return $res;
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

    /**
     * 根据出生日期时间戳计算时间
     * @param Int $time : 出生日期时间戳
     */
    public static function makeAge($time) {
        if (!$time) return '无';
        $days = (strtotime(date('Ymd')) - $time) / 86400;
        if ($days <= 31) {
            return $days.'天';
        } else if ($days == 365) {
            return '一岁';
        } else if ($days > 335 && $days < 365) {
            return '12月'.($days - 335).'天';
        } else if ($days < 365) {
            $age = (int)($days / 30).'个月';
            if ($days % 30) {
                $age .= ($days % 30).'天';
            }
            return $age;
        } else if ($days > 365) {
            $year = floor($days/365);
            $age = $year.'岁';
            if (intval(($days-$year*365) / 30)) {
                $age .= intval(($days-$year*365) / 30).'个月';
            }
            if (($days - $year*365) % 30) {
                $age .= (($days - $year*365) % 30).'天';
            }
            return $age;
        } else {
            return '无';
        }
    }

    /**
     * 宠物信息保存后的回调
     */
    public function afterSave() {
        parent::afterSave();

        // 类型
        // 更新总类型列表
        $type__cache = VariableRedis::get('pet_type_id_list');
        if ($type__cache) {
            $type_cache_data = unserialize($type__cache);
            if (!in_array($this->pet_type, $type_cache_data['data'])) {
                VariableRedis::remove('pet_type_id_list');
            }
        }
        // 该类型下宠物列表还未过缓存，不需要清除

        // 商家
        // 更新商家在售宠物类型列表
        $kennel_type_cache = VariableRedis::get('pet_types_' . (string)$this->kennel);
        if ($kennel_type_cache) {
            VariableRedis::remove('pet_types_' . (string)$this->kennel);
        }
        // 更新商家在售宠物列表
        $kennel_pet_cache = VariableRedis::get('pet_list_' . (string)$this->kennel);
        if ($kennel_pet_cache) {
            VariableRedis::remove('pet_list_' . (string)$this->kennel);
        }
    }

    /**
     * 宠物列表接口
     */
    public function parseIndexList($rows = []) {
        $data = [];
        foreach ($rows as $key => $item) {
            $temp['id'] = (string)$item['_id'];
            // 名字
            $temp['name'] = CommonFn::get_val_if_isset($item, 'name', '');
            // 价格
            $temp['price'] = CommonFn::get_val_if_isset($item, 'price', 0);
            // 商家名
            if (isset($item['kennel']) && CommonFn::isMongoId($item['kennel'])) {
                $kennel = Kennel::get($item['kennel']);
                $temp['kennel_name'] = $kennel->name;
            } else {
                $temp['kennel_name'] = '';
            }
            // 图片
            if (isset($item['pics']) && count($item['pics'])) {
                $temp['pic'] = $item['pics'][0];
            } else {
                $temp['pic'] = [
                    'url' => Yii::app()->params['defaultGoodsAvatar'],
                    'width' => 200,
                    'height' => 200,
                ];
            }
            $data[] = $temp;
        }

        return $data;
    }

    /**
     * 更新宠物的查看数并返回
     */
    public function updateViewCount() {
        // mongo
        $view_count = $this->getAttr('view_count', 0);
        // redis
        $redis_key = 'view_count_' . (string)$this->_id;
        $cache = VariableRedis::get($redis_key);
        if (!$cache) {
            $data = ['count' => 0, 'expire' => (time() + 7200)];
            VariableRedis::set($redis_key, serialize($data));
            $this->view_count = $view_count + 1;
            $this->save();
            return $view_count + 1;
        } else {
            $cache_data = unserialize($cache);
            if (time() < $cache_data['expire']) {
                $data = ['count' => $cache_data['count'] + 1, 'expire' => $cache_data['expire']];
                VariableRedis::set($redis_key, serialize($data));
                return $view_count + $cache_data['count'] + 1;
            } else {
                $data = ['count' => 0, 'expire' => (time() + 7200)];
                VariableRedis::set($redis_key, serialize($data));
                $this->view_count = $view_count + $cache_data['count'] + 1;
                $this->save();
                return $view_count + $cache_data['count'] + 1;
            }
        }
    }

    /**
     * 返回查看数
     */
    public function getViewCount() {
        $key = 'view_count_' . (string)$this->_id;
        $view_count = $this->getAttr('view_count', 0);
        $cache = VariableRedis::get($key);

        if ($cache) {
            $cache_data = unserialize($cache);
            $cache_count = isset($cache_data['count'])? $cache_data['count'] : 0;
            return $view_count + $cache_count;
        } else {
            return $view_count;
        }
    }

    public function parseRow($row, $output = []) {
        $newRow = [];
        $newRow['id']             = (string)$row['_id'];
        $newRow['name']           = CommonFn::get_val_if_isset($row, 'name', '');
        $newRow['status']         = CommonFn::get_val_if_isset($row, 'status', 0);
        $newRow['is_one_pay']         = CommonFn::get_val_if_isset($row, 'is_one_pay', 0);
        $newRow['one_pay_counts']         = CommonFn::get_val_if_isset($row, 'one_pay_counts', 0);
        //如果是一元购的话   在redis存放    还能被购买的份数
        $newRow['one_pay_left_counts'] = 0;
        if($newRow['is_one_pay']){
            $key = 'one_pay_left_counts_'.(string)$row['_id'];
            $result = VariableRedis::get($key);
            if(empty($result)){
                $newRow['one_pay_left_counts'] = $newRow['one_pay_counts'];
            }else{
                $newRow['one_pay_left_counts'] = $result;
            }
        }
        $newRow['status_str']     = self::$status_option[$newRow['status']]['name'];
        $newRow['price']          = CommonFn::get_val_if_isset($row, 'price', 0);
        $newRow['pics']           = CommonFn::get_val_if_isset($row, 'pics', []);
        $newRow['root_parent']    = (string)CommonFn::get_val_if_isset($row, 'root_parent', '');
        $newRow['birth_date']     = CommonFn::get_val_if_isset($row, 'birth_date', 0);
        $newRow['birth_date_str'] = $newRow['birth_date'] ? date('Y-m-d', $newRow['birth_date']) : '';
        $newRow['age']            = self::makeAge($newRow['birth_date']);
        $newRow['gender']         = CommonFn::get_val_if_isset($row, 'gender', 0);
        $newRow['gender_str']     = self::$gender_option[$newRow['gender']]['name'];
        $newRow['tags']           = CommonFn::get_val_if_isset($row, 'tags', []);

        $newRow['father_info']    = CommonFn::get_val_if_isset($row, 'father_info', []);
        $newRow['mother_info']    = CommonFn::get_val_if_isset($row, 'mother_info', []);

        $newRow['desc']           = CommonFn::get_val_if_isset($row, 'desc', '');
        $newRow['desc']  = str_replace("<br>","\n",$newRow['desc']);
        $newRow['vaccine_info']   = CommonFn::get_val_if_isset($row, 'vaccine_info', []);
        
        $newRow['count']       = CommonFn::get_val_if_isset($row, 'count', 1);
        $newRow['region']         = CommonFn::get_val_if_isset($row, 'region', []);
        $newRow['recommend_time'] = CommonFn::get_val_if_isset($row,'recommend_time',time());
        $newRow['reply_count'] = CommonFn::get_val_if_isset($row,'reply_count',0);
        $newRow['contact_count'] = CommonFn::get_val_if_isset($row,'contact_count',0);
        $newRow['all_reply_count'] = CommonFn::get_val_if_isset($row,'all_reply_count',0);
        $newRow['last_post_time'] = CommonFn::get_val_if_isset($row,'last_post_time',time());
        if($newRow['reply_count'] == 0){
            $newRow['last_post_time_str'] = '';
        }else{
            $newRow['last_post_time_str'] = CommonFn::sgmdate("Y年n月d日", $newRow['last_post_time'],1);
        }
        // 查看数统计
        $newRow['view_count'] = CommonFn::get_val_if_isset($row, 'view_count', 0);
        // 视频信息
        $newRow['video'] = CommonFn::get_val_if_isset($row, 'video', []);
        if (APPLICATION == 'api') {
            // 运费信息
            $newRow['carriage'] = CommonFn::get_val_if_isset($row, 'carriage', []);
            $newRow['carriage']['nonlocal'] = empty($newRow['carriage'])?0:CommonFn::get_val_if_isset($newRow['carriage'], 'nonlocal', 0);
            $newRow['carriage']['local'] = empty($newRow['carriage'])?0:CommonFn::get_val_if_isset($newRow['carriage'], 'local', 0);
            $newRow['carriage']['self'] = empty($newRow['carriage'])?0:CommonFn::get_val_if_isset($newRow['carriage'], 'self', 0);
        }

         $pet_type_str = '';
-        $pet_type_parent = '';
-        $kennel_str = '';
        // 品种信息
        $newRow['pet_type_info'] = [];
        // root_parent不存在时添加本字段
        if (CommonFn::isMongoId($row['pet_type'])) {
            $pet_type = PetTypes::get(new MongoId($row['pet_type']));
            if ($pet_type) {
                $newRow['pet_type_info'] = PetTypes::model()->parseRow($pet_type,array('id','name','parent','parent_name'));
                $pet_type_str = $pet_type->name;
-                $pet_type_parent = (string)$pet_type->parent;
                if(empty($newRow['root_parent'])){
                    $self_update = self::get($row['_id']);
                    $self_update->root_parent = $pet_type->parent;
                    $self_update->update(array('root_parent'),true);
                }
            }
        }
        // 商家信息
        $newRow['kennel_info'] = [];
        if (CommonFn::isMongoId($row['kennel'])) {
            $kennel = Kennel::get(new MongoId($row['kennel']));
            $kennel_str = $kennel->name;
            if ($kennel) {
                $newRow['kennel_info'] = Kennel::model()->parseRow($kennel->attributes,array('id','name','avatar','phone','average','type','region','comment_count','address'));
            }
        }

        $pics = CommonFn::get_val_if_isset($row, 'pics', []);
        if(count($pics)){
            $newRow['avatar'] = $pics[0];
        }

        // 疫苗信息
        foreach ($newRow['vaccine_info'] as $key => &$item) {
            if (isset($item['time'])&&$item['time']) {
                $item['time_str'] = date('Y-m-d', $item['time']);
            } else {
                //$item['time_str'] = '';
                unset($newRow['vaccine_info'][$key]);
            }
        }
        // 驱虫信息
        $newRow['deworming_info']   = CommonFn::get_val_if_isset($row, 'deworming_info', []);
        foreach ($newRow['deworming_info'] as $key => &$item) {
            if (isset($item['time'])&&$item['time']) {
                $item['time_str'] = date('Y-m-d', $item['time']);
            } else {
                //$item['time_str'] = '';
                unset($newRow['deworming_info'][$key]);
            }
        }
        
        // 最后一次未通过理由
        $newRow['last_deny_reason'] = CommonFn::get_val_if_isset($row, 'last_deny_reason', '');
        $newRow['recommend'] = CommonFn::get_val_if_isset($row, 'recommend', 0);
        // action-info
        if (APPLICATION == 'admin') {
            $newRow['action_user'] = CommonFn::get_val_if_isset($row, 'action_user', '');
            $newRow['action_time'] = CommonFn::get_val_if_isset($row, 'action_time', '');
            $newRow['action_log']  = CommonFn::get_val_if_isset($row, 'action_log', '');
        }

        if (APPLICATION == 'api') {
            unset($newRow['debug']);
            unset($newRow['last_deny_reason']);
            unset($newRow['last_post_time_str']);
            unset($newRow['recommend_time']);
            unset($newRow['last_modify']);
            unset($newRow['sort_weight']);
            unset($newRow['add_time']);
            unset($newRow['add_time_str']);
        }

        if (APPLICATION == 'admin') {
            $newRow['last_modify']    = CommonFn::get_val_if_isset($row, 'last_modify', 0);
            $newRow['add_time']       = CommonFn::get_val_if_isset($row, 'add_time', 0);
            $newRow['add_time_str']   = $newRow['add_time']? date('Y-m-d H:i', $newRow['add_time']) : '';
            $newRow['sort_weight']    = CommonFn::get_val_if_isset($row, 'sort_weight', 0);
            $newRow['from']       = CommonFn::get_val_if_isset($row, 'from', '');

            $newRow['kennel']         = (string)CommonFn::get_val_if_isset($row, 'kennel', '');
-           $newRow['pet_type']       = (string)CommonFn::get_val_if_isset($row, 'pet_type', '');
            $newRow['carriage'] = CommonFn::get_val_if_isset($row, 'carriage', []);
-            $newRow['carriage_nonlocal'] = CommonFn::get_val_if_isset($newRow['carriage'], 'nonlocal', 0);
-            $newRow['carriage_local'] = CommonFn::get_val_if_isset($newRow['carriage'], 'local', 0);
             $newRow['pet_type_str'] = $pet_type_str;
            $newRow['pet_type_parent'] = $pet_type_parent;
            $newRow['kennel_str'] = $kennel_str;
            $parents_info = [];
            // 父亲信息
            $father_info = CommonFn::get_val_if_isset($row, 'father_info', []);
            //$parents_info['father_name']            = CommonFn::get_val_if_isset($father_info, 'name', '');
            $parents_info['father_avatar']          = CommonFn::get_val_if_isset($father_info, 'avatar', '');
            $parents_info['father_breeds']          = CommonFn::get_val_if_isset($father_info, 'breeds', '');
            //$parents_info['father_shoulder_height'] = CommonFn::get_val_if_isset($father_info, 'shoulder_height', 0);
            //$parents_info['father_weight']          = CommonFn::get_val_if_isset($father_info, 'weight', 0);
            //$parents_info['father_hair_color']      = CommonFn::get_val_if_isset($father_info, 'hair_color', '');
            // 母亲信息
            $mother_info = CommonFn::get_val_if_isset($row, 'mother_info', []);
           // $parents_info['mother_name']            = CommonFn::get_val_if_isset($mother_info, 'name', '');
            $parents_info['mother_avatar']          = CommonFn::get_val_if_isset($mother_info, 'avatar', '');
            $parents_info['mother_breeds']          = CommonFn::get_val_if_isset($mother_info, 'breeds', '');
            //$parents_info['mother_shoulder_height'] = CommonFn::get_val_if_isset($mother_info, 'shoulder_height', 0);
            //$parents_info['mother_weight']          = CommonFn::get_val_if_isset($mother_info, 'weight', 0);
            //$parents_info['mother_hair_color']      = CommonFn::get_val_if_isset($mother_info, 'hair_color', '');

            $newRow['parents_info'] = $parents_info;

            $deworming_info = CommonFn::get_val_if_isset($row, 'deworming_info', []);
            // 驱虫信息

            $newRow['deworming_vivo']  = isset($deworming_info['time'])&&$deworming_info['time']? date('Y-m-d', $deworming_info['time']) : '';
            $newRow['deworming_brand'] = isset($deworming_info['brand'])? $deworming_info['brand'] : '';
        }
        return $this->output($newRow, $output);
    }

}
