<?php 
/**
 * O2oAccess.php
 *
 * o2o平台接入抽象类
 *
 * @author     2015-11-04
 */
abstract class O2oAccess {

    private static $_instances = array();   // 单例保存子类的实例化

    protected $request    = array();        // 对方服务器的请求参数
    protected $params     = array();        // 请求对方服务器时的参数数组
    protected $url        = '';             // 请求对方服务器时的接地址
    protected $access_key = '';             // 对方提供的access_key
    protected $secret_key = '';             // 对方提供的secret_key

    const CURL_GET  = -1;
    const CURL_POST = 1;

    /**
     * 强制要求子类实现的方法，命名参考目前已接入平台
     * 
     * ------ 签名及加密部分 ------
     * method checkSignature 检查签名
     * method makeSignature  生成签名
     * method makeParams     加密并生成参数
     *
     * ------- 响应部分 ------
     * method queryAvaliableTimeslots 生成可预约时间列表
     * method queryAvaliableSource    生成可预约服务人员列表
     * method updatePaiedInfo         更新订单支付状态
     * method updateOrderInfo         更新订单状态
     *
     * ------ 订单部分 -------
     * method parseForCreate   整理创建订单数据
     * method updateToPlatform 将订单信息更新到平台
     */
    abstract protected function checkSignature();
    abstract protected function makeSignature();
    abstract protected function makeParams();

    abstract protected function queryStaticSource();
    abstract protected function queryAvailableTimeslots();
    abstract protected function queryAvailableSource();
    abstract protected function updatePaiedInfo();
    abstract protected function updateOrderInfo();

    abstract protected function parseForCreate();
    abstract protected function updateToPlatform();

    /**
     * 父类的构造方法
     */
    private function __construct() {}

    /**
     * 子类的构造方法
     * 实例化后默认获取Request数据、Token及api_url
     */
    public static function getInstance($class = __CLASS__) {
        if (isset(self::$_instances[$class])) {
            return self::$_instances[$class];
        } else {
            $instance = self::$_instances[$class] = new $class(null);
            $instance->setKey()->getRequest()->setUrl();
            return $instance;
        }
    }

    /**
     * 设置ak、sk的方法
     */
    public function setKey($access_key='', $secret_key='') {
        $this->access_key = $access_key;
        $this->secret_key = $secret_key;
        return $this;
    }

    /**
     * 获取secret
     */
    public function getSecret() {
        return $this->secret_key;
    }

    /**
     * 获取access
     */
    public function getAccess() {
        return $this->access_key;
    }

    /**
     * 设置请求参数的方法
     */
    public function setParams($args) {
        $this->params = $args;
        return $this;
    }

    /**
     * 获取对方服务器请求数据，包括POST及GET，并销毁传入的控制器地址(r)
     */
    public function getRequest() {
        if (is_array($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->request[$key] = $value;
            }
        }

        if (is_array($_GET)) {
            foreach ($_GET as $key => $value) {
                $this->request[$key] = $value;
            }
        }

        if (isset($this->request['r'])) {
            unset($this->request['r']);
        }

        return $this;
    }

    /**
     * 获取请求数据字符串
     */
    public function getReuqestArray() {
        return $this->request;
    }

    /**
     * 设置request数据中的值
     */
    public function setRequestValue($key, $value) {
        $this->request[$key] = $value;
        return $this;
    }

    /**
     * 获取request数据中的值
     * @param  string $key     : 参数键名
     * @param  mix    $default : 默认值
     * @return mix    $value   : 获取的值
     */
    public function getRequestValue($key, $default) {
        $value = isset($this->request[$key]) ? $this->request[$key] : $default;
        return $value;
    }

    /**
     * 设置请求的接口地址
     */
    public function setUrl($urlString='') {
        $this->url = $urlString;
        return $this;
    }

    /**
     * 检查预约地址是否在服务范围内
     * @param  float     $latitude   : 纬度（默认为百度坐标）
     * @param  float     $longitude  : 经度（默认为百度坐标）
     * @param  boolean   $gcjTrans   : 是否需要从火星系坐标转换为百度坐标（默认否）
     * @param  boolean   $returnInfo : 是否需要返回地址信息（默认否）
     * @return mix(bool) $flag       : 地址是否在服务范围内
     * @return mix(obj)  $info       : 反向查询获得的地址信息
     */
    public function checkLocation($latitude, $longitude, $gcjTrans=false, $returnInfo=false) {
        if ($gcjTrans) {
            $position = CommonFn::GCJTobaidu($latitude, $longitude);
            $latitude = $position['lat'];
            $longitude = $position['lng'];
        }

        $location = $latitude.','.$longitude;
        $res = CommonFn::simple_http('http://api.map.baidu.com/geocoder/v2/?ak=B349f0b32ef6e78b2e678f45cb9fddaf&location='.$location.'&output=json&pois=0');
        $info = json_decode($res);
        if($info||$info->status==0){
            $addressInfo = $info->result->addressComponent;
            $flag = empty($addressInfo->province)      || empty($addressInfo->city) ||
                    $addressInfo->province != '上海市' || $addressInfo->district == '金山区' ||
                    $addressInfo->district == '松江区' || $addressInfo->district == '奉贤区' ||
                    $addressInfo->district == '青浦区' || $addressInfo->district == '崇明县';
            if($flag) {
                return false;
            }
        } else {
            return false;
        }

        if ($returnInfo) {
            return $info;
        } else {
            return true;
        }
    }

    /**
     * 插入新的订单数据并返回订单ID，若插入失败则返回空
     * @param array $array : 需要插入的数据 array(key => value, ...)
     */
    public function createOrder($array) {
        $order = new ROrder();
        foreach ($array as $key => $value) {
            $order->$key = $value;
        }

        if ($order->save()) {
            $order_id = (string)$order->_id;
        } else {
            $order_id = '';
        }

        return $order_id;
    }

    /**
     * 发送请求
     * @param  string $url    : 请求的URL，若请求方式为GET则包括请求参数
     * @param  number $method : 请求的方式
     * @return string $result : 响应结果
     */
    public function sendRequest($url, $method=self::CURL_GET) {
        if ($method == self::CURL_GET) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        // POST方法暂时未封装
        } else {
            return '';
        }
    }

}