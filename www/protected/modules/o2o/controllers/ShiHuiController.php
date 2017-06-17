<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/11/3
 * Time: 13:55
 */

class ShiHuiController extends O2oBaseController {
    //访问接口前进行签名验证
    public function beforeAction($action){
        if(isset($_GET['no_sign'])){
            return true;
        }else{
            unset($_GET['no_sign']);
            return $this->shiHuiCheckSign();
        }
    }


    // 获取服务时间接口
    public function actionQueryTime(){
        $latitude = Yii::app()->getRequest()->getParam("latitude"); //服务地址经纬度(火星坐标系)
        $longitude = Yii::app()->getRequest()->getParam("longitude"); //服务地址经纬度(火星坐标系)
        $position = CommonFn::GCJTobaidu($latitude,$longitude);
        $location = $position['lat'].','.$position['lng'];
        $res = CommonFn::simple_http('http://api.map.baidu.com/geocoder/v2/?ak=B349f0b32ef6e78b2e678f45cb9fddaf&location='.$location.'&output=json&pois=0');
        $info = json_decode($res);
        if($info||$info->status==0){
            $info = $info->result->addressComponent;

            if(!empty($info->province) && !empty($info->city) && $info->province == '上海市' && $info->district != '金山区' &&  $info->district != '松江区' &&  $info->district != '奉贤区' &&  $info->district != '青浦区' &&  $info->district != '崇明县'){
                $cityinfo['province']  = $info->province;
                $cityinfo['city']  = $info->city;
                $cityinfo['area']  = $info->district;
            }else{
                echo '{
                    "code": "20001",
                    "msg": "地址不在服务范围1"
                }';
                die();
            }
        }else{
            echo '{
                "code": "20001",
                "msg": "地址不在服务范围2"
            }';
            die();
        }
        $date[] = date('Y-m-d',time()+86400);
        $date[] = date('Y-m-d',time()+86400*2);
        $date[] = date('Y-m-d',time()+86400*3);
        $date[] = date('Y-m-d',time()+86400*4);
        $date[] = date('Y-m-d',time()+86400*5);
        $date[] = date('Y-m-d',time()+86400*6);
        $data['code'] = '0';
        $data['msg'] = 'success';
        $data['body'] = array();
        $timelist = array();
        foreach ($date as $day) {
            $day_res['date'] = $day;
            $time = strtotime($day);
            $holiday_start = strtotime('2016-01-01');
            $holiday_end = strtotime('2016-01-01');

            if( $time>=$holiday_start && $time<=$holiday_end ){
                $day_res['timeslot'] = "000000000000000000000000000000000000000000000000";
            }else{
                $day_res['timeslot'] = "000000000000000000111111111111111111111111100000";
            }
            $timelist[] = $day_res;
        }
        $data['body']['timeList'] = $timelist;
        echo  json_encode($data);
    }
    // 服务是否可用
    public function actionQueryProductIsAvailable(){
        echo '{"code": "0","msg": "服务可用"}';
    }

    //支付通知
    public function actionQueryPay()
    {
        $orderId = Yii::app()->getRequest()->getParam("orderId");
        if (!CommonFn::isMongoId($orderId)) {
            echo '{"code": "1001","msg": "订单id不正确!"}';
            die();
        }
        $criteria = new EMongoCriteria();
        $criteria->_id('==', new MongoId($orderId));
        $order = ROrder::model()->find($criteria);
        //订单不存在
        if (!$order) {
            echo '{"code": "3001","msg": "订单不存在!"}';
            die();
        } else if(intval($order->status) != 0){
            echo '{"code": "3001","msg": "已经通知过啦!!"}';
            die();
        }else {
            $order->status = 1;//修改订单状态
            //修改成功
            if($order->save()){
                echo '{"code": "0","msg": "通知成功"}';
            } else {
                echo '{"code": "1003","msg": "状态修改失败!"}';
                die();
            }
        }


    }

    //订单评价
    public function actionOrderComment(){
        $orderId = Yii::app()->getRequest()->getParam("orderId");
        $comment = Yii::app()->getRequest()->getParam("comments");//评价
        $score   = initval(Yii::app()->getRequest()->getParam("comments"));//评分


    }

    //创建订单
    public function actionCreateOrder(){
        $cityId = Yii::app()->getRequest()->getParam("cityId");
        $productId = Yii::app()->getRequest()->getParam("productId");
        $product_const = Yii::app()->params['ProductId'];

        foreach ($product_const as $key => $value) {
            $flag =1;
            foreach ($value as $k => $v) {

                if ($productId == $k){
                    $extra_type = $v;
                    if ($key == 2){
                        $productId = '57e0e0189f5160dc048b4568';//深度清洁id
                        $service_type = 2; $flag =0;break;

                    } else if($key == 1){
                        $productId = '57e0dffc9f5160dd048b4568';//日常清洁id
                        $service_type = 1; $flag =0;break;
                    }

                }
            }
            if (!$flag) break;

        }
        $criteria = new EMongoCriteria();
        $criteria->_id('==',new MongoId($productId));
        //$criteria->addCond('extra.type','==',$extra_type);
        $product_obj = Product::model()->find($criteria);

        $products = array();
        $price = 0;
        if($product_obj){
            $products[0]['product'] = $product_obj->_id;
            $products[0]['count']   = 1;
            $products[0]['extra']   = $product_obj->extra[$extra_type];
        }else{
            echo '{"code": "1003","msg": "product_not_exist!"}';
            die();
        }
        $serviceTime = Yii::app()->getRequest()->getParam("serviceStartTime");    //服务开始时间，格式为2015-04-18 10:30:00
        $final_price = Yii::app()->getRequest()->getParam("price");    //产品价格，以元为单位，精确到分
        $latitude = Yii::app()->getRequest()->getParam("latitude"); //服务地址经纬度(火星坐标系)
        $counts = Yii::app()->getRequest()->getParam("amount");
        $longitude = Yii::app()->getRequest()->getParam("longitude"); //服务地址经纬度(火星坐标系)
        $serviceAddress = Yii::app()->getRequest()->getParam("serviceAddress"); //服务地址，精确到小区
        $houseNumber = Yii::app()->getRequest()->getParam("detailAddress"); //服务地址，门牌号等详细信息
        $cellphone = Yii::app()->getRequest()->getParam("phone"); //用户电话

        $comment = Yii::app()->getRequest()->getParam("remark"); //用户备注
        $name = Yii::app()->getRequest()->getParam("contactName");//用户姓名

        $position = CommonFn::GCJTobaidu($latitude,$longitude);
        $location = $position['lat'].','.$position['lng'];
        $res = CommonFn::simple_http('http://api.map.baidu.com/geocoder/v2/?ak=B349f0b32ef6e78b2e678f45cb9fddaf&location='.$location.'&output=json&pois=0');
        $info = json_decode($res);
        if($info||$info->status==0){
            $info = $info->result->addressComponent;
            if(!empty($info->province) && !empty($info->city) && $info->province == '上海市'){
                $cityinfo['province']  = $info->province;
                $cityinfo['city']  = $info->city;
                $cityinfo['area']  = $info->district;
            }else{
                echo '{
                    "code": "20001",
                    "msg": "地址不在服务范围"
                }';
                die();
            }
        }else{
            echo '{
                "code": "20001",
                "msg": "地址不在服务范围"
            }';
            die();
        }
        $order = new ROrder();
        $order->channel = 'shihui';
        $order->order_time = time();
        $order->booking_time = strtotime($serviceTime);
        $order->products = $products;
        // $order->precedence = $precedence; //加急状态
        $order->price = intval($price);
        $order->final_price = intval($final_price);
        $address = array();
        $address['province'] = $cityinfo['province'];
        $address['city'] = $cityinfo['city'];
        $address['area'] = $cityinfo['area'];
        $address['name'] = $name;

        if($serviceAddress == $houseNumber){
            $address['detail'] = $serviceAddress;
        }else{
            $address['detail'] = $serviceAddress.$houseNumber;
        }
        $address['poi']['name'] = $address['detail'];
        $address['mobile'] = $cellphone;
        $positions[0] = isset($position['lng'])?floatval($position['lng']):0;
        $positions[1] = isset($position['lat'])?floatval($position['lat']):0;
        $address['position'] = $positions;
        $order->address = $address;
        $order->memo = $comment;
        $order->station = new MongoId('5548b05e0eb9fbc5728b51ea');
        // $order->coupons = $used_coupon;
        $order->status = 0;
        $order->user = '';
        $order->pay_channel = 'shihui';
        $order->type = $service_type;
        $order->price = $products[0]['extra']['price'];
        $order->counts = $counts;
        if($order->save()){
            echo '{
                "code": "1",
                "msg": "创建订单成功",
                "result": {
                    "orderId": "'.(string)$order->_id.'"
                }
            }';
        }else{
            echo '{
                "code": "1004",
                "msg": "创建订单失败",
            }';
        }


    }
    //取消订单
    public function actionQueryDel(){
        $order_id = Yii::app()->getRequest()->getParam("orderId");
        if( !$order_id || !CommonFn::isMongoId($order_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
             echo '{"code": "1001","msg": "params_illegal"}';die();
        }

        $order = ROrder::get(new MongoId($order_id));
        $status = -1;
        if(!$order){
            echo '{"code": "1001","msg": "params_illegal"}';die();
        }

        if( $order->status!=-1 && $order->status!=-2 ){
            foreach ($order->coupons as $user_coupon) {
                $user_coupon = UserCoupon::get($user_coupon);
                $user_coupon->status = 1;
                $user_coupon->update(array('status'),true);
            }
        }

        $order->status = $status;
        $arr_order = array('status');
        $success = $order->update($arr_order,true);
        if($success){
            echo '{"code": "0","msg": "订单取消成功"}';
        }


    }

    //取消订单
    public function actionDel(){
        $data['key']         = Yii::app()->params['shKey'];
        $data['version']     = '1.0';
        $data['serviceType'] = (int)26;
        $data['orderId']     = (string)Yii::app()->getRequest()->getParam('orderId','');//'57fde8059f5160c4048b4aeb';

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
        $res = json_decode($result,true);
        if ($res['code'] == 0){
            return true;
        } else {
            return false;
        }
    }

    //更新订单状态
    public function actionUpdateStatus(){
        $data['key']         = Yii::app()->params['shKey'];
        $data['version']     = '1.0';
        $data['serviceType'] = (int)26;
        $data['orderId']     = (string)Yii::app()->getRequest()->getParam('orderId','');
        $data['status']      = Yii::app()->getRequest()->getParam('status');
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
        $url = 'http://test2.app.hiwemeet.com/v2/openpf/home/order/thirdOrder/update';  //调用接口的平台服务地址

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dat);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result,true);
        if ($res['code'] == 0){
            return true;
        } else {
            return false;
        }

    }


}