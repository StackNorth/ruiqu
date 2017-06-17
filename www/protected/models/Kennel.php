<?php 
/**
 * 商家模型
 */
class Kennel extends MongoAr {
    public $_id;
    public $name;               // String : 商家名
    public $avatar;             // String : 商家头像，url
    public $status = 1;         // Int    : 状态
    public $join_time;          // Int    : 加入时间
    public $tags;               // Array  : 商家标签
    public $weight = 0;         // Int    : 排序权重
    public $desc;               // String : 详细说明文字
    public $desc_pics;          // Array  : 详情图片
    public $region;             // Object : 地区，{province: '', city: '', area: ''}
    public $address;            // String : 地址，参考ROrder模型address字段
    public $phone;              // String : 电话
    public $order_count = 0;        // Int    : 总订单数（包括取消、赔付的订单）
    public $finished_order = 0;     // Int    : 完成订单数
    public $average;            // Float  平均分
    public $favorable_rate;     // Float  : 好评率
    public $comment_count;      // Int    : 评价总数
    public $favorable_count;    // Int    : 好评数
    public $video;              // Object : 视频 {url:'',width:0,height:'',avatar:'',length:0}
    public $type=2;               // Int    : 商家类型，1认证商家，2普通商家
    // 审核相关
    public $business_license;   // 营业执照，{url: '', width: '', height: ''}
    public $certificate;        // 协会证书，{url: '', width: '', height: ''}
    // 账号相关
    public $apply_time;         // 申请时间
    public $account_type;       // 收款账号类型，参考ping++，alipay支付宝，wx微信支付，etc.
    public $account_name;       // 收款账号姓名，参考ping++，alipay支付宝，wx微信支付，etc.
    public $account;            // 账号
    public $have_change;        // 最近信息变更状态
    public $last_deny_reason;   // 最近一次审核未通过理由
    public $last_change_time;//最后一次修改时间
    public $from;//数据来源   wozhua xinchong chongwushichang

    public static $status_option = [
        0  => ['name' => '待审核', 'wx' => 0],
        1  => ['name' => '正常',   'wx' => 1],
        -1 => ['name' => '已删除', 'wx' => 0],
        -2 => ['name' => '审核未通过', 'wx' => 0],
    ];

    public static $from_option = [
        'wozhua' => ['name' => 'wozhua'],
        'xinchong' => ['name' => 'xinchong'],
        'shichang' => ['name' => 'shichang'],
    ];
    /**
     * 商家申请状态设置
     */
    public static $apply_status_option = [
        0    => ['name' => '未申请'],
        1    => ['name' => '正在申请'],
        2    => ['name' => '申请通过'],
        -1   => ['name' => '申请失败'],
        // status <= -2没有权限
        -2   => ['name' => '商家管理员资格正在审核'],
        -3   => ['name' => '管理员被取消授权'],
        -100 => ['name' => '商家已被删除'],
    ];

    /**
     * 商家标签，根据需要拓展或修改
     */
    public static $tag_option = [
        1 => ['name' => '30天保障'],
        2 => ['name' => '100%环境实拍'],
        3 => ['name' => '①宠①拍']
    ];

    public static $type_option = [
        0 => ['name' => '未知'],
        1 => ['name' => '认证商家'],
        2 => ['name' => '普通卖家'],
    ];

