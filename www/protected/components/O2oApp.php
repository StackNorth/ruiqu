<?php 
/**
 * 微信企业号o2o应用拓展
 * @author   2015-12-02
 */
class O2oApp {

    private $user;          // 后台用户ID
    private $userid;        // 微信管理端UserId
    private $username;      // 后台用户姓名

    /**
     * 微信跳转URI验证
     */
    public static function checkURI($agentid) {
        $state = isset($_GET['state']) ? $_GET['state'] : '';
        $data = array();
        if ($state != '5e2b4706179f774e94903e1213d2222e' || !isset($_GET['code'])) {
            $data['success'] = false;
            $data['msg']     = '验证失败，请重试';
            $data['userid']  = '';
            $data['wechat']  = array();
            return $data;
        }

        $wechat = self::getWechatObj();
        $code = $_GET['code'];

        $user = $wechat->getUserId($code, $agentid);
        $userid = isset($user['UserId']) ? $user['UserId'] : '';
        if (empty($userid)) {
            $data['success'] = false;
            $data['msg']     = '无法获取您的用户信息，请关闭页面后重试';
            $data['userid']  = '';
            $data['wechat']  = $wechat;
            return $data;
        }

        $data['success'] = true;
        $data['msg']     = 'success';
        $data['userid']  = $userid;
        $data['wechat']  = $wechat;
        return $data;
    }

    /**
     * 获取微信SDK的实例化，并设置必要的参数
     * @param  Array  $option : 企业号回调模式配置参数
     * @param  String $secret : 企业号管理组secret_key
     * @return Object $wechat : 基本设置后的Wechat对象，并已获得access_key
     */
    public static function getWechatObj($option=array(), $secret='') {
        if (empty($option)) {
            $option = WechatConfig::getLinkOption();
        }
        if ($secret == '') {
            $secret = WechatConfig::getSecret('admin_dev');
        }

        $wechat = new QyWechat($option);
        $echostr = $wechat->valid(true);
        $wechat->checkAuth($option['appid'], $secret);

        return $wechat;
    }

    /**
     * 获取微信SDK实例化，但是不经过valide()
     * @param  Array  $option : 企业号回调模式配置参数
     * @param  String $secret : 企业号管理组secret_key
     * @return Object $wechat : 基本设置后的Wechat对象，并已获得access_key
     */
    public static function getWechatActive($option=array(), $secret='') {
        if (empty($option)) {
            $option = WechatConfig::getLinkOption();
        }
        if ($secret == '') {
            $secret = WechatConfig::getSecret('admin_dev');
        }

        $wechat = new QyWechat($option);
        $wechat->checkAuth($option['appid'], $secret);

        return $wechat;
    }

    /**
     * 获取时间列表
     * 返回最近六个月的时间列表
     * @param  Int     $number : 需要的时间列表itam数量，默认为6，最大为12
     * @return Array   $data
     * array(
     *     0 => array(
     *         'text'  => '2015年12月',   // 前端显示的文本
     *         'value' => '2015-12',      // select中option的值
     *         'start' => 1448899200,     // 该月开始的时间戳
     *         'end'   => 1452232157,     // 该月结束的时间戳
     *     ),
     * );
     */
    public static function getTimeList($number=6) {
        $number = $number > 12 ? 12 : $number;

        // 2015年的订单不可查看
        if (intval(date('Ym')) < 201606 && intval(date('Y')) == 2016) {
            $number = intval(date('m'));
        }

        $time = strtotime(date('Y-m-01', time()));
        $data = array();

        for ($index=0; $index < $number; $index++) {
            $text   = date('Y年m月', $time);
            $value  = date('Y-m', $time);
            $start  = strtotime($value);
            $end    = strtotime('+1 month', $start);
            $data[] = array(
                'text'  => $text,
                'value' => $value,
                'start' => $start,
                'end'   => $end,
            );

            $time = strtotime('-1 month', $time);
        }
        // echo json_encode($data);die;

        $data[0]['text'] = '本月';

        return $data;
    }

    /**
     * 将数据返回给前端
     * @param  Boolean $success : 返回是否成功数据
     * @param  String  $msg     : 若返回flag为false则填写该参数
     * @param  Array   $content : 需要传递给前端的数据
     */
    public static function response($success, $msg='', $content=array()) {
        $data = array(
            'success' => $success,
            'msg'     => $msg,
            'content' => $content
        );
        echo json_encode($data);exit();
    }

    /**
     * 构造函数
     * @param String $userid : 用户UserId
     */
    public function __construct($userid='') {
        $this->userid = $userid;
        $user = TechInfo::getByUserId($userid);
        if (!empty($user)) {
            $this->user = $user->_id;
            $this->username = $user->name;
        } else {
            $this->user = -1;
            $this->username = '';
        }
    }

