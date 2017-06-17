<?php
/**
 * ShopController 商城相关api接口
 */
class ShopController extends ApiBaseController{
    public function beforeAction($action){
        $weixin_use = array('addAddress','addressList','delAddress','editAddress');
        if(Yii::app()->getRequest()->getParam("request_from") == 'weixin' && in_array($action->id,$weixin_use)){
            return true;
        }
        return $this->verify();
    }


    /**
     * 新增收货地址
     */
    public function actionAddAddress(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $name = trim(Yii::app()->request->getParam('name',''));
        $mobile = Yii::app()->request->getParam('mobile');
        $position = json_decode(Yii::app()->request->getParam('address_position'),true);
        $address_position[0] = isset($position['lng'])?floatval($position['lng']):floatval(0);
        $address_position[1] = isset($position['lat'])?floatval($position['lat']):floatval(0);
        $phoneReg = Yii::app()->params['phoneReg'];
        if(!preg_match($phoneReg,$mobile)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','phone_illegal'));
        }
        $address =  json_decode(Yii::app()->request->getParam('address'),true);
        if(!isset($address['province']) || empty($address['province'])){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $is_default = intval(Yii::app()->request->getParam('is_default'),0);
        if(!$name||!$mobile||!$address||!$user_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user = CommonFn::apigetObJ($user_id,"ZUser",CommonFn::getMessage('user','id_not_exist'),201);
        $new_address = array('name'=>$name,'mobile'=>$mobile,'address'=>$address,'position' => $address_position,'is_default'=>$is_default,'address_id'=>time()); 
        if($is_default==1){
            foreach ($user->shop_address as $key => $value) {
                if(isset($value['is_default']) && $value['is_default'] == 1){
                    $value['is_default'] = 0;
                }
                $user->shop_address[$key] = $value;
                $user->update(array('shop_address'),true);
            }
        }
        $user->shop_address = $user->shop_address?array_merge($user->shop_address,array($new_address)):array($new_address);
        $result = $user->update(array('shop_address'),true);
        if($result){
            CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$new_address);
        }else{
            CommonFn::requestAjax(false,'操作失败,请稍后再试',array());
        }
    }

    /**
     * 收货地址列表
     */
    public function actionAddressList(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $user = CommonFn::apigetObJ($user_id,"ZUser",CommonFn::getMessage('user','id_not_exist'),201);
        $data = $user->shop_address?$user->shop_address:array();
        $data = array_values($data);
        CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data);
    }

    /**
     * 修改收货地址
     */
    public function actionEditAddress(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $name = trim(Yii::app()->request->getParam('name',''));
        $mobile = Yii::app()->request->getParam('mobile');
        $position = json_decode(Yii::app()->request->getParam('address_position'),true);
        $address_position[0] = isset($position['lng'])?floatval($position['lng']):floatval(0);
        $address_position[1] = isset($position['lat'])?floatval($position['lat']):floatval(0);
        $phoneReg = Yii::app()->params['phoneReg'];
        if(!preg_match($phoneReg,$mobile)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','phone_illegal'));
        }
        $address =  json_decode(Yii::app()->request->getParam('address'),true);
        if(!isset($address['province']) || empty($address['province'])){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $address_id =  Yii::app()->request->getParam('address_id');
        $is_default = intval(Yii::app()->request->getParam('is_default'),0);
        if(!$name||!$mobile||!$address||!$user_id||!$address_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user = CommonFn::apigetObJ($user_id,"ZUser",CommonFn::getMessage('user','id_not_exist'),201);
        if($is_default==1){
            $old_address_list = $user->shop_address?$user->shop_address:array();
            foreach ($old_address_list as $key => $value) {
                if(isset($value['is_default']) && $value['is_default'] == 1){
                    $value['is_default'] = 0;
                }
                $old_address_list[$key] = $value;
            }
            $user->shop_address = $old_address_list;
            $user->update(array('shop_address'),true);
        }
        $address_list = $user->shop_address?$user->shop_address:array();
        foreach ($address_list as $key => $value) {
            if($value['address_id'] == $address_id){
                $value['name'] = $name;
                $value['mobile'] = $mobile;
                $value['address'] = $address;
                $value['is_default'] = $is_default;
                $value['position'] = $address_position;
                $new_address = $value;
                $address_list[$key] = $new_address;
            }
        }
        if(!isset($new_address)){
            CommonFn::requestAjax(false,CommonFn::getMessage('shop','address_not_exist'));
        }
        $user->shop_address = $address_list;
        $result = $user->update(array('shop_address'),true);
        if($result){
            CommonFn::requestAjax(true,'地址修改成功',$new_address);
        }else{
            CommonFn::requestAjax(false,'地址修改失败',array());
        }
    }

    /**
     * 删除收货地址
     */
    public function actionDelAddress(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $address_id =  Yii::app()->request->getParam('address_id');
        if(!$user_id||!$address_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user = CommonFn::apigetObJ($user_id,"ZUser",CommonFn::getMessage('user','id_not_exist'),201);
        $address_list = $user->shop_address?$user->shop_address:array();
        foreach ($address_list as $key => $value) {
            if($value['address_id'] == $address_id){
                $old_address = $value;
                unset($address_list[$key]);
            }
        }
        if(!isset($old_address)){
            CommonFn::requestAjax(false,CommonFn::getMessage('shop','address_not_exist'));
        }
        if(empty($address_list)){
            CommonFn::requestAjax(false,CommonFn::getMessage('shop','address_less_one'));
        }
        $user->shop_address = $address_list;
        $result = $user->update(array('shop_address'),true);
        if($result){
            CommonFn::requestAjax(true,'地址删除成功');
        }else{
            CommonFn::requestAjax(false,'地址删除失败');
        }
    }

    


} 