    public function __construct($scenario = 'insert') {
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public function getCollectionName() {
        return 'kennels';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
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
     * 根据微信Userid获取商家
     */
    public static function getByUserid($weixin_userid) {
        $criteria = new EMongoCriteria();
        $criteria->weixin_userid('==', $weixin_userid);
        $model = self::model()->find($criteria);
        return $model;
    }

    /**
     * 修改微信端状态
     */
    /*public function updateWeixinStatus($status) {
        $weixin_userid = $this->weixin_userid;
        $weixin_enable = self::$status_option[$status]['wx'];
        $user_data = [
            'userid' => $weixin_userid,
            'enable' => $weixin_enable,
        ];
        $option = WechatConfig::getIns()->getLinkOption();
        $secret = WechatConfig::getIns()->getSecret('admin_dev');
        $wechat = new QyWechat($option);
        $wechat->checkAuth($option['appid'], $secret);
        return $wechat->updateUser($user_data);
    }*/


    /**
     * MongoDB-Distinct获取商家内宠物类型列表
     * 过Redis，过期时间7200秒
     *
     * @return Array $list : 宠物类型列表，包含宠物类型信息
     * $list = [
     *     [
     *         'id'   => '...',
     *         'name' => '...',
     *         etc.
     *    ]
     * ];
     */
    public function getPetTypes() {
        $key = 'pet_types_'.(string)$this->_id;
        $cache = VariableRedis::get($key);
        if (!$cache) {
            $cache_data = [];
        } else {
            $cache_data = unserialize($cache);
        }

        if (!$cache_data || $cache_data['expire'] < time()) {
            $list = [];
        } else {
            $list = $cache_data['data'];
        }

        if (!$list) {
            $distinct_query = ['status' => 1, 'kennel' => $this->_id];
            $mongo = new MongoClient(DB_CONNETC);
            $db = $mongo->deal;
            $collection = $db->selectCollection('pets');
            $result = $collection->distinct('pet_type', $distinct_query);

            //$models = PetTypes::find()->where(['_id' => $result])->all();

            $criteria = new EMongoCriteria;
            $criteria->_id('in',$result);
            $cursor = PetTypes::model()->findAll($criteria);

            $list = [];
            foreach ($cursor as $key => $item) {
                $list[] = PetTypes::model()->parseRow($item->attributes);
            }

            $cache_data = ['data' => $list, 'expire' => time() + 7200];
            VariableRedis::set('pet_types_' . (string)$this->_id, serialize($cache_data));
        }

        return $list;
    }

    /**
     * 获取商家宠物列表
     * 过Redis，过期时间7200秒
     *
     * @param  Int   $limit : 显示数目
     *
     * @return Array $data  : 返回结果，包含宠物列表及商家宠物总数
     * $data = [
     *     'list' => [
     *         ['id' => '...', 'name' => '...', etc.],
     *     ],
     *     'count' => 100,
     * ];
     */
    public function getPetList($limit = 3) {
        $key = 'pet_list_' . (string)$this->_id;

        $cache = VariableRedis::get($key);
        if (!$cache) {
            $cache_data = [];
        } else {
            $cache_data = unserialize($cache);
        }

        if (!$cache_data || $cache_data['expire'] < time()) {
            $criteria = new EMongoCriteria();
            $criteria->status('==',1);
            $criteria->kennel('==',$this->_id);
            $criteria->sort('sort_weight',EMongoCriteria::SORT_DESC);
            $criteria->sort('view',EMongoCriteria::SORT_DESC);
            $criteria->limit($limit);
            $models = DealPet::model()->findAll($criteria);
            $count = $models->count();

            $list = [];
            foreach ($models as $item) {
                $list[] = DealPet::model()->parseRow($item->attributes,array('id', 'name', 'region', 'price','avatar'));
            }

            $data = ['list' => $list, 'count' => $count];
            $cache_data = ['data' => $data, 'expire' => time() + 7200];
            VariableRedis::set($key, serialize($cache_data), 0);
        } else {
            $data = $cache_data['data'];
        }

        return $data;
    }



    /**
     * 获取该商家的一条评价（根据权重排序）
     * 优先从缓存中获取数据
     */
    public function getCommentOne() {
        $key = 'kennel_comment_one_'.(string)$this->_id;
        $cache = VariableRedis::get($key);
        if (ENVIRONMENT != 'product') {
            $cache = '';
        }
        if (!$cache) {
            $cache_data = [];
        } else {
            $cache_data = unserialize($cache);
        }

        if (!$cache_data) {
            $criteria = new EMongoCriteria();
            $criteria->kennel('==',$this->_id);
            $criteria->status('==',1);
            $criteria->sort('weight',EMongoCriteria::SORT_DESC);
            $cursor = DealComment::model()->findAll($criteria);
            if (!$cursor->count()) {
                return (object)[];
            }

            foreach(CommonFn::getRowsFromCursor($cursor) as $value){
                $comment = $value;break;
            }
            $data = DealComment::model()->parseRow($cursor->attributes,array('user_info', 'pet_info', 'time_str'));
            VariableRedis::set($key, serialize($data), 7200);
            return $data;
        } else {
            return $cache_data;
        }
    }

    public function parseRow($row, $output = []) {
        $newRow = [];
        $newRow['id']                = (string)$row['_id'];
        $newRow['name']              = CommonFn::get_val_if_isset($row, 'name', '');
        $newRow['avatar']            = CommonFn::get_val_if_isset($row, 'avatar', Yii::app()->params['defaultImage']);
        $newRow['status']            = CommonFn::get_val_if_isset($row, 'status', 0);
        $newRow['status_str']        = self::$status_option[$newRow['status']]['name'];
        $newRow['join_time']         = CommonFn::get_val_if_isset($row, 'join_time', 0);
        $newRow['join_time_str']     = $newRow['join_time'] != 0 ? date('Y-m-d', $newRow['join_time']) : '';
        $newRow['tags']              = CommonFn::get_val_if_isset($row, 'tags', []);
        $newRow['weight']            = CommonFn::get_val_if_isset($row, 'weight', 0);
        $newRow['desc']              = CommonFn::get_val_if_isset($row, 'desc', '');
        $newRow['desc']  = str_replace("<br>","\n",$newRow['desc']);
        $newRow['desc_pics']         = CommonFn::get_val_if_isset($row, 'desc_pics', []);

        $newRow['region']            = CommonFn::get_val_if_isset($row, 'region', []);
        $newRow['address']           = CommonFn::get_val_if_isset($row, 'address', '');
        $newRow['phone']             = CommonFn::get_val_if_isset($row, 'phone', '');
        $newRow['order_count']       = CommonFn::get_val_if_isset($row, 'order_count', 0);
        $newRow['finished_order']    = CommonFn::get_val_if_isset($row, 'finished_order', 0);
        $newRow['favorable_rate']    = CommonFn::get_val_if_isset($row, 'favorable_rate', 0.0);
        $newRow['comment_count']     = CommonFn::get_val_if_isset($row, 'comment_count', 0);
        $newRow['favorable_count']   = CommonFn::get_val_if_isset($row, 'favorable_count', 0);

        $newRow['video']             = CommonFn::get_val_if_isset($row, 'video', []);
        $newRow['type']              = CommonFn::get_val_if_isset($row, 'type', 2);

        // 审核相关
        $newRow['business_license']  = CommonFn::get_val_if_isset($row, 'business_license', []);
        $newRow['certificate']       = CommonFn::get_val_if_isset($row, 'certificate', []);

        // 账号
        $newRow['account_type']      = CommonFn::get_val_if_isset($row, 'account_type', '');
        $newRow['account_name']      = CommonFn::get_val_if_isset($row, 'account_name', '');
        $newRow['account']           = CommonFn::get_val_if_isset($row, 'account', '');

        $newRow['last_deny_reason']           = CommonFn::get_val_if_isset($row, 'last_deny_reason', '');
        $newRow['have_change']           = CommonFn::get_val_if_isset($row, 'have_change', 0);
        $newRow['pet_types']           = $this->getPetTypes();

        if($newRow['favorable_rate']>0){
            $newRow['average'] = round($newRow['favorable_rate'] * 5);
        }else{
            $newRow['average'] = 5;
        }

        $cache = new ARedisCache();
        $key = 'pets_count_'.$newRow['id'];
        $count_cache = $cache->get($key);
        $_count=0;
        if($count_cache){
            $_count = $count_cache;
        }else{
            $criteria = new EMongoCriteria;
            $criteria->status('in',array(1, 2,));
            $criteria->kennel('==',$row['_id']);
            $cursor = DealPet::model()->findAll($criteria);
            $_count = $cursor->count();

            $cache->set($key,$_count,86400);
        }
        $newRow['pets_count'] =  $_count;

        $manager = KennelManager::getByKennel($row['_id']);

        $manager_user_id = '';
        if ($manager) {
            $manager_user_id = $newRow['user_id'];
        }else{
            $manager_user_id = Yii::app()->params['kefu_user'];
        }
        if(APPLICATION=='admin'){
            $newRow['from']       = CommonFn::get_val_if_isset($row, 'from', '');
            if ($manager) {
                $newRow['id_card'] = $manager->id_card? $manager->id_card : ['url' => '', 'width' => 0, 'height' => 0];
                $newRow['id_card_inhand'] = $manager->id_card_inhand? $manager->id_card_inhand : ['url' => '', 'width' => 0, 'height' => 0];
                $newRow['user_id'] = (string)$manager->user;
            } else {
                $newRow['id_card'] = ['url' => '', 'width' => 0, 'height' => 0];
                $newRow['id_card_inhand'] = ['url' => '', 'width' => 0, 'height' => 0];
                $newRow['user_id'] = '';
            }
        }
        $newRow['manager_user_id'] =  $manager_user_id;
        return $this->output($newRow, $output);
    }
}
