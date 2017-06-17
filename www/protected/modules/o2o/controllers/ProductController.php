<?php
/**
 * ProductController o2o商品相关接口
 *
 *  
 *
 */
class  ProductController extends O2oBaseController{
    public function actionList(){
        $page = intval(Yii::app()->getRequest()->getParam("page",1));
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $holiday = explode(',',Service::factory('VariableService')->getVariable('forbidden_order_date'));
        //新手礼包判断
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
                    //if($coupons<3){
                        //新手礼包
                        $coupon_ids = Yii::app()->params['new_user_coupon_ids'];
                        $start_time = time();
                        $end_time = $start_time + 2592000;
                        foreach ($coupon_ids as $coupon_id) {
                            $coupon_id = new MongoId($coupon_id);
                            @Service::factory('CouponService')->giveCoupon($user_id,$coupon_id,$start_time,$end_time);
                        }
                    //}else{
                        $key = 'new_user_coupons_check'.$user_id;
                        $cache->set($key,1,604800);
                    //}
                }else{
                    $key = 'new_user_coupons_check'.$user_id;
                    $cache->set($key,1,604800);
                }
            }
        }
        
        $type = Yii::app()->getRequest()->getParam("type");
        $pagesize = Yii::app()->params['O2oProductListPageSize'];
        if($type){
            $conditions = array(
                                'status'=>array('==',1),
                                'type'=>array('==',$type),
                                //'is_extra'=>array('==',0),
                            );
        }else{
            $conditions = array(
                                'status'=>array('==',1),
                                //'is_extra'=>array('==',0),
                            );
        }
        $order = array(
                        'order'=>'desc',
                        );
        $model = new Product();
        $pagedata = CommonFn::getPagedata($model,$page,$pagesize,$conditions,$order);
        $data['products'] = $pagedata['res'];
        $criteria = new EMongoCriteria();
        $criteria->status('==',1);
        if($type){
            $criteria->type('==',$type);
        }
        //$criteria->is_extra('==',1);
        //$extra_products = $model->findAll($criteria);
        //$extra_products = CommonFn::getRows($extra_products);
        //$extra_products = $model->parse($extra_products);
        //$data['extra_products'] = $extra_products;
        CommonFn::requestAjax(true,'true',$data,200,array('sum_count' => $pagedata['sum_count'],'sum_page'=>$pagedata['sum_page'],'page_size'=>$pagedata['page_size'],'current_page'=>$pagedata['current_page'],'holiday' => $holiday));
    }

    public function actionCommentList(){
        $page = intval(Yii::app()->getRequest()->getParam("page",1));
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $type = Yii::app()->getRequest()->getParam("type");
        $pagesize = Yii::app()->params['O2oCommentListPageSize'];
        if($type){
            $conditions = array(
                                'status'=>array('==',1),
                                'type'=>array('==',intval($type)),
                            );
        }else{
            $conditions = array(
                                'status'=>array('==',1),
                            );
        }
        $order = array(
                        'weight'=>'desc',
						'time' => 'desc'
                        );
        $model = new Comment();
        $pagedata = CommonFn::getPagedata($model,$page,$pagesize,$conditions,$order);
        $data['comments'] = $pagedata['res'];
        CommonFn::requestAjax(true,CommonFn::getMessage('message','operation_success'),$data,200,array('sum_count' => $pagedata['sum_count'],'sum_page'=>$pagedata['sum_page'],'page_size'=>$pagedata['page_size'],'current_page'=>$pagedata['current_page']));
    }

    public function actionComment(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $order_id = Yii::app()->getRequest()->getParam("order_id");
        $content = Yii::app()->getRequest()->getParam("content");
        $pics =  json_decode(Yii::app()->request->getParam('pics'),true);
        $score = intval(Yii::app()->getRequest()->getParam("score"));
        if(!$score || !$order_id || !$content || !$user_id || !CommonFn::isMongoId($user_id) || !CommonFn::isMongoId($order_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        if(mb_strlen(trim($content),'utf-8')<10){
            CommonFn::requestAjax(false,'评价最少十个字哦~~');
        }
        if($score>5 || $score<0){
            $score = 5;
        }
        $user = RUser::get(new MongoId($user_id));
        $order = ROrder::get(new MongoId($order_id));
        if(!$user || !$order || $order->user != $user->_id){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','operation_not_permission'));
        }
        if(isset($order->have_comment) && $order->have_comment == 1){
            CommonFn::requestAjax(false,CommonFn::getMessage('o2o','haved_comment'));
        }
		$type = 0;
        if(!isset($order->type) || $order->type == 0){
            foreach ($order->products as $product) {
                $product_obj = Product::get($product['product']);
                $type = $product_obj->type;
                break;
            }
		} else {
			$type = $order->type;
		}

        $comment = new Comment();
        $comment->content = $content;
        $comment->user = $user->_id;
        $comment->type = intval($type);
        $comment->order = $order->_id;
        $comment->pics = $pics;
        $comment->score = $score;
        $comment->time = time();

        // 保洁师信息处理
        //     2015-12-11
        $technicians = empty($order->technicians) ? null : $order->technicians;
        $comment->technicians = $technicians;

        if($comment->save()) {
            $order->have_comment = 1;
            $order->update(array('have_comment'), true);
            $data = $comment->parseRow($comment);
            $start_time = time();
            $end_time = strtotime(date('Y-m-d', $start_time + 1209600));
            $coupon_ids[] = '56c5914fa84ea0874f8d3820';
            $coupon_ids[] = '56c591b1a84ea0dd4e8d411e';
            $coupon_ids[] = '56c591e5a84ea04d238cc67b';
            $coupon_ids[] = '56c5921ca84ea0b3268bd949';
            Service::factory('CouponService')->giveCoupon($order->user, new MongoId($coupon_ids[mt_rand(0, 3)]), $start_time, $end_time);

            // 保洁师操作
            foreach ($technicians as $technician) {
                if ($technician) {
                    $technicianObj = TechInfo::get($technician);
                    if ($technicianObj && $technicianObj->weixin_userid) {
                        $url_prefix = ENVIRONMENT == 'product' ? 'http://api.yiguanjia.me' : 'http://apitest.yiguanjia.me';
                        $wechat = O2oApp::getWechatObj();
                        $wechat_data = array(
                            'touser' => $technicianObj->weixin_userid,
                            'msgtype' => 'news',
                            'agentid' => '1',
                            'news' => array(
                                'articles' => array(
                                    array(
                                        'title' => '壹橙管家提示-新评价',
                                        'description' => $technicianObj->name . '你好！你刚刚在壹橙管家O2O服务上收到了一条评分为' . $score . '分的订单评价，请点击查看。',
                                        'url' => $url_prefix . '/index.php?r=o2o/myComment/index',
                                    ),
                                ),
                            ),
                        );
                        $wechat->sendMessage($wechat_data);
                    }
                    // 好评数统计
                    if ($score == 5) {
                        $favourable_count = $technicianObj->favourable_count + 1;
                        $technicianObj->favourable_count = $favourable_count;
                        $technicianObj->save();
                    }
                }
                CommonFn::requestAjax(true, CommonFn::getMessage('o2o', 'comment_success'), $data);
            }
        }else{
            CommonFn::requestAjax(false,CommonFn::getMessage('o2o','comment_failt'));
        }
    }

    public function actionQuestion(){
        $user_id = Yii::app()->getRequest()->getParam("user_id");
        $content = Yii::app()->getRequest()->getParam("content");
        $type = Yii::app()->getRequest()->getParam("type");
        if(!$type || !$content || !$user_id || !CommonFn::isMongoId($user_id)){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','params_illegal'));
        }
        $user = RUser::get(new MongoId($user_id));
        if(!$user){
            CommonFn::requestAjax(false,CommonFn::getMessage('message','operation_not_permission'));
        }
        $question = new Question();
        $question->content = $content;
        $question->user = $user->_id;
        $question->type = isset($type)?intval($type):0;
        $question->time = time();
        if($question->save()){
            $data = $question->parseRow($question);
            CommonFn::requestAjax(true,CommonFn::getMessage('o2o','comment_success'),$data);
        }else{
            CommonFn::requestAjax(false,CommonFn::getMessage('o2o','comment_failt'));
        }
    }
}