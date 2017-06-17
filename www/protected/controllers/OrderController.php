<?php
class OrderController extends AdminController{

    public function actionCount() {
        header('Content-Type: application/json; charset=utf-8');
        $mongo      = new MongoClient(DB_CONNETC);
        $db         = $mongo->fuwu;
        $collection = $db->selectCollection('orders');

        $pipleline  = array(
            array(
                '$match' => array(
                    'user' => array('$nin' => array()),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$user',
                    'count' => array('$sum' => 1)
                ),
            ),
            array(
                '$sort' => array(
                    'count' => -1
                ),
            ),
            array(
                '$limit' => 60
            ),
        );

        $a = $collection->aggregate($pipleline);
        $result = $a['result'];

        foreach ($result as $key => &$row) {
            $user = RUser::get($row['_id']);
            $row['user_name'] = $user->user_name;
            $row['No'] = $key + 1;
        }

        echo json_encode($result);
    }

    public function actionIndex()
    {
        $status_option = Order::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);
        $this->render('index', array(
            'status' => $status
        ));
    }

    public function actionList(){
        $params = CommonFn::getPageParams();
        $search = Yii::app()->request->getParam('search', '');
        $status = intval(Yii::app()->request->getParam('status', 100));
        $search = trim($search);
        if(preg_match('/\d{13,14}/',$search)){
            $res = Order::getOrderByOrderNu($search);
            if($res){
                $rows[] = $res;
                $parsedRows = Order::model()->parse($rows);
                echo CommonFn::composeDatagridData($parsedRows,1);
            }
            die();

        }
        $criteria = new EMongoCriteria($params);
        if($status!=100){
            $criteria->status('==', $status);
        }
        if ($search != ''){
            if (strlen($search) == 24){
                $criteria->addCond('user','==',new MongoId($search));
            }else{
                $user = RUser::getUserByName($search);
                if($user){
                    $criteria->addCond('user','==',$user->_id);
                }
            }
        }
        $criteria->sort('time',EMongoCriteria::SORT_DESC);
        $cursor = Order::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Order::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

     public function actionEdit(){
        $id = Yii::app()->request->getParam('id', '');

        $express_company = Yii::app()->request->getParam('express_company', '');
        $express_number = Yii::app()->request->getParam('express_number', '');
        $memo = Yii::app()->request->getParam('memo', '');
        $reason = Yii::app()->request->getParam('reason','');

        $status = intval(Yii::app()->request->getParam('status', 1));
 
        if($status == 100){
            CommonFn::requestAjax(false, '必须指定订单状态！');
        }

        if(!$id){
            CommonFn::requestAjax(false, '订单不存在');
        }else{
            $criteria = new EMongoCriteria();
            $criteria->_id = new MongoId($id);
            $order = Order::model()->find($criteria);
            if (empty($order)){
                CommonFn::requestAjax(false, '订单不存在');
            }
        }
        $order->express_company = $express_company;
        $order->express_number = $express_number;
        $order->memo = $memo;
        if(($order->status==0 || $order->status==3)&&$status == -2){
            $return_count = true;
        }elseif ($order->status == -2 && ($status == 0 || $status == 1 || $status==3) ) {
            $cut_count = true;
        }
        if(isset($cut_count)){
            foreach ($order->goods as $key => $value) {
                $goods = CommonFn::getObJ($value['goods_id'],"ZGoods");
                if($goods && $goods->count>=1){
                    $goods->count -= 1;
                    $goods->update(array('count',true));
                }else{
                    CommonFn::requestAjax(false, '商品库存不足');
                }
            }
        }
        $order->status = $status;
        $success = $order->update(array('express_number','express_company','memo','status'),true);
        if($success&&isset($order->address['mobile'])){
            $order_info = Order::model()->parseRow($order);
            if($order->status == -2){
                //已取消
                CommonSMS::send('shop_order_cancel',array('order_num'=>$order_info['order_num'],'mobile'=>$order_info['address']['mobile']));
            }elseif ($order->status == 1){
                //已发货
                CommonSMS::send('shop_order_ship',array('express_company'=>$order_info['express_company'],'express_number'=>$order_info['express_number'],'mobile'=>$order_info['address']['mobile']));
            }
        }
        if($success&&isset($return_count)){
            foreach ($order->goods as $key => $value) {
                $goods = CommonFn::getObJ($value['goods_id'],"ZGoods");
                if($goods){
                    $goods->count += 1;
                    $goods->update(array('count',true));
                }
            }
        }
        CommonFn::requestAjax($success, '', array());
    }

}
