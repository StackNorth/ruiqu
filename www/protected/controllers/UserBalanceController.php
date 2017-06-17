<?php

/**
 * Created by PhpStorm.
 * User: north
 * Date: 2017/6/17
 * Time: 上午8:26
 */
class UserBalanceController extends AdminController
{
    public function actionIndex(){
        $status = Balance::$channel_option;
        $channel_option = CommonFn::getComboboxData($status, 100, true, 100);
        $data = self::getYestodayBalance();
        $data['register_people_num'] = self::getYestodayRegisterUser();
        $this->render('index',[
            'channel_option'=>$channel_option,
            'data'=>$data
        ]);
    }

    public function actionList() {
        $pageParams = CommonFn::getPageParams();

        $id = intval(Yii::app()->request->getParam('id'));
        $search = Yii::app()->request->getParam('search', '');
        $date_start_order = Yii::app()->request->getParam('date_start_order','');
        $date_end_order = Yii::app()->request->getParam('date_end_order','');
        $type = intval(Yii::app()->request->getParam('status',100));

        $criteria = new EMongoCriteria($pageParams);
        // id筛选
        if ($id) {
            $criteria->_id('==', new MongoId($id));
        }
        // 状态筛选
        if ($type != 100) {
            $criteria->type('==', $type);
        }
        if($search) {

            $criteria->addCond('uid','==',intval($search));

        }


        if (!empty($date_start_order) && !empty($date_end_order)) {
            // 开始时间处理
            $timestamp_start_order = strtotime($date_start_order);
            // 结束时间处理，需通过strototime()增加一天
            $timestamp_end_order = strtotime('+1 day', strtotime($date_end_order));

            $criteria->create_time_i('>=', $timestamp_start_order);
            $criteria->create_time_i('<', $timestamp_end_order);
        }

        $cursor = Balance::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = Balance::model()->parse($rows);
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
        $cookie = dirname(__FILE__) . '/cookie_oschina.txt';
        $res = CommonFn::http_post($data, $url, $cookie);
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
                $res1 = CommonFn::http_post($data, $url, $cookie);
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

    public function  getYestodayRegisterUser(){

        date_default_timezone_set('PRC');

        $start_time = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $end_time = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
        $criteria = new EMongoCriteria();
        $criteria->regist_time_i('>=',$start_time);
        $criteria->regist_time_i('<=',$end_time);

        $rquser = RqUser::model()->findAll($criteria);

        return  count($rquser);//昨日注册人数
    }
    public function  getYestodayBalance(){

        date_default_timezone_set('PRC');

        $start_time = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $end_time = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
        $criteria = new EMongoCriteria();
        $criteria->create_time_i('>=',$start_time);
        $criteria->create_time_i('<=',$end_time);
        $criteria->type('==',1);
        $criteria->from('==',0);
        $balances = Balance::model()->findAll($criteria);
        $total = 0.0;

        $data['balance_count'] = count($balances);//昨日总单数
        $uids = array();
        foreach ($balances as $key=>$value ){
            $total += $value->pay_money;
            $flag = in_array($value->uid,$uids);
            if(!$flag) {
                $uids[] = $value->uid;
            }
            //var_dump( $value->pay_money);
        }
        $data['balance_people_num']=count($uids);//昨日总人数
        $data['total']=$total;//昨日充值数
        return $data;
    }
}