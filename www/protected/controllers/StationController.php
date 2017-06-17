<?php
class StationController extends AdminController{

    public function actionIndex()
    {
        $status_option = Station::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);

        $type = CommonFn::getComboboxData(Yii::app()->params['o2o_service'], 100, true, 100);

        $this->render('index', array(
            'status' => $status,
            'type' => $type
        ));
    }

    public function actionList(){
        $filter_status = intval(Yii::app()->request->getParam('status', 100));
        $search = Yii::app()->request->getParam('search', '');
        $id = Yii::app()->request->getParam('id', '');

        $params = CommonFn::getPageParams();
        $criteria = new EMongoCriteria($params);

        if ($id != ''){
            $station_id = new MongoId($id);
            $criteria->_id('==', $station_id);
        }
        if ($filter_status != 100){
            $criteria->status('==', $filter_status);
        }


        if ($search != ''){
            $criteria->name('or', new MongoId($search));
        }

        $cursor = Station::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Station::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionEdit(){
        $id = Yii::app()->request->getParam('id', '');

        $status = intval(Yii::app()->request->getParam('status', 1));
        $name = Yii::app()->request->getParam('name', '');
        $start_time = intval(Yii::app()->request->getParam('start_time', 9));
        $end_time = intval(Yii::app()->request->getParam('end_time', 20));
        $beauticians_count = intval(Yii::app()->request->getParam('beauticians_count', 1));

        $address = Yii::app()->request->getParam('address', array());

        $types = Yii::app()->request->getParam('types', array());

        $address = json_decode($address);

        $coverage = Yii::app()->request->getParam('coverage', array());
        $coverage = json_decode($coverage);

        if($status == 100){
            CommonFn::requestAjax(false, '必须指定状态！');
        }
        if($name == '' || empty($address) ){
            CommonFn::requestAjax(false, '必填内容为空');
        }

        if($start_time >= $end_time){
            CommonFn::requestAjax(false, '时间选择错误');
        }

        //$types_arr = array();
        //if(!empty($types)){
            //foreach ($types as $type) {
                //if($type != 100){
                   // $types_arr[] = Yii::app()->params['o2o_service'][$type];
                //}else{
                    //CommonFn::requestAjax(false, '服务项目不能为空');
               // }
            //}
        //}else{
            //CommonFn::requestAjax(false, '服务项目不能为空');
        //}

        $status=$status>1?1:$status;

        if(!$id){
            $station = new Station();
        }else{
            $criteria = new EMongoCriteria();
            $criteria->_id = new MongoId($id);
            $station = Station::model()->find($criteria);
            if (empty($station)){
                CommonFn::requestAjax(false, '服务点 不存在');
            }
        }

        $station->status = $status;
        $station->name = $name;
        $station->start_time = $start_time;
        $station->end_time = $end_time;
        $station->beauticians_count = $beauticians_count;
        $station->address = $address;
        $station->coverage = $coverage;
        //$station->types = $types;


        $arr_station = array('status','name','start_time','end_time','beauticians_count','address','coverage');

        $success = $station->save(true,$arr_station);
        CommonFn::requestAjax($success, '', array());
    }
}
