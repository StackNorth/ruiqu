<?php
/**
 * User: charlie
 * O2O 订单表
 */
class ROrder extends MongoAr
{
    public $_id;

    // public $pay_id;//由ping++生成的支付单号
    //public $pay_type;//用户的支付方式

    public $charge_id;//ping++的chargeId,charge_id即为支付单号
    public $pay_channel;//支付渠道
    public $channel;//订单来源渠道
    public $precedence = 0;//加急 默认0
    public $booking_time; //用户预约的时间
    public $order_time;  //订单生成时间
    public $deal_time;  //订单处理时间
    public $take_time;  //接单时间
    public $set_out_time;  //出发时间
    public $arrive_time;  //到达时间
    public $finish_time;  //完成时间
    public $cancel_time;  //订单取消时间
    public $apply_refund_time;  //订单申请退款时间
    public $refund_time;  //订单退款时间
    public $append_orders = array();
    public $products=array(); //订单包含的商品数组   数据库设计  支持多个产品在一个订单
    public $station = '';//服务网点,station的mongoid
    public $price;  //订单金额
    public $OrderNo;//JingBai订单id
    public $final_price;  //订单使用代金券之后的金额  如果没有使用代金券   $final_price=$price
    public $pay_price;//订单最终支付的金额  price-coupons-用户要支付的余额

    public $signUrl;//签字图片链接 上传到七牛
    public $coupons=array();//订单使用的代金券  数据库设计可以支持多张
    
    public $coupon_type;


    public $user;  //对应的RUser 的mongoid
    public $status=0;//订单状态 0=>待支付  1=>已支付  2=>已处理  3=>已接单  4=>已出发  5=>已上门 6=>已完成 -1=>已取消 -2=>已退款

    public $memo = '';//用户备注
    public $remark = '';//后台备注

    public $type = 0;//订单类型

    public $counts=1;//预订的数量

    public $have_comment = 0; //是否已评价

    public $technicians = array(); // 接单保洁师
    //public $technician = ''; // 接单保洁师,user的id
    //public $technician_name = ''; // 接单保洁师姓名

    public $address = array(); //地址信息   包含   province  city  area position detail   name  mobile position


    public static $order_filter = array(
        0 => array('name' => '未选择'),
        1 => array('name' => '来源'),
        2 => array('name' => '服务'),
        3 => array('name' => '状态')
    );

    public static $status_option = array(
        0 => array('name' => '待支付'),
        1 => array('name' => '已支付'),
        2 => array('name' => '已处理'),
        3 => array('name' => '已接单'),
        4 => array('name' => '已出发'),
        5 => array('name' => '已上门'),
        6 => array('name' => '已完成'),
        7 => array('name' => '退款中'),
        -1 => array('name' => '已取消'),
        -2 => array('name' => '已退款'),
        -3 => array('name' => '申请退款'),
    );


    public static $channel_option = array(
        "wx_pub" => array('name' => '微信公众号'),
        "dongfang" => array('name' => '东方CJ'),
        "youzan" => array('name' => '有赞'),
        "shihui" => array("name" => '实惠'),
        "shangmenshoukuan" => array('name' => '保洁师上门收款'),
        "balance"=>array('name' => '余额支付'),
        "mix"=>array('name' => '混合支付'),  //部分余额支付   部分付款
        "other" => array('name' => '其他'),

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
        return 'orders';
    }

