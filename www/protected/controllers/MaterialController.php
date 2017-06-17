<?php 
/**
 * 物资控制器
 * @author zhouxuchen 2015-09-16
 */
class MaterialController extends AdminController {

    // 物资首页
    public function actionIndex () {
        $status_option = Material::$status_option;
        $unit_option = Material::$unit_option;
        $enable_option = Material::$enable_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);
        $type = CommonFn::getComboboxData($unit_option, 100, false, 0);
        $enable = CommonFn::getComboboxData($enable_option, 100, true, 100);

        // 服务点信息
        $criteria = new EMongoCriteria();
        $cursor = Station::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Station::model()->parse($rows);
        $station_data = array();
        foreach ($parsedRows as $key => $v) {
            $station_data = array_merge($station_data, array($v['id'] => array('name' => $v['name'])));
            // $station_data[] = array('name' => $v['name']);
        }

        $station = CommonFn::getComboboxData($station_data, '', false, '');

        $this->render('index', array(
            'status'  => $status,
            'type'    => $type,
            'enable'  => $enable,
            'station' => $station
        ));
    }

    // 物资列表
    public function actionList () {
        $params = CommonFn::getPageParams();

        $id = Yii::app()->request->getParam('id', '');
        $status = intval(Yii::app()->request->getParam('status', 100));
        $enable = intval(Yii::app()->request->getParam('enable', 100));
        $search = Yii::app()->request->getParam('search', '');

        $criteria = new EMongoCriteria($params);

        // 库存状态删选
        if($status!=100){
            $criteria->status('==', $status);
        }
        // 物资启用状态筛选
        if($enable!=100){
            $criteria->enable('==', $enable);
        }

        if ($search != ''){
            if (CommonFn::isMongoId($search)){
                $criteria->_id('==',new MongoId($search));
            } else {
                $criteria->name = new MongoRegex('/' . $search . '/');
            }
        }

        $cursor = Material::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Material::model()->parse($rows);

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    // 编辑物资信息
    // 若id被传入则物资信息被编辑，若id未传入则新建一个物资信息
    public function actionEdit () {
        $id = Yii::app()->request->getParam('id', '');

        $name = Yii::app()->request->getParam('name', '');
        $unit = intval(Yii::app()->request->getParam('unit', 0));
        $stockWarnLine = intval(Yii::app()->request->getParam('stockWarnLine', 0));
        $price = number_format(Yii::app()->request->getParam('price', 0), 2, '.', '');
        $enable = intval(Yii::app()->request->getParam('enable', 1));
        $stock = intval(Yii::app()->request->getParam('stock', 0));
        $remarks = Yii::app()->request->getParam('remarks', '');

        if (!$unit) {
            CommonFn::requestAjax(false, '请选择单位');
            die;
        }

        if(!$id){
            $material = new Material();
            $material->addTime = time();
            $material->stock = $stock;
        }else{
            $criteria = new EMongoCriteria();
            $criteria->_id = new MongoId($id);
            $material = Material::model()->find($criteria);
            if (empty($material)){
                CommonFn::requestAjax(false, '物资不存在');
                die;
            }
        }

        // 直接修改物资，正式使用后该行注释
        $material->stock = $stock;

        $material->name = $name;
        $material->unit = $unit;
        $material->unit_str = $this->unit_str($unit); // 判断单位str
        $material->price = $price;
        $material->stockWarnLine = $stockWarnLine;
        $material->status = $this->status($material->stock, $material->stockWarnLine);
        $material->status_str = $this->status_str($material->status); //判断库存状态
        $material->enable = $enable;
        $material->enable_str = $this->enable_str($enable);
        $material->remarks = $remarks;

        $arr_addMaterial = array('name', 'unit_str', 'unit', 'price', 'stock', 'stockWarnLine', 'addTime', 'user', 'status', 'status_str', 'enable', 'enable_str', 'remarks');

        $success = $material->save(true, $arr_addMaterial);

        CommonFn::requestAjax($success, '', array());
    }

    /**
     * 出入库操作
     * @param obj c_user     : criteria for model `User`
     * @param obj c_material : criteria for model `Material`
     * @param obj c_object   : criteria for model `User`
     * @param obj c_station  : criteria for model `Station`
     */
    public function actionStock () {
        $mid = Yii::app()->request->getParam('mid', '');
        $operate = intval(Yii::app()->request->getParam('operate', 1));
        $num = intval(Yii::app()->request->getParam('num', 0));
        $tot_price = Yii::app()->request->getParam('tot_price', 0);
        $remarks =Yii::app()->request->getParam('remarks', '');
        $object = Yii::app()->request->getParam('object', '');
        $sid = Yii::app()->request->getParam('station', '');
        $time = time();

        if ($mid == '') {
            CommonFn::requestAjax(false, '请选择物资');
            die;
        }

        if ($num <= 0) {
            CommonFn::requestAjax(false, '数量错误');
            die;
        }

        // 用户信息
        $user = Yii::app()->user;
        $email = $user->name;
        $c_user = new EMongoCriteria();
        $c_user->email = $email;
        $userInfo = User::model()->find($c_user);

        // 物资基本信息
        $c_material = new EMongoCriteria();
        $c_material->_id = new MongoId($mid);
        $material = Material::model()->find($c_material);
        if (empty($material)) {
            CommonFn::requestAjax(false, '物资不存在');
            die;
        }

        $stock = new Stock();

        // 所需要的物资数据
        $lastStock = $material->stock;
        $price = $material->price;

        // 入库数量及价格
        $stock->num = $num;
        $stock->tot_price = $tot_price != 0 ? $tot_price : $num * $price;
        $stock->lastStock = $lastStock;
        $stock->newStock = $this->newStock($lastStock, $num, $operate);
        if ($stock->newStock < 0) {
            CommonFn::requestAjax(false, '出库操作错误，请检查数量', array());
            die;
        }

        if ($operate == 0) {
            if ($sid == '') {
                CommonFn::requestAjax(false, '请选择服务点');
                die;
            }

            // 目标用户
            $c_object = new EMongoCriteria();
            $c_object->name = $object;
            $objInfo = User::model()->find($c_object);
            if (empty($objInfo)) {
                CommonFn::requestAjax(false, '目标用户不存在');
                die;
            } else {
                $stock->object = $objInfo->_id;
                $stock->objectName = $objInfo->name;
            }

            // 服务点信息
            $c_station = new EMongoCriteria();
            $c_station->_id = new MongoId($sid);
            $station = Station::model()->find($c_station);
            if (empty($station)) {
                CommonFn::requestAjax(false, '服务点不存在');
                die;
            }
            $stock->station = new MongoId($sid);
            $stock->stationName = $station->name;


            $arr_addStock = array('mid', 'mname', 'operate', 'operate_str', 'user', 'time', 'username', 'num', 'tot_price', 'lastStock', 'newStock', 'remarks', 'object', 'objectName', 'station', 'stationName');
        } else {
            $stock->objectName = '公司总库';
            $stock->stationName = '无';

            $arr_addStock = array('mid', 'mname', 'operate', 'operate_str', 'user', 'time', 'username', 'num', 'tot_price', 'lastStock', 'newStock', 'remarks', 'objectName', 'stationName');
        }

        $stock->mid = new MongoId($mid);
        $stock->mname = $material->name;
        $stock->operate = $operate;
        $stock->operate_str = $this->operate_str($operate);
        $stock->user = $userInfo->_id;
        $stock->username = $userInfo->name;
        $stock->time = $time;

        $stock->remarks = $remarks;

        $material->stock = $stock->newStock;
        $material->status = $this->status($material->stock, $material->stockWarnLine);
        $material->status_str = $this->status_str($material->status);

        $arr_addMaterial = array('stock', 'status', 'status_str');

        // 增加库存表记录
        $success_stock = $stock->save(true, $arr_addStock);
        // 修改物资表记录
        $success_material = $material->save(true, $arr_addMaterial);
        // print_r($stock);die;

        CommonFn::requestAjax($success_stock&$success_material, '', array());
    }

    // 自动填充用户
    public function actionSelectUser () {
        $chars = Yii::app()->request->getParam('user', '');

        $MongoDbAuthManager = new CMongoDbAuthManager();
        $users_id = $MongoDbAuthManager->getAuthUser('保洁师');

        $criteria = new EMongoCriteria();
        $criteria->addCond('name', 'or', new MongoRegex('/'.$chars.'/'));
        $criteria->addCond('email', 'or', new MongoRegex('/'.$chars.'/'));

        $cursor = User::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $index = 0;
        foreach ($rows as $key => $v) {
            if (in_array($v['_id'], $users_id)) {
                $arr[] = array(
                    'id' => $index,
                    'data' => $v['name'],
                    'description' => $v['email'],
                    'uid' => $v['_id'],
                );
                $index++;
            } else {
                continue;
            }
        }

        if (empty($arr)) {
            $arr = array(
                'id' => 0,
                'data' => '',
                'description' => '',
                'uid' => -1,
            );
        }

        echo json_encode($arr);
    }

    // 自动填充物资
    public function actionSelectMaterial () {
        $chars = Yii::app()->request->getParam('material', '');

        $criteria = new EMongoCriteria();
        $criteria->name = new MongoRegex('/'.$chars.'/');

        $cursor = Material::model()->findAll($criteria);
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

    // 判断单位类型
    protected function unit_str ($type) {
        switch ($type) {
            case 1:
                $unit_str = '瓶';
                break;
            case 2:
                $unit_str = '袋';
                break;
            case 3:
                $unit_str = '盒';
                break;
            case 4:
                $unit_str = '台';
                break;
            case 5:
                $unit_str = '件';
                break;
            case 6:
                $unit_str = '双';
                break;
            case 7:
                $unit_str = '只';
                break;
            case 8:
                $unit_str = '个';
                break;
            case 9:
                $unit_str = '套';
                break;
            case 10:
                $unit_str = '副';
                break;
            case 11:
                $unit_str = '毫升';
                break;
            default:
                $unit_str = '未找到相应单位';
                break;
        }

        return $unit_str;
    }

    // 判断库存状态（status）
    protected function status ($stock, $stockWarnLine) {
        if ($stock > $stockWarnLine && $stockWarnLine >= 0) {
            $status = 2;
        } else if ($stock > 0 && $stock <= $stockWarnLine) {
            $status = 1;
        } else if ($stock == 0) {
            $status = 0;
        } else {
            $status = 3;
        }

        return $status;
    }

    // 判断库存状态(status_str)
    protected function status_str ($type) {
        switch ($type) {
            case 0:
                $status_str = '无库存';
                break;
            case 1:
                $status_str = '紧张';
                break;
            case 2:
                $status_str = '充足';
                break;
            default:
                $status_str = '未知';
                break;
        }

        return $status_str;
    }

    // 判断物资是否启用
    protected function enable_str ($type) {
        if ($type == 1) {
            $enable_str = '启用';
        } else {
            $enable_str = '停用';
        }

        return $enable_str;
    }

    // 判断库存操作
    protected function operate_str ($type) {
        if ($type == 0) {
            $operate_str = '出库';
        } else {
            $operate_str = '入库';
        }

        return $operate_str;
    }

    // 新库存计算
    protected function newStock ($lastStock, $num, $operate) {
        if ($operate == 0) {
            $newStock = $lastStock - $num;
        } else {
            $newStock = $lastStock + $num;
        }

        return $newStock;
    }
}
?>