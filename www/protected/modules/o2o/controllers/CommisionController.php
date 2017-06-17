<?php 
/**
 * 提成控制器
 * @author     2015-12-02
 */
class CommisionController extends AdminController {

    public function actionIndex() {
        $type_option = Commision::$type_option;
        $type = CommonFn::getComboboxData($type_option, 100, true, 100);

        $this->render('index', array(
            'type_option' => $type,
        ));
    }

    public function actionList() {
        $search = Yii::app()->request->getParam('search', '');
        $type   = intval(Yii::app()->request->getParam('type', 100));
        $start  = Yii::app()->request->getParam('start', '');
        $end    = Yii::app()->request->getParam('end', '');

        $params = CommonFn::getPageParams();
        $criteria = new EMongoCriteria($params);

        // 订单类型筛选
        if ($type != 100) {
            $criteria->type('==', $type);
        }

        // 时间筛选
        if ($start != '') {
            $start_time = strtotime($start);
            $criteria->booking_time('>=', $start_time);
        }

        if ($end != '') {
            $end_time = strtotime($end);
            $end_time = strtotime('+1 day', $end_time);
            $criteria->booking_time('<', $end_time);
        }

        // 模糊搜索
        if ($search != '') {
            if (CommonFn::isMongoId($search)) {
                $criteria->addCond('_id', 'or', new MongoId($search));
                $criteria->addCond('order', 'or', new MongoId($search));
            } else {
                $user_regex = new MongoRegex('/'.$search.'/');
                $criteria_user = new EMongoCriteria();
                $criteria_user->addCond('email', 'or', $user_regex);
                $criteria_user->addCond('name', 'or', $user_regex);
                $users = User::model()->findAll($criteria_user);
                $users_id = array();
                foreach ($users as $key => $row) {
                    $users_id[] = $row->_id;
                }

                $criteria->addCond('user', 'in', $users_id);
            }
        }

        $cursor = Commision::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);

        $parsedRows = Commision::model()->parse($rows);
        $total = $cursor->count();

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    /**
     * 添加提成数据
     */
    public function actionAddCommision() {
        $datetime  = Yii::app()->request->getParam('datetime', '');
        $user_name = Yii::app()->request->getParam('user_name', '');
        $user      = intval(Yii::app()->request->getParam('user', -1));
        $order     = Yii::app()->request->getParam('order', '');
        $commision = floatval(Yii::app()->request->getParam('commision', 0));

        $time = empty($datetime) ? time() : strtotime($datetime);
        // 保洁师处理
        // 优先根据userid查询
        if ($user != -1) {
            $userObj = User::get($user);
            if (empty($userObj)) {
                CommonFn::requestAjax(false, '查无此人', array());
            }
        } else if ($user_name != '') {
            $criteria = new EMongoCriteria();
            $criteria->name('==', $user_name);
            $userObj = User::model()->find($criteria);
            if (empty($userObj)) {
                CommonFn::requestAjax(false, '查无此人', array());
            } else {
                $user = $userObj->_id;
            }
        } else {
            CommonFn::requestAjax(false, '查无此人', array());
        }

        // 订单处理
        if ($order != '') {
            if (!CommonFn::isMongoId($order)) {
                CommonFn::requestAjax(false, '请检查订单ID', array());
            } else {
                $orderID = new MongoId($order);
                $orderObj = ROrder::get($orderID);
                if (empty($orderObj)) {
                    $orderObj = AppendOrder::get($orderID);
                    if (empty($orderObj)) {
                        CommonFn::requestAjax(false, '订单不存在', array());
                    } else {
                        $type = 1;
                    }
                } else {
                    $type = 0;
                }
            }
        } else {
            $orderID = '';
            $type = -1;
        }

        $commisionObj = new Commision();
        $commisionObj->time      = $time;
        $commisionObj->user      = $user;
        $commisionObj->order     = $orderID;
        $commisionObj->commision = $commision;
        $commisionObj->type      = $type;

        $success = $commisionObj->insert();
        CommonFn::requestAjax($success, '', array());
    }

    /**
     * 异步查询某一保洁师时间段内提成总和
     */
    public function actionCommisionCountOne() {
        $search = Yii::app()->request->getParam('search', '');
        $start = Yii::app()->request->getParam('start', '');
        $end = Yii::app()->request->getParam('end', '');
        $type = intval(Yii::app()->request->getParam('type', 100));

        if (CommonFn::isMongoId($search)) {
            $user = User::get(new MongoId($search));
        } else {
            $user_regex = new MongoRegex('/'.$search.'/');
            $criteria = new EMongoCriteria();
            $criteria->addCond('email', 'or', $user_regex);
            $criteria->addCond('name', 'or', $user_regex);
            $cursor = User::model()->findAll($criteria);
            if ($cursor->count() == 1) {
                foreach ($cursor as $value) {
                    $user = $value;
                }
            } else {
                $user = null;
            }
        }

        $data = array();
        if ($user) {
            $mongo = new MongoClient(DB_CONNETC);
            $db = $mongo->wozhua_o2o;
            $collection = $db->selectCollection('commision');

            $pipleline = array(
                array(
                    '$match' => array(
                        'user' => array('$eq' => $user->_id),
                        'time' => array(
                            '$gte' => strtotime($start),
                            '$lt'  => strtotime('+1 day', strtotime($end)),
                        ),
                    ),
                ),
                array(
                    '$group' => array(
                        '_id' => '$type',
                        'sum' => array('$sum' => '$commision'),
                    ),
                ),
            );

            if ($type != 100) {
                $pipleline[0]['$match']['type'] = array('$eq' => $type);
            }

            $a = $collection->aggregate($pipleline);
            if (isset($a['result'])) {
                $sum = 0;
                foreach ($a['result'] as $key => $value) {
                    $data[] = array(
                        'type' => Commision::$type_option[$value['_id']]['name'],
                        'sum' => $value['sum'],
                    );
                    $sum += $value['sum'];
                }
                if ($type == 100) {
                    $data[] = array(
                        'type' => '全部',
                        'sum' => $sum,
                    );
                }
            }
        }

        echo json_encode($data);
    }

    public function actionFixCommisionTime() {
        set_time_limit(0);

        $criteria = new EMongoCriteria();
        $criteria->type('noteq', -1);
        $cursor = Commision::model()->findAll($criteria);
        foreach ($cursor as $key => $model) {
            if (!$model->booking_time) {
                if ($model->type == 0) {
                    $order = ROrder::get($model->order);
                    if ($order) {
                        $model->booking_time = $order->booking_time;
                        $model->save();
                    }
                } else if ($model->type == 1) {
                    $append_order = AppendOrder::get($model->order);
                    if ($append_order) {
                        $order = ROrder::get($append_order->order);
                        $model->booking_time = $order->booking_time;
                        $model->save();
                    }
                }
            } else {
                continue;
            }
        }

        echo 'ok';
    }

}
