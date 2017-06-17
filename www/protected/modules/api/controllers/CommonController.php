<?php
/**
 * 公用API
 */
class CommonController extends ApiBaseController {
    public function beforeAction($action){
        $weixin_use = array('staticsource','getsubcity', 'getallcity');
        if(in_array(strtolower($action->id),$weixin_use)){
            return true;
        }
        return $this->verify();
    }


    //获取全部的城市信息
    public function actionGetAllCity(){
        $criteria = new EMongoCriteria();
        $criteria->sort('parent_province_id', EMongoCriteria::SORT_ASC);
        $criteria->sort('parent_city_id', EMongoCriteria::SORT_ASC);
        $cursor = CityLib::model()->findAll($criteria);
        $res = CommonFn::getRowsFromCursor($cursor);
        $province = array();
        $city = array();
        $area = array();
        $keyMap = array();
        foreach ($res as $item) {
            $keyMap[$item['_id']] = $item['name'];
        }
        foreach ($res as $item) {
            $parent = $item['parent_area_id'];
            $level = 4;
            if ($parent == 0) {
                $parent = $item['parent_city_id'];
                $level = 3;
            }
            if ($parent == 0) {
                $parent = $item['parent_province_id'];
                $level = 2;
            }
            if ($parent == 0) {
                $level = 1;
            }
            if ($level == 1) {
                $province[] = $item['name'];
            } else if ($level == 2) {
                $city[$keyMap[$item['parent_province_id']]][] = $item['name'];
            } else if ($level == 3) {
                $area[$keyMap[$item['parent_city_id']]][] = $item['name'];
            }
        }
        $data = array('p' => $province, 'c' => $city, 'a' => $area);

        CommonFn::requestAjax(true, CommonFn::getMessage('message', 'operation_success'), $data, 200);
    }

