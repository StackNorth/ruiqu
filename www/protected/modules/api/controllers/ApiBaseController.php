<?php
/**
 * api接口公共基类
 */
class ApiBaseController extends CController{

    //api访问前进行签名验证
    /**
     * @return bool
     */
    protected function verify() {
       if(!empty(Yii::app()->request->getParam('no_sign'))&&(YII_DEBUG == true)){
           return true;
       }
       if(Yii::app()->request->getParam('app_client_id') == 1){
            $this->check_version();
        }
       $result = $this->api_check_sign();
       return $result;
    }

   
    

    //判断用户是否是当日首次访问应用，可以将一些定期任务放入此方法内调用
    protected function today_first_login($user_id){
        $date = date('Ymd');
        $Key = HelperKey::generateUserActionKey('login',$date,$user_id);
        $status = UserActionRedis::get($Key);
        if(!$status && !empty($user_id)){
            UserActionRedis::set($Key,true);//设置用户状态为已签到
            $syncData['user_id'] = $user_id;
            $syncData['app_client_id'] = intval(Yii::app()->request->getParam('app_client_id'));
            $syncData['device_id'] = Yii::app()->request->getParam('device_id');
            $syncData['channel'] = Yii::app()->request->getParam('channel'); 
            $syncData['app_version'] = Yii::app()->request->getParam('app_version'); 
            $syncData['phone_type'] = Yii::app()->request->getParam('phone_type');
            $syncData['os_version'] = Yii::app()->request->getParam('os_version');
            $syncData['last_visit_time'] = time();
            $list = new ARedisList('user_info_update');
            $list->push(serialize($syncData));
            $add_score = $this->addScore($user_id,'score_first_open');
            if($add_score['status']){
                return $add_score;
            }
        }
    }

    
    //签名验证方法
    //每次GET/POST请求的参数，凡是在这个列表以内的参数名字：["id","app_client_id","time","topic_id","group_id","user_id","post_id"]加上private_key按key字母升序排列拼接,然后md5运算之后生成
    protected function api_check_sign(){
//        return true;
        $need_args=array('device_id','os_version','api_version','time','channel','app_client_id','app_version','sign');
        $sign_args=array("id","app_client_id","time","topic_id","group_id","user_id","post_id","app_version");

        $request=array();
        if(is_array($_GET)){
            foreach($_GET as $k=>$v){
                $request[$k]=$v;
            }
        }
        if(is_array($_POST)){
            foreach($_POST as $k=>$v){
                $request[$k]=$v;
            }
        }
        $device_id = CommonFn::get_val_if_isset($request,'device_id',"");
        $temp_args=array();
        $sign='';
        if(is_array($request)){
            foreach($request as $_key => $_value) {
                if($_key!='sign'){
                    if(in_array($_key,$sign_args)){
                        $temp_args[$_key]=$_value;
                    }
                }else{
                    $sign = $_value;
                }
            }
        }
        if($sign){
            if($request['app_client_id'] == 2){
                $temp_args['private_key'] = Yii::app()->params['androidPrivateKey'];
            }elseif($request['app_client_id'] == 1){
                $temp_args['private_key'] = Yii::app()->params['iosPrivateKey'];
            }else{
                CommonFn::requestAjax(false,'签名验证失败');
            }

            if(isset($temp_args)&&!empty($temp_args)){
                ksort($temp_args);
            }
            $arg_str='';
            foreach($temp_args as $k=>$v){
                if($arg_str==''){
                    $arg_str .= $k.'='.$v;
                }else{
                    $arg_str .= '&'.$k.'='.$v;
                }
            }
            $new_sign=md5($arg_str);
            if($new_sign!=$sign){
                CommonFn::requestAjax(false,'签名验证失败');
            }
        }else{
            CommonFn::requestAjax(false,'签名验证失败');
        }
        return true;
    }

    public function syncPosition(){
        $position_arr =  json_decode(Yii::app()->request->getParam('position'),true);
        $position[0] = isset($position_arr['lng'])?floatval($position_arr['lng']):0;
        $position[1] = isset($position_arr['lat'])?floatval($position_arr['lat']):0;
        $user_id = Yii::app()->request->getParam('user_id','');
        $city_info =  json_decode(Yii::app()->request->getParam('city_info'),true);
        if($user_id && ($city_info || $position[0])){
            $user_obj = RUser::get(new MongoId($user_id));
            if($user_obj && (!isset($user_obj->position[0]) || !$user_obj->position[0])){
                $user_obj->city_info = $city_info;
                $user_obj->position = $position;
                $user_obj->update(array('city_info','position'),true);
            }
        }
    }



}