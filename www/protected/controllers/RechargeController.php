<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/9/28
 * Time: 9:29
 */
class RechargeController extends AdminController {
    public function actionIndex() {
        $status_option = Recharge::$status_option;
        $status = CommonFn::getComboboxData($status_option, 100, true, 100);

        // 代金券信息
        $criteria_main = new EMongoCriteria();
        $criteria_main->status('==', 1);
        $conpon_main = Coupon::model()->findAll($criteria_main);
        $rows_main = CommonFn::getRowsFromCursor($conpon_main);
        $main_row = array();
        foreach($rows_main as $row){
            $ids = (string)$row['_id'];
            $main_row[$ids] = array('name' =>$row['name']);
        }
        $main_coupons = CommonFn::getComboboxData($main_row, 100, true, 100);

        $this->render('index', array(
            'status'         => $status,
            'main_coupons' => $main_coupons,

        ));
    }


    public function actionList() {

        $search = Yii::app()->request->getParam('search', '');
        $id = Yii::app()->request->getParam('id', '');

        $filter_status = intval(Yii::app()->request->getParam('status', 100));
        $params = CommonFn::getPageParams();

        $criteria = new EMongoCriteria($params);


        if ($id != ''){
            $recharge_id = new MongoId($id);
            $criteria->_id('==', $recharge_id);
        }
        if ($filter_status != 100){
            $criteria->addCond('status','==',intval($filter_status));

        }

        if ($search != ''){
            if(CommonFn::isMongoId($search)){
                $criteria->_id('==', new MongoId($search));

                $criteria->addCond('_id','or',new MongoId($search));
                $criteria->addCond('user','or',new MongoId($search));
            }

        }

        $recharge = Recharge::model()->findAll($criteria);

        $total = $recharge->count();

        $rows = CommonFn::getRowsFromCursor($recharge);

        $parsedRows = Recharge::model()->parse($rows);

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }
    //添加充值卡信息
    public function actionAdd () {
        $denomination = intval(Yii::app()->request->getParam('denomination', 0));
        $cash_back = intval(Yii::app()->request->getParam('cash_back', 0));
        $status = intval(Yii::app()->request->getParam('status', 0));
        $desc = Yii::app()->request->getParam('desc', '');
        $order = intval(Yii::app()->request->getParam('order', 1));
        $coupons = array();
        $select_fun = Yii::app()->request->getParam('add_select_fun', '');
        $flag = empty($denomination);
        $flag = $flag || $status == -1 ;
        if ($flag) {
            CommonFn::requestAjax(false, '请检查数据完整性', array());
            exit;
        }
        $coupons_obj = array();
        //判断 返现 代金券
        if ($select_fun == 'coupon') {
            //数组分割处理
            $coupons = explode("<br />",nl2br(trim(Yii::app()->request->getParam('coupons'))));
            foreach ($coupons as $key => $coupon) {
                $coupon = rtrim(trim($coupon),',');
                foreach ($coupons as $k => $v) {
                    if ($key != $k) {
                        $v = rtrim(trim($v),',');
                        if ($v == $coupon) {
                            CommonFn::requestAjax(false, '代金券id重复');
                            break;
                        }
                    }
                }


                if (CommonFn::isMongoId($coupon)) {
                    $coupon = new MongoId($coupon);
                    $coupon_obj = Coupon::get($coupon);

                    if (!$coupon_obj) {
                        CommonFn::requestAjax(false, '代金券不存在');
                        break;
                    } else {
                        $coupons_obj[$key] = $coupon;
                    }
                } else {
                    CommonFn::requestAjax(false, '代金券输入错误');
                    break;
                }
            }


        } else{
            //返现
            $cash_back = intval(Yii::app()->request->getParam('cash_back', ''));
            if ($cash_back <= 0 ){
                CommonFn::requestAjax(false, '返现金额不能为0');
            }
        }



        $recharge = new Recharge();
        $recharge->denomination = intval($denomination);//充值面额
        $recharge->cash_back = intval($cash_back);//返现金额
        $recharge->coupons = $coupons_obj;
        $recharge->desc = $desc;//描述
        $recharge->status = intval($status);//状态
        $recharge->order = $order;
        $addRecharge_arr = array('denomination','cash_back','status','desc','coupons','order');
        $success = $recharge->save(true, $addRecharge_arr);

        CommonFn::requestAjax($success, '', array());

    }


    public function actionEdit() {
        $denomination = intval(Yii::app()->request->getParam('denomination',''));//面额
        $desc = Yii::app()->request->getParam('desc', '');//描述
        $status = intval(Yii::app()->request->getParam('status', 0));//状态
        $order = intval(Yii::app()->request->getParam('order', 1));
        $id = Yii::app()->request->getParam('id', '');
        $coupons = array();//代金券
        $select_fun = Yii::app()->request->getParam('select_fun', '');
        $coupons_obj = array();
        //指定状态
        if ($status == 100) {
            CommonFn::requestAjax(false, '必须指定状态！');
        }
        //填写面额
        if (!isset($denomination)) {
            CommonFn::requestAjax(false, '必须填写面额！');
        }
        $cash_back = 0;
        if ($select_fun == 'coupon') {
            //数组分割处理
            $coupons = explode("<br />",nl2br(trim(Yii::app()->request->getParam('text_coupons'))));

            if (empty($coupons[0])) {
                CommonFn::requestAjax(false, '请输入代金券');

            }
            foreach ($coupons as $key => $coupon) {
                $coupon = rtrim(trim($coupon),',');
                foreach ($coupons as $k => $v) {
                    if ($key != $k) {
                        $v = rtrim(trim($v),',');
                        if ($v == $coupon) {
                            CommonFn::requestAjax(false, '代金券id重复');
                            break;
                        }
                    }
                }

                $coupon = new MongoId($coupon);
                $coupon_obj = Coupon::get($coupon);

                if (!$coupon_obj) {
                    CommonFn::requestAjax(false, '代金券不存在');
                    break;
                } else {
                    $coupons_obj[$key] = $coupon;
                }
            }

        } else{
            //返现
            $cash_back = intval(Yii::app()->request->getParam('cash_back', ''));
            if ($cash_back <= 0 ){
                CommonFn::requestAjax(false, '返现金额不能为0');
            }
        }
        $criteria = new EMongoCriteria();
        $criteria->_id = new MongoId($id);
        $recharge = Recharge::model()->find($criteria);
        $recharge->order = $order;
        $recharge->coupons = $coupons_obj;
        $recharge->cash_back = $cash_back;
        $recharge->desc = $desc;
        $recharge->denomination = $denomination;
        $recharge->status = $status;
        //保存修改的充值券信息
        $addRecharge_arr = array('denomination','cash_back','status','desc','coupons','order');
        $success = $recharge->save(true, $addRecharge_arr);
        CommonFn::requestAjax($success, '', array());

    }
}