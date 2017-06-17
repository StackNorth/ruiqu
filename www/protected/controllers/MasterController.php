<?php
class MasterController extends AdminController{

    public function actionIndex()
    {
        $status_option = Master::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);

        $this->render('index', array(
            'status' => $status
        ));
    }

    public function actionList(){
        $params = CommonFn::getPageParams();
        $type = Yii::app()->request->getParam('type', 100);
        $id = Yii::app()->request->getParam('id', '');
        $status = intval(Yii::app()->request->getParam('status', 100));
        $search = Yii::app()->request->getParam('search', '');
        $criteria = new EMongoCriteria($params);


        if ($type != 100){
            $criteria->type('==', new MongoId($type));
        }

        if($status!=100){
            $criteria->status('==', $status);
        }

        if ($id != ''){
            $master_id = new MongoId($id);
            $criteria->_id('==', $master_id);
        }

        if ($search != ''){
            if (CommonFn::isMongoId($search)){
                $criteria->_id('==',new MongoId($search));
            } else {
                $criteria->name = new MongoRegex('/' . $search . '/');
            }
        }

        $cursor = Master::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Master::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionEdit(){
        $id = Yii::app()->request->getParam('id', '');
        $status = intval(Yii::app()->request->getParam('status', 1));
        $avatar = Yii::app()->request->getParam('avatar', '');
        $type = Yii::app()->request->getParam('type', 'beautician');
        $name = Yii::app()->request->getParam('name', '');
        $desc = Yii::app()->request->getParam('desc', '');
        $city_info = Yii::app()->request->getParam('city_info', array());

        $mobile = Yii::app()->request->getParam('mobile', '');
        $address = Yii::app()->request->getParam('address', '');

        $position = Yii::app()->request->getParam('position',array());

        $sex = intval(Yii::app()->request->getParam('sex',3));
        $pics =  Yii::app()->request->getParam('pics',array());
        $coverage =  Yii::app()->request->getParam('coverage',array());

        if($status == 100){
            CommonFn::requestAjax(false, '必须指定状态！');
        }

        if(!$avatar){
            CommonFn::requestAjax(false, '头像不能为空');
        }

        if(!$type){
            CommonFn::requestAjax(false, '类型不能为空');
        }

        if(!$mobile){
            CommonFn::requestAjax(false, '联系方式不能为空');
        }

        if(empty($position)){
            CommonFn::requestAjax(false, '位置不能为空');
        }

        if(empty($coverage)){
            CommonFn::requestAjax(false, '服务区域不能为空');
        }

        $cityArray = array();
        $zCity = new ZCityInfo();
        if(!$zCity->checkCity($city_info,$cityArray)){
            CommonFn::requestAjax(false, '请检查城市信息是否正确！');
        }

        if ($name == '' || mb_strlen($name, 'utf-8') < 2 ||mb_strlen($name, 'utf-8') > 10){
            CommonFn::requestAjax(false, '姓名至少2个字,最多10个字');
        }

        if (mb_strlen($desc, 'utf-8') > 500){
            CommonFn::requestAjax(false, '简介最多500个字！');
        }

        $cityArray = array();
        $zCity = new ZCityInfo();
        if(!$zCity->checkCity($city_info,$cityArray)){
            CommonFn::requestAjax(false, '请检查城市信息是否正确！');
        }

        if(!$id){
            $master = new Master();
            $op = 'insert';
        }else{
            $op = 'update';
            $criteria = new EMongoCriteria();
            $criteria->_id = new MongoId($id);
            $master = Master::model()->find($criteria);
            if (empty($master)){
                CommonFn::requestAjax(false, '此人不存在');
            }
        }

        $sex = $sex>1?1:$sex;

        $new_pics = array();
        foreach($pics as $picStr){
            $pic =  json_decode($picStr,true);
            if(isset($pic) && $pic){
                $new_pics[] = $pic;
            }
        }
        $pics = $new_pics;

        $status=$status>1?1:$status;
        $master->name = $name;
        $master->desc = CommonFn::parse_break($desc);
        $master->avatar = $avatar;
        $master->status = $status;
        $master->type = $type;
        $master->city_info = $cityArray;
        //$master->user = $user;
        //$master->ruser = $ruser;
        $master->position = $position;
        $master->mobile = $mobile;
        $master->address = $address;
        $master->sex = $sex;
        $master->coverage = $coverage;
        $master->pics = $pics;

        $arr_addMaster = array('status','user','ruser','type','name','city_info','position','mobile','address','sex','avatar','desc','pics','coverage');

        $success = $master->save(true,$arr_addMaster);

        CommonFn::requestAjax($success, '', array());
    }
}
