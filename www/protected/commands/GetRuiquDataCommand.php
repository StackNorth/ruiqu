<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/11/23
 * Time: 9:11
 */

class GetRuiquDataCommand extends CConsoleCommand {
    public function run($args){
        error_reporting(E_ALL);
        ini_set('memory_limit', '256M');
        set_time_limit(0);
        self::getData();
        //self::getYestodayBalance();
    }

    public function getData()
    {
        // 连接mongodb数据库
        $mongo = new MongoClient();
        $db_name=$mongo->test;

        $collection_name=$db_name->balance;
        $page = 1;
        $url = 'http://m.opencom.cn/ajax/login.php?url=http://m.opencom.cn/&app_kind=';
        $data['user_id'] = '15821769872';
        $data['password'] = 'de9b8132bf387981d6cc1f940421509d';
        $cookie = dirname(__FILE__) . '/cookie_oschina.txt';
        CommonFn::get_cookie($url,$data,$cookie);


        while($page){
            $data = array();
            $data['act'] = 'get_user_balance';
            $data['page'] = $page;
            $data['size'] = 15;
            //$data['start_data'] = '2017-06-15';
            //$data['end_data'] = '2017-06-15';
            $data['currency_type'] = 2;
            $data['recharge_type'] = -1;
            $url = 'http://m.opencom.cn/ajax/cf_list.php?appid=41807';
            $res = CommonFn::http_post($data,$url,$cookie);
            if($res['ret']) {
                $tmp = 0;
                foreach ($res['list'] as $key => $value) {
                    $query = array('uid'=> intval($value['uid']),"order_num"=>$value['order_num'],"from"=>$value['from'],"type"=>$value['type'],"type_name"=>$value['type_name']);
                    $cursor = $collection_name->findOne($query);
                    if(!empty($cursor)) {

                        $file = fopen('1.txt','a') or die('open file');
                        fwrite($file,'用户已存在'.$value['uid']."订单".$value['order_num']."\r\n");
                        fclose($file);
                        $balance = Balance::get($cursor['_id']);
                        $balance->app_kind = $cursor['app_kind'];
                        $balance->uid = $cursor['uid'];
                        $balance->order_num = $cursor['order_num'];
                        $balance->cash_flow_name = $cursor['cash_flow_name'];
                        $balance->w_order_name = $cursor['w_order_name'];
                        $balance->pay_money = $cursor['pay_money'];
                        $balance->init_money = $cursor['init_money'];
                        $balance->surplus_money = $cursor['surplus_money'];
                        $balance->from = $cursor['from'];
                        $balance->type = $cursor['type'];
                        $balance->create_time = $cursor['create_time'];
                        $balance->create_time_i = $cursor['create_time_i'];
                        $balance->deal_time = $cursor['deal_time'];
                        $balance->deal_time_i = $cursor['deal_time_i'];
                        $balance->type_name = $cursor['type_name'];
                        $result1 = $balance->save();
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
                sleep(1);
            } else {
                if(isset($res['not_have'])) {
                    echo $page;
                    exit;
                }
            }

        }


    }

    public function http_post($data,$url,$cookie){
        $str = '';
        $dat = '';
        foreach ($data as $key => $value) {
            $str .= $key.'='.$value.'&';
            $dat .= $key.'='.urlencode($value).'&';
        }
        $str = rtrim(trim($str),'&');

        $str .= $str.Yii::app()->params['shMd5Key'];

        $data['sign'] = strtoupper(md5($str));
        $dat .= 'sign='.urlencode($data['sign']);//构建post 参数
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dat);
        curl_setopt($ch,CURLOPT_COOKIE,$cookie);

        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result,true);
        return $res;
    }

}

