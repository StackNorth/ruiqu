<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/12/12
 * Time: 16:48
 */
class JingBaiController extends O2oBaseController
{
    public function beforeAction($action)
    {
        if (isset($_POST['type'])){
            $type = $_POST['type'];

        }

        if (isset($_GET['type'])){
            $type = $_GET['type'];
        }

        if (isset($type)){
            if($type == Yii::app()->params['JingBai']){
                return true;
            }
        }
        echo '{
                "code": "10001",
                "msg": "缺少必要参数type",
                
            }';
        die();
    }

    //获取产品列表
    public function actionGetAllProduct()
    {
        $criteria = new EMongoCriteria();
        $criteria->status('==', 1);
        $cursor = Product::model()->findAll($criteria);
        $products = CommonFn::getRowsFromCursor($cursor);
        $data = array();
        $data['code'] = '0';
        $data['message'] = 'success';
        foreach ($products as $key => $value) {
            $data['data'][$key]['productId'] = (string)$value['_id'];
            $data['data'][$key]['name'] = $value['name'];
        }

        echo json_encode($data);
    }

    //获取预约时间
    public function actionGetStationTime()
    {
        echo '{
        "code": "0",
        "message": "success",
        "data": [
            {
                "AvailableMaternityAppointmentTimeId": "1", 
                "Time": "8:00" 
            },
            {
                "AvailableMaternityAppointmentTimeId": "2",
                "Time": "8:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "3",
                "Time": "09:00"
            },
            {
                "AvailableMaternityAppointmentTimeId": "4",
                "Time": "09:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "5",
                "Time": "10:00"
            },
            {
                "AvailableMaternityAppointmentTimeId": "6",
                "Time": "10:30"
            },{
                "AvailableMaternityAppointmentTimeId": "7", 
                "Time": "11:00" 
            },
            {
                "AvailableMaternityAppointmentTimeId": "8",
                "Time": "11:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "9",
                "Time": "12:00"
            },
            {
                "AvailableMaternityAppointmentTimeId": "10",
                "Time": "12:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "11",
                "Time": "13:00"
            },
            {
                "AvailableMaternityAppointmentTimeId": "12",
                "Time": "13:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "13", 
                "Time": "14:00" 
            },
            {
                "AvailableMaternityAppointmentTimeId": "14",
                "Time": "14:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "15",
                "Time": "15:00"
            },
            {
                "AvailableMaternityAppointmentTimeId": "16",
                "Time": "15:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "17",
                "Time": "16:00"
            },
            {
                "AvailableMaternityAppointmentTimeId": "18",
                "Time": "16:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "19", 
                "Time": "17:00" 
            },
            {
                "AvailableMaternityAppointmentTimeId": "20",
                "Time": "17:30"
            },
            {
                "AvailableMaternityAppointmentTimeId": "21",
                "Time": "18:00"
            }
        ]
    
        }';

    }

    //获取产品下的服务 类型和价格
    public function actionGetExtraForProduct()
    {
        if (!$_POST) {
            echo '
            {
                "code" : "1001",
                "message": "请求方式不正确"
            }
            ';
            die();
        }

        $productId = Yii::app()->getRequest()->getParam('ProductId', '');
        if (!CommonFn::isMongoId($productId)) {
            echo '
            {
                "code" : "1001",
                "message": "请求的产品id不正确"
            }
            ';
            die();
        }
        $data['code'] = '0';
        $data['message'] = 'success';

        $product = Product::model()->get(new MongoId($productId));

        $data['data']['productId'] = (string)$product->_id;
        $data['data']['name'] = $product->name;
        $data['data']['status'] = $product->status;
        if (empty($product->extra)) {
            $data['data']['product']['price'] = $product->price;
            $data['data']['product']['type'] = $product->name;
        } else {
            foreach ($product->extra as $key => $value) {
                $data['data']['extra'][$key]['price'] = $value['price'];
                $data['data']['extra'][$key]['type'] = $value['type'];
            }
        }
        echo json_encode($data);
    }

    //获取区
    public function actionGetCoverage()
    {
        $station = Station::model()->get(new MongoId("58bd62ebce93ada5048b4578"));
        $coverage = $station->coverage;
        $data = array();
        $data['code'] = '0';
        $data['message'] = 'success';
        foreach ($coverage as $key => $value) {
            $data['data'][$key]['Id'] = $key;
            $data['data'][$key]['Name'] = $value['area'];
        }
        echo json_encode($data);
    }

    //创建订单
    public function actionCreateOrder()
    {
        if (!$_POST) {
            echo '
            {
                "code" : "1001",
                "message": "请求方式不正确"
            }
            ';
            die();
        }
        //创建订单必需参数
        $OrderNo = Yii::app()->getRequest()->getParam('OrderNo','');//Jingbai订单id
        $ProductId = Yii::app()->getRequest()->getParam('ProductId','');
        $name = Yii::app()->getRequest()->getParam('ExpectedMotherName','');
        $mobile = Yii::app()->getRequest()->getParam('Mobile','');
        $address = Yii::app()->getRequest()->getParam('Address','');
        $extra = json_decode(Yii::app()->getRequest()->getParam('extra',''),true);
        $memo = Yii::app()->getRequest()->getParam('Memo','');
        $areaId = intval(Yii::app()->getRequest()->getParam('areaId'));
        $count = intval(Yii::app()->getRequest()->getParam('Quantity',''));
        $finalPrice = floatval(Yii::app()->getRequest()->getParam('Price',''));
        $booking_time = Yii::app()->getRequest()->getParam('AppointmentTime','');
        $invoice = floatval(Yii::app()->getRequest()->getParam('invoice',0));//默认不需要
        $invoice_price = floatval(Yii::app()->getRequest()->getParam('invoicePrice',0));//发票价格
        if (!CommonFn::isMongoId($ProductId)){
            echo '
            {
                "code" : "1001",
                "message": "Productid错误"
            }
            ';
            die();
        }

        //参数不能为空
        if (empty($OrderNo) || empty($ProductId) ||empty($name) ||empty($mobile) ||empty($address)   ||empty($count) ||empty($finalPrice) ||empty($booking_time) ) {
            if(empty($OrderNo)){
                $tmp = 'orderNo';
            } else if(empty($ProductId)){
                $tmp = 'ProductId';
            }else if(empty($name)){
                $tmp = 'ExpectedMotherName';
            }else if(empty($mobile)){
                $tmp = 'Mobile';
            }else if(empty($address)){
                $tmp = 'Address';
            }else if(empty($count)){
                $tmp = 'Quantity';
            }else if(empty($finalPrice)){
                $tmp = 'Price';
            }else if(empty($booking_time)){
                $tmp = 'AppointmentTime';
            }

            echo '
            {
               "code": "10001",
                "message": "缺少参数,请检查'.$tmp.'"
            }
            ';
            die();
        }
        $rOrder = new ROrder();

        $rOrder->channel      = 'jingbai';
        $rOrder->booking_time = intval($booking_time);
        $rOrder->order_time   = time();

        $products[0]['product'] = new MongoId($ProductId);
        $products[0]['count'] = $count;
        if (!empty($extra)){
            $products[0]['extra'] = (object)$extra;
        } else {
            $extra =array();
            $products[0]['extra'] = (object)$extra;
        }


        $rOrder->products     = $products;
        if (isset($extra['price'])) {
            $rOrder->price = $count*floatval($extra['price']);
        }else{
            $rOrder->price = $count*floatval($finalPrice);
        }

        $cursor = Product::model()->get(new MongoId($ProductId));
        $type = $cursor->type;
        $rOrder->type = $type;
        $rOrder->final_price  = $count*floatval($finalPrice);
        if($invoice == 1){
            $rOrder->remark = '需要发票,发票价格为'.$invoice_price;
        }

        $rOrder->counts       = $count;
        $rOrder->status       = intval(1);
        $rOrder->memo         = $memo;

        $rOrder->station      = new MongoId("58bd62ebce93ada5048b4578");
        $station = Station::model()->get(new MongoId("58bd62ebce93ada5048b4578"));
        $coverage = $station->coverage[$areaId];

        $add['province'] = $coverage['province'];
        $add['city'] = $coverage['city'];
        $add['area'] = $coverage['area'];
        $add['detail'] = $address;

        $add['mobile'] = $mobile;
        $add['name'] = $name;
        $rOrder->OrderNo = $OrderNo;

        $rOrder->address      = (object)$add;
        $rOrder->pay_channel  = "jingbai";

        $addROrder_arr = array('OrderNo','channel', 'booking_time', 'order_time', 'price','products', 'final_price', 'counts','precedence', 'coupons', 'user', 'status', 'memo', 'remark', 'type', 'have_comment', 'station', 'address', 'pay_channel');
        $success = $rOrder->save(true, $addROrder_arr);
        if ($success) {
            echo '
            {
               "code": "0",
                "message": "success"
            }
            ';
            die();
        } else {
            echo '
            {
               "code": "10001",
                "message": "订单创建失败,请检查数据格式"
            }
            ';
        }


    }

    /**
     * 取消订单接口
     */
    public function actionDelOrder()
    {
        $orderId = Yii::app()->getRequest()->getParam('OrderId', '');//巾帼园订单编号
        $orderNo = Yii::app()->getRequest()->getParam('OrderNo', '');//精佰订单编号
        //巾帼园订单编号为空 取消所有拥有orderNo的订单。
        $orderIds = array();
        if(empty($orderId)){
            $criteria = new EMongoCriteria();
            $criteria->addCond('OrderNo','==',$orderNo);
            $cursor = ROrder::model()->findAll($criteria);
            $orders = CommonFn::getRowsFromCursor($cursor);
            foreach ($orders as $value){
                $orderIds[] = $value['_id'];
            }
        } else {
            //传了巾帼园订单号，取消所有传过来的巾帼园订单
            $orderIds = explode(',', $orderId);
            foreach ($orderIds as $value) {
                if(!CommonFn::isMongoId($value)){
                    echo '
                    {
                    "code": "10001",
                    "message": "巾帼园订单编号错误"
                    }
                    ';
                    die();
                }
            }
        }
        if (empty($orderIds)) {
            echo '
                    {
                    "code": "10001",
                    "message": "订单编号为空"
                    }
                    ';
            die();
        }

        foreach($orderIds as $id) {
            $order = ROrder::model()->get(new MongoId($id));
            //订单状态不能为已取消
            if ($order->status != -1) {
                if ($order->status == 6) {
                    echo '
                    {
                    "code": "10001",
                    "message": "已完成订单不能取消"
                    }
                    ';
                    die();
                } else {
                    $order->status = -3;
                }
                $success = $order->save();
                if(!$success){
                    echo '
                    {
                    "code": "10001",
                    "message": "请求失败,订单号为:"' . $id . '
                    }
                    ';
                    die();
                }
            }
        }

        echo '
            {
               "code": "0",
                "message": "请求成功，请等待回复"
            }
            ';
        die();

    }

}
