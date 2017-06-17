<?php
/**
 * UserController 用户相关api接口
 */
class UserController extends ApiBaseController{
    public function beforeAction($action){
        $weixin_use = array('info','join','yuyue');
        if(Yii::app()->getRequest()->getParam("request_from") == 'weixin' && in_array($action->id,$weixin_use)){
            return true;
        }
        return $this->verify();
    }
    //用户邮箱注册
    public function actionRegister(){
        $userAr  = new RUser();
        $data = array();
        $data['user_name'] = trim(Yii::app()->request->getParam('user_name',''));
        $data['password'] = Yii::app()->request->getParam('password');
        $data['email'] = Yii::app()->request->getParam('email','');
        $data['avatar'] = Yii::app()->request->getParam('avatar','');
        $data['city_info'] =  json_decode(Yii::app()->request->getParam('city_info'),true);
        $position = json_decode(Yii::app()->request->getParam('position'),true);
        $data['position'][0] = isset($position['lng'])?floatval($position['lng']):0;
        $data['position'][1] = isset($position['lat'])?floatval($position['lat']):0;
        //防止city_info出现非法数据
        if(!isset($data['city_info']['province'])){
            $data['city_info']['province'] = '';
            $data['city_info']['city'] = '';
            $data['city_info']['area'] = '';
        }elseif(!isset($data['city_info']['city'])){
            $data['city_info']['city'] = '';
            $data['city_info']['area'] = '';
        }elseif(!isset($data['city_info']['area'])){
            $data['city_info']['area'] = '';
        }

        $data['app_client_id'] = intval(Yii::app()->request->getParam('app_client_id'));
        $data['device_id'] = Yii::app()->request->getParam('device_id');
        $data['channel'] = Yii::app()->request->getParam('channel');
        $data['openid'] = Yii::app()->request->getParam('openid');
        $data['phone_type'] = Yii::app()->request->getParam('phone_type');
        $data['os_version'] = Yii::app()->request->getParam('os_version');
        $data['register_time'] = time();
        $data['last_visit_time'] = time();

        if(!preg_match(Yii::app()->params['emailReg'], $data['email'])){
            CommonFn::requestAjax(false,CommonFn::getMessage('user','email_Illegal'));
        }
        $z_user = new ZUser();
        $z_user->validate_user_name($data['user_name']);
        if(strlen($data['password'])<6 || strlen($data['password'])>20){
            CommonFn::requestAjax(false, CommonFn::getMessage('user','password_length_6_20'));
        }
        $userAr->attributes = $data;
        $criteria = new EMongoCriteria();
        $criteria->email('==',$userAr->email);
        $olduser = RUser::model()->find($criteria);
        if($olduser){
            CommonFn::requestAjax(false,CommonFn::getMessage('user','email_already_registered'));
        }
        $criteria = new EMongoCriteria();
        $criteria->user_name('==',$userAr->user_name);
        $olduser = RUser::model()->find($criteria);
        if($olduser){
            CommonFn::requestAjax(false,CommonFn::getMessage('user','username_already_registered'));
        }
        $userAr->password = md5($userAr->password);
        //用户注册后默认关注几个圈子
        $z_group = new ZGroup();
        $userAr->groups = $z_group->get_default_fllow_group();
        if($userAr->save()){
            $data = RUser::model()->parseRow($userAr->attributes);
            CommonFn::requestAjax(true,CommonFn::getMessage('user','register_success'),$data);
        }else{
            CommonFn::requestAjax(false,CommonFn::getMessage('user','register_faild'));
        }
    }