    //获取城市级联信息
    public function actionGetSubCity(){
        $address_info =  json_decode(Yii::app()->request->getParam('address_info'),true);
        if(!isset($address_info['province']) || $address_info['province'] == '未知'){
            $address_info = array();
        }
        $province = array();
        $city = array();
        $area = array();
        $z_citylib = new ZCityLib();
        if(isset($address_info['province'])&&$address_info['province']){
            $province = $z_citylib->getSubCity(1);
            foreach ($province as $key => $value) {
                if($value['name'] == $address_info['province']){
                    $province[$key]['is_selected'] = 1;
                    $city = $z_citylib->getSubCity($value['city_code']);
                    foreach ($city as $sub_key => $sub_value) {
                        if(isset($address_info['city']) && $sub_value['name'] == $address_info['city']){
                            $have_selected = true;
                            $city[$sub_key]['is_selected'] = 1;
                            $area = $z_citylib->getSubCity($sub_value['city_code']);
                            foreach ($area as $grand_key => $grand_value) {
                                if($grand_value['name'] == $address_info['area']){
                                    $area[$grand_key]['is_selected'] = 1;
                                }else{
                                    $area[$grand_key]['is_selected'] = 0; 
                                }
                            }
                        }else{
                            $city[$sub_key]['is_selected'] = 0;
                        }
                    }
                    if(!isset($have_selected)){
                        $city[0]['is_selected'] = 1;
                        $area = $z_citylib->getSubCity($city[0]['city_code']);
                        foreach ($area as $grand_key => $grand_value) {
                            if($grand_key == 0){
                                $area[$grand_key]['is_selected'] = 1;
                            }else{
                                $area[$grand_key]['is_selected'] = 0; 
                            }
                        }
                    }
                }else{
                    $province[$key]['is_selected'] = 0;
                }
            }
        }else{
            $province = $z_citylib->getSubCity(1);
            foreach ($province as $key => $value) {
                if($value['name'] == '上海市'){
                    $province[$key]['is_selected'] = 1;
                }else{
                    $province[$key]['is_selected'] = 0;
                }
            }
            foreach ($province as $key => $value) {
                if($value['name'] == '上海市'){
                    $city = $z_citylib->getSubCity($value['city_code']);
                    foreach ($city as $sub_key => $sub_value) {
                        if($sub_value['name'] == '上海市'){
                            $city[$sub_key]['is_selected'] = 1;
                        }else{
                            $city[$sub_key]['is_selected'] = 0;
                        }
                    }
                    $area = $z_citylib->getSubCity($city[0]['city_code']);
                    foreach ($area as $sub_key => $sub_value) {
                        if($sub_value['name'] == '上海市'){
                            $area[$sub_key]['is_selected'] = 1;
                        }else{
                            $area[$sub_key]['is_selected'] = 0;
                        }
                    }
                }
            }
        }
        //容错处理，防止客户端上传错误城市
        if(empty($province)){
            $province = $z_citylib->getSubCity(1);
            foreach ($province as $key => $value) {
                if($value['name'] == '上海市'){
                    $province[$key]['is_selected'] = 1;
                }else{
                    $province[$key]['is_selected'] = 0;
                }
            }
        }
        if(empty($city)){
            foreach ($province as $key => $value) {
                if($value['is_selected'] == 1){
                    $temp_code = $value['city_code'];
                }
            }
            $city = $z_citylib->getSubCity($temp_code);
            if(empty($city)){
                $temp['name'] = ''; 
                $city[] = $temp;
            }
            $city[0]['is_selected'] = 1;
        }
        if(empty($area)){
            foreach ($city as $key => $value) {
                if($value['is_selected'] == 1){
                    $temp_code = $value['city_code'];
                }
            }
            $area = $z_citylib->getSubCity($temp_code);
            if(empty($area)){
                $temp['name'] = ''; 
                $area[] = $temp;
            }
            $area[0]['is_selected'] = 1;
        }
        foreach ($province as $key => $value) {
            unset($province[$key]['city_code']);
        }
        foreach ($city as $key => $value) {
            unset($city[$key]['city_code']);
        }
        foreach ($area as $key => $value) {
            unset($area[$key]['city_code']);
        }
        $data['provinces'] = $province;
        $data['citys'] = $city;
        $data['areas'] = $area;      
        CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data,200);
    }

    public function actionPay(){
        $pay_channel = Yii::app()->getRequest()->getParam("pay_channel");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        $callback = Yii::app()->getRequest()->getParam("callback");
        $amount = 1;
        $result = Service::factory('PayService')->Pay($pay_channel,$amount);
        if($callback){
            echo $callback."($result)";
            die();
        }
        echo $result;
    }

    public function actionRetrieve(){
        $charge_id = Yii::app()->getRequest()->getParam("charge_id");
        $result = Service::factory('PayService')->retrieve($charge_id);
        echo $result;
    }

    

    public function actionGetUrl(){
        $url_id = Yii::app()->getRequest()->getParam("url_id");
        $data['url'] = Service::factory('VariableService')->getVariable($url_id);
        $success = empty($data) ? false : true;
        $message = $success ? '' : 'URL不存在';
        CommonFn::requestAjax($success, $message, $data);
    }

    

    public function actionCustomeParam() {
        $user_id   = Yii::app()->request->getParam('user_id', '');
        $device_id = Yii::app()->request->getParam('device_id');
        $data['new_user_coupons_value'] = Yii::app()->params['new_user_coupons_value']?Yii::app()->params['new_user_coupons_value']:0;
        if($user_id){
            $cache = new ARedisCache();
            $key = 'new_user_coupons_check'.$user_id;
            $new_user_coupons_check = $cache->get($key);
            if(!$new_user_coupons_check){
                if(CommonFn::isMongoId($user_id)){
                    $user_id = new MongoId($user_id);
                    $criteria = new EMongoCriteria();
                    $criteria->user('==',$user_id);
                    $coupons = UserCoupon::model()->count($criteria);
                    if($coupons<1){
                        $data['get_user_coupons'] = 1;
                    }else{
                        $data['get_user_coupons'] = 0;
                    }
                }else{
                    $data['get_user_coupons'] = 0;
                }
            }else{
                $data['get_user_coupons'] = 0;
            }
        }elseif ($device_id) {
            $cache = new ARedisCache();
            $key = 'new_user_coupons_check'.$device_id;
            $new_user_coupons_check = $cache->get($key);
            if(!$new_user_coupons_check){
                $criteria = new EMongoCriteria();
                $criteria->user_device_id('==',$device_id);
                $coupons = UserCoupon::model()->count($criteria);
                if($coupons<1){
                    $data['get_user_coupons'] = 1;
                }else{
                    $data['get_user_coupons'] = 0;
                }
            }
        }else{
            $data['get_user_coupons'] = 0;
        }
        CommonFn::requestAjax(true,'success', $data);
    }

    public function actionStaticSource() {
        $key = Yii::app()->request->getParam('key');
        $static = StaticSource::getByKey($key);
        if (!$static) {
            CommonFn::requestAjax(false, '资源不存在', []);
        } else {
            CommonFn::requestAjax(true, '', [
                'id' => (string)$static->_id,
                'key' => $static->key,
                'title' => $static->title? $static->title : '',
                'content' => $static->content,
            ]);
        }
    }
}
