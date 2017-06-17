<?php
class CouponCodeController extends AdminController{

    public function actionIndex()
    {
        $status_option = CouponCode::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);

        $this->render('index', array(
            'status' => $status,
        ));
    }

    public function actionList(){
        $search = Yii::app()->request->getParam('search', '');
        $filter_status = intval(Yii::app()->request->getParam('status', 100));

        $params = CommonFn::getPageParams();
        $criteria = new EMongoCriteria($params);


        if ($filter_status != 100){
            $criteria->status('==', $filter_status);
        }

        if ($search != '') {
            $criteria->code('or', new MongoRegex('/'.$search.'/'));
            $criteria->channel('or', new MongoRegex('/'.$search.'/'));
        }

        $cursor = CouponCode::model()->findAll($criteria);
        $total = $cursor->count();
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = CouponCode::model()->parse($rows);
        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionEdit(){
        $id = Yii::app()->request->getParam('id', '');
        $status = intval(Yii::app()->request->getParam('status', 1));

        if(!$id){
            CommonFn::requestAjax(false, '请选择兑换码');
        }

        if($status == 100 ){
            CommonFn::requestAjax(false, '必须指定状态');
        }

        $status=$status>1?1:$status;

        $criteria = new EMongoCriteria();
        $criteria->_id = new MongoId($id);
        $coupon_code = CouponCode::model()->find($criteria);
        if (empty($coupon_code)){
            CommonFn::requestAjax(false, '兑换码 不存在');
        }

        $arr_coupon = array('status');

        $coupon_code->status = $status;

        $success = $coupon_code->save(true,$arr_coupon);
        CommonFn::requestAjax($success, '', array());
    }

}