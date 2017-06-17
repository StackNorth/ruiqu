<?php 
/**
 * 库存控制器
 * @author     2015-09-18
 */
class StockController extends AdminController {

    // 显示库存修改信息首页
    public function actionIndex () {
        $operate_option = Stock::$operate_option;
        $operate = CommonFn::getComboboxData($operate_option, 100, true, 100);
        $objectName = Yii::app()->request->getParam('objectName', '');
        $stationName = Yii::app()->request->getParam('stationName', '');
        $fromStockView = (!empty($objectName) || !empty($stationName)) ? true : false;

        // 服务点信息
        $criteria = new EMongoCriteria();
        $cursor = Station::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Station::model()->parse($rows);
        $station_data = array();
        foreach ($parsedRows as $key => $v) {
            $id = (string)$v['id'];
            $station_data = array_merge($station_data, array($id => array('name' => $v['name'])));
        }
        $station_data = array_merge($station_data, array('noStation' => array('name' => '无')));

        $station = CommonFn::getComboboxData($station_data, 'all', true, 'all');

        $data = array(
            'operate'       => $operate,
            'objectName'    => $objectName,
            'stationName'   => $stationName,
            'fromStockView' => $fromStockView,
            'station'       => $station
        );

        $this->render('index', $data);
    }

    // 库存操作列表
    public function actionList () {
        $params = CommonFn::getPageParams();

        $operate = intval(Yii::app()->request->getParam('operate', 100));
        $s_mname = Yii::app()->request->getParam('s_mname', '');
        $s_user = Yii::app()->request->getParam('s_user', '');
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end = Yii::app()->request->getParam('date_end', '');
        $station = Yii::app()->request->getParam('station', 'all');

        $criteria = new EMongoCriteria($params);

        // 时间处理
        if (!empty($date_start) && !empty($date_end)) {
            // 开始时间处理
            $timestamp_start = strtotime($date_start);
            // 结束时间处理，需通过strtotime()增加一天
            $timestamp_end = strtotime('+1 day', strtotime($date_end));

            $criteria->time('>=', $timestamp_start);
            $criteria->time('<=', $timestamp_end);
        }

        // 操作筛选
        if($operate != 100) {
            $criteria->operate('==', $operate);
        }

        // 根据物资名称搜索
        if ($s_mname != '') {
            if (CommonFn::isMongoId($s_mname)){
                $criteria->mid('==',new MongoId($s_mname));
            } else {
                $criteria->mname = new MongoRegex('/' . $s_mname . '/');
            }
        }

        // 根据目标用户名搜索
        if ($s_user != '') {
            if (CommonFn::isMongoId($s_user)){
                $criteria->object('==', new MongoId($s_user));
            } else {
                $criteria->objectName = new MongoRegex('/' . $s_user . '/');
            }
        }

        // 根据服务点搜索
        if ($station != 'all') {
            if ($station == 'noStation') {
                $criteria->stationName('==', '无');
            } else {
                $criteria->station('==', new MongoId($station));
            }
        }

        $cursor = Stock::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Stock::model()->parse($rows);

        // $this->p($parsedRows);die;

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    // 编辑库存操作备注
    public function actionEdit () {
        // echo "<pre>";
        // print_r($_POST);die;

        $id         = Yii::app()->request->getParam('id', '');
        $objectName = Yii::app()->request->getParam('objectName', '');
        $station    = Yii::app()->request->getParam('station', '');
        $remarks    = Yii::app()->request->getParam('remarks', ''); 

        $criteria = new EMongoCriteria();
        $criteria->_id = new MongoId($id);
        $stock = Stock::model()->find($criteria);
        if (empty($stock)) {
            CommonFn::requestAjax(false, '该条操作不存在');
        }

        $arr_addStocks = array();

        // 获取目标用户信息
        if ($objectName != $stock->objectName) {
            $criteria = new EMongoCriteria();
            $criteria->name = $objectName;
            $object_info = User::model()->find($criteria);

            if (count($object_info) == 0) {
                CommonFn::requestAjax(false, '用户不存在，请检查用户名', array());
                die;
            }

            $stock->objectName = $objectName;
            $stock->object = $object_info->_id;
            $arr_addStocks[] = 'object';
            $arr_addStocks[] = 'objectName';
        }

        // 获取服务点信息
        if ((string)$station != (string)$stock->station) {
            $station_id = new MongoId($station);
            $criteria = new EMongoCriteria();
            $criteria->_id = $station_id;
            $station_info = Station::model()->find($criteria);

            $stock->stationName = $station_info->name;
            $stock->station = $station_id;
            $arr_addStocks[] = 'stationName';
            $arr_addStocks[] = 'station';
        }

        $arr_addStocks[] = 'remarks';

        $stock->remarks = $remarks;

        $success = $stock->save(true, $arr_addStocks);

        CommonFn::requestAjax($success, '', array());
    }

    // 自动填充服务点信息
    public function actionSelectStation () {
        $station = Yii::app()->request->getParam('station', '');

        $criteria = new EMongoCriteria();
        $criteria->name = new MongoRegex('/'.$station.'/');

        $cursor = Station::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);

        if (empty($rows)) {
            $arr = array(
                'id' => 0,
                'data' => ''
            );
        } else {
            foreach ($rows as $key => $v) {
                $arr[] = array(
                    'id' => $key,
                    'data' => $v['name']
                );
            }
        }

        echo json_encode($arr);
    }

    /**
     * 几个私有方法
     * p           使用<pre>标签打印数据
     * checkAction 检查减小库存的操作是否超过最小库存量
     * checkStock  检查库存量所处的状态
     * unit        判断单位类型
     */
    protected function p($arr) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

    // 检查库存是否充足（针对减小库存的操作）
    protected function checkAction () {
        // TODO...
    }

    // 修改库存后检查库存的状态（针对所有操作）
    protected function checkStock () {
        // TODO...
    }

    // 判断单位类型
    protected function unit ($type) {
        switch ($type) {
            case 1:
                $unit = '瓶';
                break;
            case 2:
                $unit = '袋';
                break;
            case 3:
                $unit = '盒';
                break;
            case 4:
                $unit = '台';
                break;
            case 5:
                $unit = '件';
                break;
            default:
                $unit = '未找到相应单位';
                break;
        }

        return $unit;
    }

}

?>