    //微信用户的登录
    public function actionWeixinLogin(){
        $data = array();
        $data['user_name'] = mb_strtolower(Yii::app()->request->getParam('user_name',''));
        $data['avatar'] = Yii::app()->request->getParam('avatar','');
        $data['city_info'] =  json_decode(Yii::app()->request->getParam('city_info'),true);
        $position = json_decode(Yii::app()->request->getParam('position'),true);

        //防止city_info出现非法数据
        if(!isset($data['city_info']['province']) || $data['city_info']['province'] == '未知'){
            $data['city_info']['province'] = '';
            $data['city_info']['city'] = '';
            $data['city_info']['area'] = '';
        }elseif(!isset($data['city_info']['city'])){
            $data['city_info']['city'] = '';
            $data['city_info']['area'] = '';
        }elseif(!isset($data['city_info']['area'])){
            $data['city_info']['area'] = '';
        }

        $data['position'][0] = isset($position['lng'])?floatval($position['lng']):0;
        $data['position'][1] = isset($position['lat'])?floatval($position['lat']):0;

        $data['app_client_id'] = intval(Yii::app()->request->getParam('app_client_id'));
        $data['device_id'] = Yii::app()->request->getParam('device_id');
        $data['channel'] = Yii::app()->request->getParam('channel');
        $data['phone_type'] = Yii::app()->request->getParam('phone_type');
        if($data['channel'] == 'appstore'){
            $data['phone_type'] = Yii::app()->request->getParam('device_model');
        }
        $data['app_version'] = Yii::app()->request->getParam('app_version');

        $data['os_version'] = Yii::app()->request->getParam('os_version');

        $data['openid'] = Yii::app()->request->getParam('openid','');
        $data['unionid'] = Yii::app()->request->getParam('unionid','');

        $data['sex'] = intval(Yii::app()->request->getParam('sex'));

        $data['register_time'] = time();
        $data['last_visit_time'] = time();

        

        if ($data['openid'] == ''){
            CommonFn::requestAjax(false, CommonFn::getMessage('user','weixin_login_faild'));
        }

        $criteria = new EMongoCriteria();
        if(isset($data['unionid']) && !empty($data['unionid'])){
            $criteria->unionid('==',$data['unionid']);//unionid保证账号统一
        }else{
            $criteria->openid('==',$data['openid']);
        }
        $user = RUser::model()->find($criteria);
        if($user){
            $user->os_version = $data['os_version'];
            $user->device_id = $data['device_id'];
            $user->app_client_id = $data['app_client_id'];
            $paraArr = array('os_version','device_id','app_client_id');
            if(!empty($data['position'])&&!empty($data['position'][0])&&!empty($data['position'][1])){
                $user->position = $data['position'];
                $paraArr[] = 'position';
            }
            if(!empty($data['city_info']['province'])){
                $user->city_info = $data['city_info'];
                $paraArr[] = 'city_info';
            }
            $user->update($paraArr,true);
            $data = RUser::model()->parseRow($user->attributes);
            $z_action_cat = new ZActionCat();
            $news_count = $z_action_cat->getUnReadNews($user->_id);
            $data['news'] = $news_count;
            CommonFn::requestAjax(true,CommonFn::getMessage('user','login_success'),$data);
        }else{
            $z_user = new ZUser();
            $z_user->validate_user_name($data['user_name']);
            $userAr  = new RUser();
            $userAr->user_name = $data['user_name'];
            $userAr->avatar = $data['avatar'];
            $userAr->city_info = $data['city_info'];
            $userAr->position = $data['position'];
            $userAr->app_client_id = $data['app_client_id'];
            $userAr->device_id = $data['device_id'];
            $userAr->phone_type = $data['phone_type'];
            $userAr->app_version = $data['app_version'];
            $userAr->os_version = $data['os_version'];
            $userAr->channel = $data['channel'];
            $userAr->openid = $data['openid'];
            $userAr->unionid = $data['unionid'];
            $userAr->sex = $data['sex']?$data['sex']:3;
            $userAr->register_time = $data['register_time'];
            $userAr->last_visit_time = $data['last_visit_time'];
            try {
                $saveResult = $userAr->save();
            }catch(Exception $e){
                $userAr->user_name = 'wz_'.dechex(time());
                $saveResult = $userAr->save();
            }

            if($saveResult){
                $z_group = new ZGroup();
                $default_groups = $z_group->get_default_fllow_group();
                $userAr->groups = $default_groups;
                $userAr->update(array('groups'),true);
                $list = new ARedisList('after_user_reg');
                $user_id = (string)$userAr->_id;
                $list->push($user_id);
                $data = RUser::model()->parseRow($userAr->attributes);
                $news = [
                    'like'=>0,
                    'message'=>0,
                    'reply'=>0,
                    'notice'=>0,
                    'order'=>0,
                    'follow'=>0,
                    'new_topic'=>0,
                    'new_card'=>0,
                    'total'=>0
                ];
                $data['news'] = $news;
                CommonFn::requestAjax(true,CommonFn::getMessage('user','register_success'),$data,200,array('is_new'=>1));
            }else{
                CommonFn::requestAjax(false,CommonFn::getMessage('user','register_faild'));
            }
        }
    }