    /**
     * 获取时间范围内总提成以及提成列表
     * @param  NumberInt $start : 查询条件，开始时间
     * @param  NumberInt $end   : 查询条件，结束时间，默认为当前时间
     * @param  Boolean   $page  : 是否应用分页设置
     * @return Array     $data  : 返回的结果，包括总提成数、总订单数、主订单数、附加订单数
     */
    public function getCommision($start, $end = 0, $page = false) {
        $end = $end == 0 ? time() : $end;

        if ($page) {
            $params = CommonFn::getPageParams();
            $criteria = new EMongoCriteria($params);
        } else {
            $criteria = new EMongoCriteria();
        }


        $criteria_user = new EMongoCriteria();
        $criteria_user->_id('==',intval($this->user));
        $techinfo = TechInfo::model()->find($criteria_user);
        $criteria->user('==', $techinfo->name);
        $criteria->booking_time('>=', intval($start));
        $criteria->booking_time('<', intval($end));
        $cursor = Commision::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);

        $count  = $cursor->count();

        $sum    = 0.0;  // 总提成数
        $order  = 0;    // 主订单数
        $append = 0;    // 附加订单数

        // group生成统计数据
        $mongo = new MongoClient(DB_CONNETC);
        $db = $mongo->fuwu;
        $collection = $db->selectCollection('commision');
        $keys = array('user' => 1);
        $initial = array('sum' => 0.0, 'order' => 0, 'append' => 0);
        $reduce  = 'function (obj, prev) {';
        $reduce .= 'prev.sum += obj.commision;';
        $reduce .= 'if(obj.type == 0){prev.order++};';
        $reduce .= 'if(obj.type == 1){prev.append++};';
        $reduce .= '}';
        $condition = array(
            'condition' => array(
                'booking_time' => array(
                    '$gte' => intval($start),
                    '$lt'  => intval($end)
                ),
                'user' => array(
                    '$eq'  => $this->user
                ),
        ));
        $g = $collection->group($keys, $initial, $reduce, $condition);
        $group_data = $g['retval'];
        if (!empty($group_data)) {
            $group_data = $group_data[0];
            $sum    = $group_data['sum'];
            $order  = $group_data['order'];
            $append = $group_data['append'];
        }

        $parsedRows = Commision::model()->parse($rows);
       foreach ($parsedRows as $data) {
           $sum += floatval($data['commision']);
       }
        $data = array(
            'userid' => $this->userid, // 用户企业号内ID
            'sum'    => $sum,          // 总提成数
            'list'   => $parsedRows,   // 提成列表
            'count'  => $count,        // 总订单数
            'append' => $append,       // 追加订单数
            'order'  => $order,        // 普通订单数
        );
        return $data;
    }

    /**
     * 获取时间范围内评价统计及评价列表
     * @param  NumberInt $start : 查询条件，开始时间
     * @param  NumberInt $end   : 查询条件，结束时间，默认为当前时间
     * @param  Boolean   $page  : 是否分页
     * @return Array     $data  : 返回结果，包括总评价数、评价列表
     */
    public function getComment($start, $end=0, $page=false) {
        $end = $end == 0 ? time() : $end;

        if ($page) {
            $params = CommonFn::getPageParams();
            $criteria = new EMongoCriteria($params);
        } else {
            $criteria = new EMongoCriteria();
        }
        //$criteria->technician('==', intval($this->user));
        $criteria->time('>=', intval($start));
        $criteria->time('<', intval($end));
        $cursor = Comment::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $rows_new = array();
        foreach($rows as $key => $value){
            //判断是否为当前保洁师订单
            foreach($value['technicians'] as $v) {
                if($v['technician_id'] == intval($this->user)){
                    $rows_new[] = $rows[$key];
                }
            }
        }
        $rows = $rows_new;
        $count = count($rows);
        //$count = $cursor->count();
        $parsedRows = Comment::model()->parse($rows);

        $data = array(
            'count'  => $count,
            'list'   => $parsedRows
        );

        return $data;
    }

    public function getOrder($start, $end=0, $page=false) {
        $end = $end == 0 ? time() : $end;

        if ($page) {
            $params = CommonFn::getPageParams();
            $criteria = new EMongoCriteria($params);
        } else {
            $criteria = new EMongoCriteria();
        }


        $criteria->addCond('technicians.technician_id','==',intval($this->user));
        $criteria->booking_time('>=', intval($start));
        $criteria->booking_time('<', intval($end));
        $cursor = ROrder::model()->findAll($criteria);


        $rows = CommonFn::getRowsFromCursor($cursor);

        $rows_new = array();
        foreach($rows as $key => $value){
            //判断是否为当前保洁师订单
            foreach($value['technicians'] as $v) {
                if($v['technician_id'] == intval($this->user)){
                    $rows_new[] = $rows[$key];
                }
            }
        }

        $rows = $rows_new;
        $count = count($rows);
        $parsedRows = ROrder::model()->parse($rows);

        $data = array(
            'count' => $count,
            'list'  => $parsedRows
        );

        return $data;
    }

    /**
     * 获取user的值
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * 获取username
     */
    public function getUsername() {
        return $this->username;
    }

}