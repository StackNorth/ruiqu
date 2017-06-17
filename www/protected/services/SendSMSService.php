<?php
class SendSMSService extends Service{
    /**
    * url 为服务的url地址
    * query 为请求串
    */
    public function sock_post($url,$query){
        // if(ENVIRONMENT != 'product'){
        //  return false;
        // }
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;
    }

    /**
    * 模板接口发短信
    * apikey 为云片分配的apikey
    * tpl_id 为模板id
    * tpl_value 为模板值
    * mobile 为接受短信的手机号
    */
    public function tpl_send_sms($tpl_id, $tpl_value, $mobile){
        $t_mobile = array('18521093629');
        if(ENVIRONMENT != 'product' && !in_array($mobile,$t_mobile)){
            return false;
            // $mobile = '18521093629';
        }
        //$apikey = '181e7c60a605e96d6a0166579cbb76a4';
        $apikey = 'a9a0eae716ebc9da2d13b1ed161ade7a';
        $url="http://yunpian.com/v1/sms/tpl_send.json";
        $encoded_tpl_value = urlencode("$tpl_value");
        $post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
        return $this->sock_post($url, $post_string);
    }

    /**
    * 普通接口发短信
    * apikey 为云片分配的apikey
    * text 为短信内容
    * mobile 为接受短信的手机号
    */
    public function send_sms($text, $mobile){
        $t_mobile = array('18521093629');
        if(ENVIRONMENT != 'product' && !in_array($mobile,$t_mobile)){
            return false;
            // $mobile = '18521093629';
        }
        //$apikey = '181e7c60a605e96d6a0166579cbb76a4';
        $apikey = 'a9a0eae716ebc9da2d13b1ed161ade7a';
        $url="http://yunpian.com/v1/sms/send.json";
        $encoded_text = urlencode($text);
        $post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
        return $this->sock_post($url, $post_string);
    }
}
?>

