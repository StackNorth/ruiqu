<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/11/23
 * Time: 9:11
 */

class GetRuiquUserDataCommand extends CConsoleCommand {
    public function run($args){
        error_reporting(E_ALL);
        ini_set('memory_limit', '256M');
        set_time_limit(0);
        self::getData();

    }


    public function getData()
    {
        // 连接mongodb数据库
        $mongo = new MongoClient();
        $db_name=$mongo->test;
        $collection_name=$db_name->user;
        $page = 1;
        while($page){
            $datas = array();
            $datas['act'] = 'default_search';
            $datas['page'] = $page;
            $datas['size'] = 30;
            $url = 'http://m.opencom.cn/ajax/app_userinfo.php?appid=41807';
            $cookie = Yii::app()->params['cookie'];
            $res = CommonFn::http_post($datas,$url,$cookie,1);
            if($res['ret']) {
                $tmp = 0;
                foreach ($res['list'] as $key => $value) {
                    $query = array('uid'=> intval($value['uid']));
                    $cursor = $collection_name->findOne($query);
                    if(!empty($cursor)) {
                        echo '用户已存在'.$value['uid'];
                        $file = fopen('2.txt','a') or die('open file');
                        fwrite($file,'用户已存在'.$value['uid']."\r\n");
                        fclose($file);
                        $user = RqUser::get($cursor['_id']);
                        $user->uid = $value['uid'];
                        $user->user_credit = $value['user_credit'];
                        $user->post = $value['post'];
                        $user->phone = $value['phone'];
                        $user->regist_time_i = $value['regist_time_i'];
                        $user->user_name = $value['user_name'];
                        $user->use_exp = $value['use_exp'];
                        $user->save_time_i = $value['save_time_i'];
                        $user->user_level = $value['user_level'];
                        $user->dynamic = $value['dynamic'];
                        $user->tx_id = $value['tx_id'];
                        $user->pm = $value['pm'];
                        $result1 = $user->save();
                        if($result1){
                            $tmp ++;
                        }
                        if($tmp > 10) {
                            echo $tmp;exit;
                        }
                    } else {
                        $result=$collection_name->insert($value);
                        if($result) {
                            echo $page."\r\n";
                        } else {
                            echo 'foreach'.$page;exit;
                        }
                    }

                }
                $page++;
                sleep(2);
            } else {
                if(isset($res['not_have'])) {
                    echo $page;
                    exit;
                }
            }

        }


    }
    public function updateUserData() {
        $users = RqUser::model()->findAll();
        $count = 0;
        foreach ($users as $key => $user){
            $data = array();
            $data['act'] = 'all';
            $data['uid'] = $user->uid;

            $url = 'http://m.opencom.cn/ajax/app_userinfo.php';
            $cookie = Yii::app()->params['cookie'];
            $res = CommonFn::http_post($data,$url,$cookie,1);
            if(!isset($res['ret'])) {
                $data = array();
                $data['act'] = 'all';
                $data['uid'] = $user->uid;
                $res2 = CommonFn::http_post($data,$url,$cookie);
                if(!isset($res2)) {
                    echo $user->_id;
                    exit;
                } else {
                    $res = $res2;
                }
            }
            if( $res['ret'] ) {//获取用户的 发帖数 动态数 最后在线时间
                $tmp = RqUser::get($user->_id);
                $tmp->post_num =$res['post_num'];
                $tmp->flist_count =$res['flist_count'];
                $tmp->last_time =$res['last_time'];
                $result = $tmp->save();
                if($result) {//更新成功 获取用户的余额 总支出和总收入
                    $data = array();
                    $data['act'] = 'app_order_money_count';
                    $data['to_uid'] = $user->uid;

                    $url = 'http://m.opencom.cn/ajax/cf_list.php?appid=41807';
                    $res1 = CommonFn::http_post($data,$url,$cookie);
                    if($res1['ret']) {//获取成功
                        $tmp = RqUser::get($user->_id);
                        $tmp->account_money =$res1['account_money'];
                        $tmp->count_money =$res1['count_money'];
                        $tmp->pay_money =$res1['pay_money'];
                        $result = $tmp->save();
                        if($result) {// 保存成功
                            //sleep(1);
                        } else {
                            $file = fopen('4.txt','a') or die('open file');
                            fwrite($file,'写入失败'.$tmp->_id."\r\n");
                            fclose($file);exit;
                        }
                    } else {
                        $tmp = RqUser::get($user->_id);
                        $tmp->account_money =0;
                        $tmp->count_money =0;
                        $tmp->pay_money =0;
                        $result = $tmp->save();
                        if($result) {// 保存成功
                            //sleep(1);
                        } else {
                            $file = fopen('4.txt','a') or die('open file');
                            fwrite($file,'写入失败'.$tmp->_id."\r\n");
                            fclose($file);exit;
                        }
                    }
                    $count++;
                    echo $count."\r\n";
                    echo $user->_id."\r\n";
                } else {
                    $file = fopen('3.txt','a') or die('open file');
                    fwrite($file,'写入失败'.$tmp->_id."\r\n");
                    fclose($file);exit;
                }
            }

        }
    }


}

