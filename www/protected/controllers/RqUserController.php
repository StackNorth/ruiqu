<?php

class RqUserController extends AdminController {

    public function actionIndex(){
        $this->render('index');
    }

    public function actionList() {
        $pageParams = CommonFn::getPageParams();

        $id = intval(Yii::app()->request->getParam('id'));
        $search = Yii::app()->request->getParam('search', '');
        $date_start_order = Yii::app()->request->getParam('date_start_order','');
        $date_end_order = Yii::app()->request->getParam('date_end_order','');

        $criteria = new EMongoCriteria($pageParams);
        // id筛选
        if ($id) {
            $criteria->_id('==', new MongoId($id));
        }
        if($search) {
            if( is_numeric($search)){
                $criteria->addCond('phone','or',intval($search));
                $criteria->addCond('uid','or',intval($search));

            } else {
                $criteria->addCond('user_name','==',new MongoRegex('/'.$search.'/'));

            }
        }


        if (!empty($date_start_order) && !empty($date_end_order)) {
            // 开始时间处理
            $timestamp_start_order = strtotime($date_start_order);
            // 结束时间处理，需通过strototime()增加一天
            $timestamp_end_order = strtotime('+1 day', strtotime($date_end_order));

            $criteria->regist_time_i('>=', $timestamp_start_order);
            $criteria->regist_time_i('<', $timestamp_end_order);
        }

        $cursor = RqUser::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = RqUser::model()->parse($rows);
        $total = $cursor->count();

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    public function actionDetail()
    {
        $uid = Yii::app()->request->getParam('uid', '');
        if(empty($uid)) {
            CommonFn::requestAjax(false, "请传入正确的uid");exit;
        }
        $criteria = new EMongoCriteria();
        $criteria->uid('==',intval($uid));
        $rqUser = RqUser::model()->find($criteria);
        $data = array();
        $data['uid'] = $uid;
        $data['act'] = 'all';

        $url = 'http://m.opencom.cn/ajax/app_userinfo.php';
        $cookie = Yii::app()->params['cookie'];
        $res = CommonFn::http_post($data, $url, $cookie,1);
        if ($res['ret']) {//获取用户的 发帖数 动态数 最后在线时间
            $rqUser->post_num = $res['post_num'];
            $rqUser->flist_count = $res['flist_count'];
            $rqUser->last_time = $res['last_time'];
            $result = $rqUser->save();
            if ($result) {//更新成功 获取用户的余额 总支出和总收入
                $data = array();
                $data['act'] = 'app_order_money_count';
                $data['to_uid'] = $uid;

                $url = 'http://m.opencom.cn/ajax/cf_list.php?appid=41807';
                $res1 = CommonFn::http_post($data, $url, $cookie,1);
                if ($res1['ret']) {//获取成功
                    $rqUser->account_money = $res1['account_money'];
                    $rqUser->count_money = $res1['count_money'];
                    $rqUser->pay_money = $res1['pay_money'];
                    $result = $rqUser->save();
                    if ($result) {// 保存成功
                        //sleep(1);
                    } else {
                        CommonFn::requestAjax($result, "获取失败");exit;
                    }
                } else {
                    $rqUser->account_money = 0;
                    $rqUser->count_money = 0;
                    $rqUser->pay_money = 0;
                    $result = $rqUser->save();

                }
            }
            CommonFn::requestAjax($result, "true", $rqUser);exit;
        }
    }
}