    public function parseRow($row,$output=array()){
        $newRow = array();
        $newRow['id'] = (string)$row['_id'];

        // $newRow['pay_id'] = CommonFn::get_val_if_isset($row,'pay_id','');
        $newRow['pay_channel'] = CommonFn::get_val_if_isset($row,'pay_channel','');
        $newRow['charge_id'] = CommonFn::get_val_if_isset($row,'charge_id','');
        $newRow['channel'] = CommonFn::get_val_if_isset($row,'channel','wx_pub');
        $newRow['type'] = CommonFn::get_val_if_isset($row,'type','');

        $newRow['counts'] = CommonFn::get_val_if_isset($row,'counts',1);
        $newRow['booking_time'] = CommonFn::get_val_if_isset($row,'booking_time',0);
        $newRow['order_time'] = CommonFn::get_val_if_isset($row,'order_time',0);
        $newRow['have_comment'] = CommonFn::get_val_if_isset($row,'have_comment',0);
        $newRow['order_num'] = $newRow['order_time'].hexdec(substr((string)$row['user'],-3));
        $newRow['deal_time'] = CommonFn::get_val_if_isset($row,'deal_time',0);
        $newRow['take_time'] = CommonFn::get_val_if_isset($row,'take_time',0);
        $newRow['set_out_time'] = CommonFn::get_val_if_isset($row,'set_out_time',0);
        $newRow['arrive_time'] = CommonFn::get_val_if_isset($row,'arrive_time',0);
        $newRow['finish_time'] = CommonFn::get_val_if_isset($row,'finish_time',0);
        $newRow['cancel_time'] = CommonFn::get_val_if_isset($row,'cancel_time',0);
        $newRow['apply_refund_time'] = CommonFn::get_val_if_isset($row,'apply_refund_time',0);
        $newRow['apply_refund_time_str'] = $newRow['apply_refund_time']?date('Y年n月d日 H:i',$newRow['apply_refund_time']):'';
        $newRow['refund_time'] = CommonFn::get_val_if_isset($row,'refund_time',0);
        $newRow['booking_time_str'] = date('Y年n月d日 H:i',$newRow['booking_time']);
        $newRow['order_time_str'] =   date("n月d日 H:i", $newRow['order_time']);
        $newRow['deal_time_str'] = $newRow['deal_time']?CommonFn::sgmdate("Y年n月d日:H:i:s", $newRow['deal_time'],1):'';
        $newRow['take_time_str'] = $newRow['take_time']?CommonFn::sgmdate("Y年n月d日:H:i:s", $newRow['take_time'],1):'';
        $newRow['set_out_time_str'] = $newRow['set_out_time']?CommonFn::sgmdate("Y年n月d日:H:i:s", $newRow['set_out_time'],1):'';
        $newRow['arrive_time_str'] = $newRow['arrive_time']?CommonFn::sgmdate("Y年n月d日:H:i:s", $newRow['arrive_time'],1):'';
        $newRow['finish_time_str'] = $newRow['finish_time']?CommonFn::sgmdate("Y年n月d日:H:i:s", $newRow['finish_time'],1):'';
        $newRow['cancel_time_str'] = $newRow['cancel_time']?CommonFn::sgmdate("Y年n月d日:H:i:s", $newRow['cancel_time'],1):'';
        $newRow['refund_time_str'] = $newRow['refund_time']?CommonFn::sgmdate("Y年n月d日:H:i:s", $newRow['refund_time'],1):'';

        $newRow['signUrl'] = CommonFn::get_val_if_isset($row,'signUrl','');
        $newRow['sum_price'] = 0;
        $newRow['price'] = floatval(CommonFn::get_val_if_isset($row,'price',0));

        $newRow['af_sum_price'] = $newRow['price'];
        $newRow['final_price'] = floatval(CommonFn::get_val_if_isset($row,'final_price',0));
        $newRow['pay_price'] = floatval(CommonFn::get_val_if_isset($row,'pay_price',$newRow['final_price']));
        $newRow['sum_price'] = $newRow['final_price'];
        $newRow['memo'] = CommonFn::get_val_if_isset($row,'memo','');
        $newRow['remark'] = CommonFn::get_val_if_isset($row,'remark','');
        $newRow['technicians'] = CommonFn::get_val_if_isset($row,'technicians','');
        $user = array();
        $t_user = new ZUser();
        if(isset($row['user']) && $row['user']){
            $_user = $t_user->get($row['user']);
            $user = RUser::model()->parseRow($_user->attributes,array('user_name','id','avatar'));
            $user['otherPlatform'] = 0;
        }else{
            $user_name = self::$channel_option[$newRow['channel']]['name'].'用户';
            $user = array('user_name'=>$user_name,'id'=>'','avatar'=>Yii::app()->params['defaultUserAvatar'], 'otherPlatform' => 1);
        }
        $newRow['user'] = $user;


        $products = array();
        // $t_component = new ZComponent();
        $newRow['products_str'] = '';
        if(isset($row['products'])&&is_array($row['products'])){
            // $_products = $t_component->getList(Product::model(),$row['products']);
            foreach ($row['products'] as $key => $product) {
                $product_obj = Product::get($product['product']);

                $temp_info = $product_obj->parseRow($product_obj);
                //echo(json_encode($temp_info));exit;
                $temp_info['count'] = $product['count'];
                //$temp_info['extra'] = $product['extra'];
                $products[] = array('product'=>$temp_info,'extra'=>$product['extra']);
                //$temp_info['extra'] = $product['extra'];
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
        $newRow['station'] = (object)array();
        if(isset($row['station'])){
            $staion = Station::get($row['station']);
            $staion = Station::model()->parseRow($staion,array('id','name'));

            $newRow['station'] = $staion;
            $newRow['station_id'] = $staion['id'];
            $newRow['station_name'] = $staion['name'];
        }


        foreach( $newRow['technicians'] as $technicians) {
            $newRow['technician'] = $technicians;
            if ($newRow['technician']) {
                $tech_info = TechInfo::get($newRow['technician']);
                if ($tech_info) {
                    $newRow['tech_info'][] = TechInfo::model()->parseRow($tech_info, array('id', 'name', 'mobile', 'weixin_userid'));
                } else {
                    $newRow['tech_info'][] = [];
                }
            } else {
                $newRow['tech_info'][] = [];
            }
        }
        //$newRow['technician_name'] = isset($newRow['tech_info']['name']) ? $newRow['tech_info']['name'] : '';

       // $newRow['hasSendTec'] = $newRow['technicians'] == 0 ? 0:1;
        foreach($newRow['technicians'] as $technician) {
            if ($technician == 0) {
                $newRow['hasSendTec'] = 0;
            } else {
                $newRow['hasSendTec'] = 1;
            }
        }
        $coupons = array();
        if(isset($row['coupons'])&&is_array($row['coupons'])){
            foreach ($row['coupons'] as $coupon) {
                $user_coupon_obj = UserCoupon::get($coupon);
                if(!$user_coupon_obj){
                    continue;
                }
                $coupons[] = $user_coupon_obj->parseRow($user_coupon_obj);
            }
        }
        $newRow['coupons'] = $coupons;
        if(!isset($newRow['coupons'])||empty($newRow['coupons'])){
            $newRow['coupons']=CommonFn::$empty;
        }


        if(!isset($newRow['coupons'])||empty($newRow['coupons'])){
            $newRow['coupons']=CommonFn::$empty;
        }


        $newRow['address'] = CommonFn::get_val_if_isset($row,'address',array("province"=>"","city"=>"","area"=>"","address_info"=>"","name"=>"","mobile"=>"","position"=>array(121,31)));
        if(!isset($newRow['address']['province'])){
            $newRow['address']['province'] = '';
        }
        if(!isset($newRow['address']['city'])){
            $newRow['address']['city'] = '';
        }
        if(!isset($newRow['address']['area'])){
            $newRow['address']['area'] = '';
        }
        if(!isset($newRow['address']['detail'])){
            $newRow['address']['detail'] = '';
        }
        if(!isset($newRow['address']['name'])){
            $newRow['address']['name'] = '';
        }
        if(!isset($newRow['address']['mobile'])){
            $newRow['address']['mobile'] = '';
        }
        if(!isset($newRow['address']['position'])){
            $newRow['address']['position'] = array(121,31);
        }
        if(!isset($newRow['address']['poi']) || !isset($newRow['address']['poi']['name'])){
            $newRow['address']['poi'] = array('name'=>'','uid'=>'');
        }
//echo(ROrder::$status_option[$newRow['status']]);exit;
        $newRow['status'] = CommonFn::get_val_if_isset($row,'status',1);
        $newRow['status_str'] = ROrder::$status_option[$newRow['status']]['name'];
        $newRow['book_status'] = '';
        if($newRow['status'] == 6){
            $newRow['book_status'] = 3;
            $newRow['book_status_str'] = '已完成';
        }elseif ($newRow['status'] == -1) {
            $newRow['book_status'] = 2;
            $newRow['book_status_str'] = '已取消';
        }else{
            $newRow['book_status'] = 1;
            $newRow['book_status_str'] = '预约中';
        }
        $newRow['action_user'] = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time'] = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log'] = CommonFn::get_val_if_isset($row,'action_log',"");

        // 订单评分处理
        if (intval($row['have_comment']) == 1) {
            $criteria = new EMongoCriteria();
            $criteria->order = $row['_id'];
            $comment = Comment::model()->find($criteria);
            $newRow['score'] = $comment->score;
            $newRow['commentId'] = (string)$comment->_id;
        } else {
            $newRow['score'] = 100;
            $newRow['commentId'] = '';
        }

        if(APPLICATION=='admin'){
            if($newRow['address']['mobile']){
                $mongo = new MongoClient(DB_CONNETC);

                $where = array();

                $where['address.mobile'] = $newRow['address']['mobile'];
                $where['status'] = array('$gt'=>0);

                $newRow['order_count'] = $mongo->fuwu->orders->count($where);
            }else{
                $newRow['order_count'] = 0;
            }

        }

        if(APPLICATION=='api'){
            unset($newRow['charge_id']);

            unset($newRow['action_user']);
            unset($newRow['action_time']);
            unset($newRow['action_log']);
        }
        return $this->output($newRow,$output);
    }

    /**
     * ROrder保存后的回调函数
     */
    protected function afterSave() {
        parent::afterSave();


        // 生成保洁师提成并保存
        if ($this->status == 6 && !empty($this->technicians)) {
            // 订单时间检查
            // 预定时间是否在12月之后
            $time = time();
            if ($this->booking_time >= strtotime('2015-11-01')) {
                // 若完成时间与预定时间相差大于7天，则完成时间为预定时间+3天
                if ($this->booking_time + 604800 < $time) {
                    $time = $this->booking_time + 259200;
                }
            } else {
                return true;
            }

            $order = $this->_id;
            $commisionObj = Commision::getByOrder($order);

            // 检查Cmmmision信息是否已录入
           foreach ($this->technicians as $key => $value) {
               $technician_id = $value['technician_id'];
               $technician = $value['technician_name'];
               $tech = TechInfo::get($technician_id);
               if ($commisionObj == false) {
                   // 普通订单生成提成并保存
                   $commisionObj = new Commision();
                   $commisionObj->time = empty($this->finish_time) ? $time : $this->finish_time;
                   $commisionObj->booking_time = $this->booking_time;
                   $commisionObj->user = $technician;
                   $commisionObj->order = $this->_id;
                   $commisionObj->commision = Commision::getCommision($this, Commision::MAIN, $tech->scheme);
                   $commisionObj->type = Commision::MAIN;
                   $commisionObj->insert();

                   // 订单内附加订单生成提成并保存
                   $appends = $this->append_orders;
                   if (!empty($appends)) {
                       $criteria = new EMongoCriteria();
                       $criteria->_id('in', $appends);
                       $appendOrders = AppendOrder::model()->findAll($criteria);
                       foreach ($appendOrders as $key => $row) {
                           if ($row->status != 1) {
                               continue;
                           }
                           $commisionObj = new Commision();
                           $commisionObj->time = empty($this->finish_time) ? $time : $this->finish_time;
                           $commisionObj->booking_time = $this->booking_time;
                           $commisionObj->user = $technician;
                           $commisionObj->order = $row->_id;
                           $commisionObj->commision = Commision::getCommision($row, Commision::APPEND, $tech->scheme);
                           $commisionObj->type = Commision::APPEND;
                           $commisionObj->insert();
                       }
                   }
               }


               // 提示保洁师订单已完成
               if ($tech && $tech->weixin_userid) {
                   // 检查订单评价是否存在，若存在则不发送
                   $comment = Comment::getByOrder($this->_id);
                   if (!$comment) {
                       $wechat = O2oApp::getWechatActive();
                       $url_prefix = ENVIRONMENT == 'product' ? 'http:// api.yiguanjia.me' : 'http:// apitest.yiguanjia.me';
                       $wechat_data = [
                           'touser' => $tech->weixin_userid,
                           'msgtype' => 'news',
                           'agentid' => '24',
                           'news' => [
                               'articles' => [
                                   [
                                       'title' => '壹橙管家提示-订单已完成',
                                       'description' => $tech->name . '你好！预定时间在' . date('m月d日H:i', $this->booking_time) . '的订单已完成，请点击查看订单情况。',
                                       'url' => $url_prefix . '/index.php?r=o2o/myCommision/info&order=' . (string)$order . '&user=' . $technician . '&type=0',
                                   ],
                               ],
                           ],
                       ];
                       $wechat->sendMessage($wechat_data);
                   }
               }
           }
       }

    }
    //取消订单
    public function delOrder($orderId){
        $data['key']         = Yii::app()->params['shKey'];
        $data['version']     = '1.0';
        $data['serviceType'] = (int)26;
        $data['orderId']     = $orderId;
        echo self::curl_post($data);
    }

    //更新订单状态
    public function actionUpdateStatus($orderId,$status){
        $data['key']         = Yii::app()->params['shKey'];
        $data['version']     = '1.0';
        $data['serviceType'] = (int)26;
        $data['orderId']     = $orderId;
        $data['status']      = $status;
        echo self::curl_post($data);

    }
    //查询订单状态
    /*public function actionQueryOrder(){
        $data['key']         = Yii::app()->params['shKey'];
        $data['version']     = '1.0';
        $data['serviceType'] = (int)26;
        $data['orderId']     = '57fde8059f5160c4048b4aeb';
        $data['status']      = '-1';
        ksort($data);
        $str = '';
        $dat = '';
        foreach ($data as $key => $value) {
            $str .= $key.'='.$value.'&';
            $dat .= $key.'='.urlencode($value).'&';
        }
        $str = rtrim(trim($str),'&');
        $str .= $str.Yii::app()->params['shMd5Key'];

        $data['sign'] = strtoupper(md5($str));
        $dat .= 'sign='.urlencode($data['sign']);//构建post 参数
        $url = 'http://test2.app.hiwemeet.com/v2/openpf/home/order/thirdOrder/detail';  //调用接口的平台服务地址

        $url .= '?'.$dat;
        $result = CommonFn::simple_http($url);
        echo $result;

    }*/

    public function curl_post($data){
        ksort($data);
        $str = '';
        $dat = '';
        foreach ($data as $key => $value) {
            $str .= $key.'='.$value.'&';
            $dat .= $key.'='.urlencode($value).'&';
        }
        $str = rtrim(trim($str),'&');
        $str .= $str.Yii::app()->params['shMd5Key'];

        $data['sign'] = strtoupper(md5($str));
        $dat .= 'sign='.urlencode($data['sign']);//构建post 参数
        $url = 'http://test2.app.hiwemeet.com/v2/openpf/home/order/thirdOrder/cancel';  //调用接口的平台服务地址

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dat);
        $result = curl_exec($ch);
        curl_close($ch);
        return  $result;
    }

}