    //用户登陆
    public function actionLogin(){
        $password = md5(Yii::app()->request->getParam('password'),'');
        $email = preg_replace('/\0/','',Yii::app()->request->getParam('email',''));
        $email = str_replace(' ','',$email);
        if($password&&$email){
            $criteria = new EMongoCriteria();
            $criteria->email = new MongoRegex('/' . $email . '/i');

            try {
                $userAr = RUser::model()->find($criteria);
            } catch (Exception $e) {
                CommonFn::requestAjax(false,CommonFn::getMessage('user','id_not_exist'));
            }
            if(!$userAr){
                CommonFn::requestAjax(false,CommonFn::getMessage('user','id_not_exist'));
            }
            if($password == $userAr->password){
                $userAr->last_visit_time = time();
                $userAr->update(array('last_visit_time'),true);
                $z_action_cat = new ZActionCat();
                $news_count = $z_action_cat->getUnReadNews($userAr->_id);
                $data = RUser::model()->parseRow($userAr->attributes);
                $data['news'] = $news_count;
                CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data);
            }else{
                CommonFn::requestAjax(false,CommonFn::getMessage('user','username_or_password_error'));
            }
        }else{
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_miss'));
        }
    }

    //用户登陆前验证
    public function actionValidate(){
        $email = Yii::app()->request->getParam('email');
        if(!$email){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_miss'));
        }
        $criteria = new EMongoCriteria();
        if(preg_match(Yii::app()->params['emailReg'], $email)){
            $criteria->email('==',$email);
        }else{
            $criteria->user_name('==',$email);
        }
        $userAr = RUser::model()->find($criteria);
        if($userAr){
            CommonFn::requestAjax(false,CommonFn::getMessage('user','email_already_registered'));
        }else{
            CommonFn::requestAjax(true,'');
        }
    }


    //用户信息
    public function actionInfo(){
        if(Yii::app()->request->getParam('app_client_id') == 2){
            $this->check_version();
        }
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $uid = Yii::app()->getRequest()->getParam("to_user_id");
        $user_name = Yii::app()->getRequest()->getParam("user_name");
        if($user_name){
            $criteria = new EMongoCriteria();
            $criteria->user_name('==',$user_name);
            $res = RUser::model()->find($criteria);
            if(!$res){
                CommonFn::requestAjax(false,CommonFn::getMessage('user','user_not_exist'),204);
            }else{
                $uid = $res->_id;
            }
        }
        $page = intval(Yii::app()->getRequest()->getParam("page",1));
        if(empty($page)){
            $page = 1;
        }
        $notopic = Yii::app()->getRequest()->getParam("notopic");
        if(empty($user_id) && empty($uid)){
            CommonFn::requestAjax(false,CommonFn::getMessage('user','id_not_empty'),201);
        }
        $add_score = $this->today_first_login($user_id);
        if($user_id){
            $id = $user_id;
        }
        $model = new RUser();
        if($uid){
            if($user_id){
                $user_node = new UserNodeRecord($user_id);
                $relation = $user_node->relation($uid);
            }
            $id = $uid;
        }
        $user = CommonFn::apigetObJ($id,"ZUser",CommonFn::getMessage('user','id_not_exist'),201);
        $user_data = $model->parseRow($user->attributes,array(),true);

        $user_data['relation'] = isset($relation)?$relation:0;
        $criteria = new EMongoCriteria();
        $criteria->user('==',$user->_id);
        $criteria->status("==",1);
        $criteria->limit(3)->sort('time',EMongoCriteria::SORT_DESC);
        $model = new Topic();
        $cursor = $model->findAll($criteria);
        $rows = CommonFn::getRows($cursor);
        $topics = $model->parse($rows);
        $feed = array();
        if($topics){
            $feed['pics'] = array();
            foreach ($topics as $topic) {
                $feed['pics'] = array_merge($feed['pics'],$topic['pics']);
                $feed['pics'] = array_slice($feed['pics'],0,3);
            }
            if($feed['pics']){
                $feed['type'] = 'pics';
                $user_data['feed'] = $feed;
            }elseif($topics[0]['content']){
                $feed['type'] = 'text';
                $feed['content'] = $topics[0]['content'];
                $user_data['feed'] = $feed;
            }else{
                $feed['type'] = 'text';
                $feed['content'] = '';
                $user_data['feed'] = $feed;
            }
        }else{
            $feed['type'] = 'text';
            $feed['content'] = '';
            $user_data['feed'] = $feed;
        }
        $z_action_cat = new ZActionCat();
        $news_count = $z_action_cat->getUnReadNews($user->_id);
        $user_data['news'] = $news_count;
        $data['user'] = $user_data;
        if(empty($notopic)){
            $conditions = array(
                'user'=>array('==',$user->_id),
                'status'=>array('==',1)
            );
            $order = array(
                'time'=>'desc',
            );
            $model = new Topic();
            $pagedata = CommonFn::getPagedata($model,$page,20,$conditions,$order);
            $user_topics = $pagedata['res'];
            if(!empty($user_id)){
                foreach ($user_topics as $key => $topic) {
                    $z_like = new ZLike();
                    $like = $z_like->getLikeByLikeObj($user_id,$topic['id']);
                    if(empty($like)){
                        $user_topics[$key]['is_liked'] = false;
                    }else{
                        $user_topics[$key]['is_liked'] = true;
                    }
                }
                if(Yii::app()->getRequest()->getParam("page_size",0)==1){
                    if(isset($user_topics[0]['pics'])&&count($user_topics[0]['pics'])<3){
                        $criteria = new EMongoCriteria();
                        $criteria->user('==',$user->_id);
                        $criteria->status("==",1);
                        $criteria->limit(2)->sort('time',EMongoCriteria::SORT_DESC)->offset(1);
                        $model = new Topic();
                        $cursor = $model->findAll($criteria);
                        $rows = CommonFn::getRows($cursor);
                        $topics = $model->parse($rows);
                        foreach ($topics as $topic) {
                            $user_topics[0]['pics'] = array_merge($user_topics[0]['pics'],$topic['pics']);
                            $user_topics[0]['pics'] = array_slice($user_topics[0]['pics'],0,3);
                        }
                    }
                }
            }
            $data['topic_list'] = $user_topics;
        }
        if($add_score['status']){
            $score_info['score_change'] = $add_score['score'];
            $score_info['current_score'] = $add_score['current_score'];
            $score_info['score_type'] = '签到';
            if(isset($pagedata)){
                CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data,303,array_merge($score_info,array('sum_count' => $pagedata['sum_count'],'sum_page'=>$pagedata['sum_page'],'page_size'=>$pagedata['page_size'],'current_page'=>$pagedata['current_page'])));
            }else{
                CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data,303,$score_info);
            }
        }else{
            if(isset($pagedata)){
                CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data,200,array('sum_count' => $pagedata['sum_count'],'sum_page'=>$pagedata['sum_page'],'page_size'=>$pagedata['page_size'],'current_page'=>$pagedata['current_page']));
            }else{
                CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data);
            }
        }
    }

    

    //修改用户资料
    public function actionEdit(){
        $data['_id'] = Yii::app()->getRequest()->getParam('user_id');
        if(!CommonFn::isMongoId($data['_id'])){
            CommonFn::requestAjax(false,CommonFn::getMessage('user','id_not_exist'),201);
        }
        $model = new RUser();
        $user = CommonFn::apigetObJ($data['_id'],"ZUser",CommonFn::getMessage('user','id_not_exist'),201);

        //需要进行修改的数据内容
        $data['avatar'] = Yii::app()->getRequest()->getParam('avatar','');
        $data['mobile'] = Yii::app()->getRequest()->getParam('mobile','');
        $data['sex'] =  intval(Yii::app()->getRequest()->getParam('sex'));
        $data['user_name']= Yii::app()->getRequest()->getParam('user_name','');
        $data['city_info'] =  json_decode(Yii::app()->request->getParam('city_info'),true);
        //防止city_info出现非法数据
        if(!isset($data['city_info']['province'])){
            $data['city_info']['province'] = '';
            $data['city_info']['city'] = '';
            $data['city_info']['area'] = '';
        }elseif(!isset($data['city_info']['city'])){
            $data['city_info']['city'] = '';
            $data['city_info']['area'] = '';
        }elseif(!isset($data['city_info']['area'])){
            $data['city_info']['area'] = '';
        }
        if($data['avatar']){
            if(!CommonFn::checkPicFormat($data['avatar'])){
                CommonFn::requestAjax(false,CommonFn::getMessage('user','user_avatar_illegal'));
            }
        }
        if($user->certify_status == 1 && $data['user_name'] &&  $user->user_name != $data['user_name']){
            CommonFn::requestAjax(false,'你已通过认证，不允许修改昵称');
        }
        //过滤user_id ，检测本接口所需参数是否完整
        $item_count = 0;
        unset($data['_id']);
        //用户名检测
        if(isset($data['user_name']) && $data['user_name']){
            if(mb_strlen($data['user_name'],'utf-8')<2||mb_strlen($data['user_name'],'utf-8')>16){
                CommonFn::requestAjax(false,CommonFn::getMessage('user','username_length_illegal'));
            }
            $z_user = new ZUser();
            $z_user->validate_user_name($data['user_name']);
            $u_criteria = new EMongoCriteria();
            $u_criteria->user_name('==',$data['user_name']);
            $olduser = RUser::model()->find($u_criteria);
            if($olduser&&$olduser->_id!=$user->_id){
                CommonFn::requestAjax(false,CommonFn::getMessage('user','username_already_registered'));
            }
        }elseif(empty($data['user_name'])){
            $data['user_name'] = $user->user_name;
        }
        foreach($data as $key => $val){
            if(!empty($val)){
                if($key=='city_info' && empty($val['province'])){
                    continue;
                }
                $item_count++;
                $user->{$key} = $val;
            }
        }
        //更新数据
        if($item_count){
            if($user->save(true)){
                $data = $user->parseRow($user->attributes,array(),true);
                CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data);
            }else{
                CommonFn::requestAjax(false,CommonFn::getMessage('message','operation_faild'));
            }
        }else{
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
    }

    //加入我们
    public function actionJoin(){
        $name              = Yii::app()->request->getParam('name', '');
        $city              = Yii::app()->request->getParam('city', '');
        $id_num              = Yii::app()->request->getParam('id_num', '');
        $mobile              = Yii::app()->request->getParam('mobile', '');
        $invitor              = Yii::app()->request->getParam('invitor', '');
        $contact_name              = Yii::app()->request->getParam('contact_name', '');
        $contact_phone              = Yii::app()->request->getParam('contact_phone', '');
        $img_upper_body    = Yii::app()->request->getParam('img_upper_body','');
        $img_handheld_card = Yii::app()->request->getParam('img_handheld_card','');
        $img_card_front    = Yii::app()->request->getParam('img_card_front','');
        $img_card_back     = Yii::app()->request->getParam('img_card_back','');

        if (!$name || !$city|| !$id_num|| !$mobile|| !$invitor|| !$contact_name|| !$contact_phone|| !$img_upper_body|| !$img_handheld_card|| !$img_card_front|| !$img_card_back) {
            CommonFn::requestAjax(false, '信息要填写完整哦');
        }

        $tech = new Tech();
        // Tech更新
        $tech->name              = $name;
        $tech->city              = $city;
        $tech->id_num              = $id_num;
        $tech->mobile              = $mobile;
        $tech->invitor              = $invitor;
        $tech->contact_name              = $contact_name;
        $tech->contact_phone              = $contact_phone;
        $tech->status            = 0;
        $tech->img_upper_body    = $img_upper_body;
        $tech->img_handheld_card = $img_handheld_card;
        $tech->img_card_front    = $img_card_front;
        $tech->img_card_back     = $img_card_back;


        $success_tech = $tech->save();
        CommonFn::requestAjax($success_tech, '', []);
    }

    public function actionYuyue(){
        $name              = Yii::app()->request->getParam('name', '');
        $city              = Yii::app()->request->getParam('city', '');
        $mobile              = Yii::app()->request->getParam('mobile', '');
        $type              = Yii::app()->request->getParam('type', '');
        $desc              = Yii::app()->request->getParam('desc', '');
        $address              = Yii::app()->request->getParam('address', '');
        $gender              = Yii::app()->request->getParam('gender', '');
        
        if (!$name || !$city|| !$mobile|| !$type|| !$desc|| !$address|| !$gender) {
            CommonFn::requestAjax(false, '信息要填写完整哦');
        }

        $yuyue = new Yuyue();
        // Tech更新
        $yuyue->name              = $name;
        $yuyue->city              = $city;
        $yuyue->mobile              = $mobile;
        $yuyue->status            = 0;
        $yuyue->type            = $type;
        $yuyue->desc            = $desc;
        $yuyue->address            = $address;
        $yuyue->gender            = $gender;

        $success = $yuyue->save();
        CommonFn::requestAjax($success, '', []);
    }
}