<?php
/**
 * 后台保洁师管理
 *     2016-03-04
 */
class TechController extends AdminController {

    /**
     * 首页
     */
    public function actionIndex() {
        $status_option = CommonFn::getComboboxData(TechInfo::$status_option, 1, true, 100);
        $scheme_option = CommonFn::getComboboxData(Commision::$scheme_option, 100, true, 100);
        $service_type  = Yii::app()->params['o2o_service'];

        $datePickerStart = date('Y-m-d 00:00');
        $datePickerEnd = date('Y-m-d 00:00', strtotime('+15 day', strtotime('today')));

        $this->render('index', [
            'status_option' => $status_option,
            'scheme_option' => $scheme_option,
            'service_type'  => $service_type,
            'datePickerStart' => $datePickerStart,
            'datePickerEnd' => $datePickerEnd,
        ]);
    }

    /**
     * 列表
     */
    public function actionList() {
        $pageParams = CommonFn::getPageParams();

        $id     = intval(Yii::app()->request->getParam('id', 0));
        $search = Yii::app()->request->getParam('search', '');
        $scheme = intval(Yii::app()->request->getParam('scheme', 100));
        $status = intval(Yii::app()->request->getParam('status', 100));

        $criteria = new EMongoCriteria($pageParams);
        // id筛选
        if ($id) {
            $criteria->_id('==', $id);
        }
        // 状态筛选
        if ($status != 100) {
            $criteria->status('==', $status);
        }
        // 提成方案筛选
        if ($scheme != 100) {
            $criteria->scheme('==', Commision::$scheme_option[$scheme]['alias']);
        }
        // 搜索
        if ($search) {
            // 搜索ID
            if (!preg_match('/\D/', $search)) {
                $criteria->_id('==', intval($search));
            // 搜索姓名或微信ID
            } else {
                $criteria->name('or', new MongoRegex('/'.$search.'/'));
                $criteria->wechat_id('or', new MongoRegex('/'.$search.'/'));
            }
        }

        $cursor = TechInfo::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $parsedRows = TechInfo::model()->parse($rows);
        $total = $cursor->count();

        echo CommonFn::composeDatagridData($parsedRows, $total);
    }

    /**
     * 编辑保洁师基本信息
     */
    public function actionEdit() {
        $_id          = intval(Yii::app()->request->getParam('_id', 0));
        $name         = Yii::app()->request->getParam('name', '');
        $avatar       = Yii::app()->request->getParam('avatar', '');
        $status       = intval(Yii::app()->request->getParam('status', 1));
        $scheme       = intval(Yii::app()->request->getParam('scheme', -1));
        $service_type = Yii::app()->request->getParam('service_type', []);
        $desc         = Yii::app()->request->getParam('desc', '');

        // intval service_type
        foreach ($service_type as &$value) {
            $value = intval($value);
        }

        $tech = TechInfo::get($_id);
        $user = User::get($_id);

        if (!$tech || !$user) {
            CommonFn::requestAjax(false, '保洁师信息不存在');
        }

        // 微信status更新
        if ($status != $tech->status) {
            TechInfo::updateWeixinStatus($_id, $status);
        }

        // TechInfo更新
        $tech->name         = $name;
        $tech->status       = $status;
        $tech->scheme       = Commision::$scheme_option[$scheme]['alias'];
        $tech->avatar       = $avatar;
        $tech->service_type = $service_type;
        $tech->desc         = $desc;

        // user更新
        $user->status = $status;
        $user->name   = $name;

        $success_tech = $tech->save();
        $success_user = $user->save();
        CommonFn::requestAjax($success_tech && $success_user, '', []);
    }

    /**
     * 获取保洁师可预约时间列表
     */
    public function actionGetTechTimeline() {
        $_id = Yii::app()->request->getParam('_id', 0);
        $timeline = FreeTimeRecord::model()->getTechTimeline(intval($_id));

        echo json_encode($timeline);
    }

    /**
     * 修改保洁师可预约时间
     */
    public function actionModifyFreetime() {
        $_id = intval(Yii::app()->request->getParam('_id', 0));
        $new_time_list = Yii::app()->request->getParam('new_time_list', '{}');
        $old_time_list = Yii::app()->request->getParam('old_time_list', '{}');

        if (!TechInfo::get($_id)) {
            CommonFn::requestAjax(false, '保洁师信息不存在', []);
        }

        $new_time_list = empty($new_time_list) ? '{}' : $new_time_list;
        $old_time_list = empty($old_time_list) ? '{}' : $old_time_list;

        $new_list = json_decode($new_time_list, true);
        $old_list = json_decode($old_time_list, true);

        if (empty($new_list)) {
            CommonFn::requestAjax(false, '没有新数据传入', []);
        }

        $need_set = [];
        $need_unset = [];
        foreach ($new_list as $key => $item) {
            if (array_key_exists($key, $old_list)) {
                foreach ($item as $k => $v) {
                    if (intval($v) != intval($old_list[$key][$k])) {
                        $hour = $k == 9 ? '09' : (string)$k;
                        if (intval($v) == 0) {
                            $need_unset[] = strtotime($key.$hour.'00');
                        } else if (intval($v) == 1) {
                            $need_set[] = strtotime($key.$hour.'00');
                        }
                    }
                }
            } else {
                foreach ($item as $k => $v) {
                    if (intval($v) == 1) {
                        $hour = $k == 9 ? '09' : (string)$k;
                        $need_set[] = strtotime($key.$hour.'00');
                    }
                }
            }
        }

        foreach ($old_list as $key => $item) {
            if (!array_key_exists($key, $new_list)) {
                foreach ($item as $k => $v) {
                    if (intval($v) == 1) {
                        $hour = $k == 9 ? '09' : (string)$k;
                        $need_unset[] = strtotime($key.$hour.'00');
                    }
                }
            }
        }

        $need_set = array_unique($need_set);
        $need_unset = array_unique($need_unset);

        foreach ($need_set as $key => $value) {
            FreeTimeRecord::model()->TechsetFreetime($_id, $value);
        }

        foreach ($need_unset as $key => $value) {
            FreeTimeRecord::model()->TechUnsetFreetime($_id, $value);
        }

        CommonFn::requestAjax(true, '', []);
    }

    /**
     * 修改保洁师服务范围
     */
    public function actionModifyCoverage() {
        $_id = intval(Yii::app()->request->getParam('_id', 0));
        $coverage_json = Yii::app()->request->getParam('coverage_json', '[]');

        $tech = TechInfo::get($_id);
        if (!$tech) {
            CommonFn::requestAjax(false, '保洁师信息不存在');
        }

        $business = [];
        $coverage = json_decode($coverage_json, true);
        foreach ($coverage as $key => $item) {
            if (isset($item['business'])) {
                $business[] = $item['business'];
            } else {
                continue;
            }
        }
        $tech->coverage = $coverage;
        $tech->business = $business;

        $success = $tech->save();
        CommonFn::requestAjax($success, '', []);
    }

    /**
     * 修改保洁师微信端信息
     */
    public function actionModifyWeixinInfo() {
        $_id = intval(Yii::app()->request->getParam('_id', 0));
        $name = Yii::app()->request->getParam('name', '');
        $weixin_userid = Yii::app()->request->getParam('weixin_userid', '');
        $mobile = Yii::app()->request->getParam('mobile', '');

        $tech = TechInfo::get($_id);
        if (!$tech) {
            CommonFn::requestAjax(false, '保洁师信息不存在', []);
        }

        // 检查userId是否重复
        if (!$tech->weixin_userid && TechInfo::getByUserid($weixin_userid)) {
            CommonFn::requestAjax(false, '微信ID已存在', []);
        }

        // 检查mobile是否重复
        if ($tech->mobile != $mobile && TechInfo::getByMobile($mobile)) {
            CommonFn::requestAjax(false, '手机号重复', []);
        }

        $option = WechatConfig::getIns()->getLinkOption();
        $secret = WechatConfig::getIns()->getSecret('admin_dev');
        $wechat = new QyWechat($option);

        $weixin_user_data = [
            'userid'     => $weixin_userid,
            'name'       => $name,
            'mobile'     => $mobile,
            'department' => [2],
        ];

        if ($wechat->checkAuth($option['appid'], $secret)) {
            // 检查用户是否存在
            $weixin_userInfo = $wechat->getUserInfo($weixin_userid);
            if ($weixin_userInfo == false) {
                $result = $wechat->createUser($weixin_user_data);
                if ($result['errmsg'] != 'created') {
                    CommonFn::requestAjax(false, '微信验证失败1: '.$result['errmsg'], []);
                }
            } else {
                $result = $wechat->updateUser($weixin_user_data);
                if ($result['errmsg'] != 'updated') {
                    CommonFn::requestAjax(false, '微信验证失败2: '.$result['errmsg'], []);
                }
            }
        } else {
            CommonFn::requestAjax(false, '微信Auth验证失败3', []);
        }

        // 后台信息修改
        $tech->weixin_userid = $weixin_userid;
        $tech->mobile = $mobile;
        $success = $tech->save();
        CommonFn::requestAjax($success, '', []);
    }

    /**
     * 选择保洁师接口
     */
    public function actionSelectTech() {
        $chars = Yii::app()->request->getParam('tech', '');

        $criteria = new EMongoCriteria();
        $criteria->addCond('name', 'or', new MongoRegex('/'.$chars.'/'));
        $criteria->addCond('weixin_userid', 'or', new MongoRegex('/'.$chars.'/'));
        $criteria->status('==', 1);

        $cursor = TechInfo::model()->findAll($criteria);

        $rows = CommonFn::getRowsFromCursor($cursor);
        $index = 0;
        $data = [];
        foreach ($rows as $key => $item) {
            $data[] = [
                'id' => $index,
                'data' => $item['name'],
                'description' => $item['weixin_userid'],
                'tech_id' => $item['_id'],
            ];
            $index++;
        }

        if (!$data) {
            $data = [
                'id'          => 0,
                'data'        => '',
                'description' => '',
                'tech_id'     => -1,
            ];
        }

        echo json_encode($data);
    }

    /**
     * 复制保洁师信息到新表
     */
    public function actionCopyTech() {
        set_time_limit(0);
        $sign = Yii::app()->request->getParam('sign', '');
        if ($sign != 'mayThe4thBwithU') {
            die('wrong sign');
        }

        $mongoDbAuthManager = new CMongoDbAuthManager();
        $tech_ids = $mongoDbAuthManager->getAuthUser('保洁师');

        $criteria = new EMongoCriteria();
        $criteria->_id('in', $tech_ids);
        $cursor = User::model()->findAll($criteria);
        foreach ($cursor as $key => $item) {
            $tech = TechInfo::get($item->_id);
            if (!$tech) {
                $tech = new TechInfo();
                $tech->_id = $item->_id;
            }
            $tech->name = $item->name;
            $tech->desc = '';
            $tech->avatar = Yii::app()->params['defaultUserAvatar'];
            $tech->status = $item->status;
            $tech->scheme = isset($item->scheme) ? $item->scheme : 'no_scheme';
            $tech->weixin_userid = isset($item->userid) ? $item->userid : '';
            $tech->mobile = $item->wx_info['mobile'];
            $tech->service_type = isset($item->service_type) ? $item->service_type : [];
            $tech->coverage = [];
            $tech->business = [];
            // $tech->coverage = isset($item->coverage) ? $item->coverage : [];
            // $tech->weixin_info = $item->wx_info;

            // 保洁师接单数（状态为已完成的订单）
            $criteria = new EMongoCriteria();
            $criteria->technician('==', $tech->_id);
            $criteria->status('==', 6);
            $rOrders = ROrder::model()->findAll($criteria);
            $tech->order_count = $rOrders->count();

            // 保洁师好评数（分数为5的评价）
            $criteria = new EMongoCriteria();
            $criteria->score('==', 5);
            $criteria->status('==', 1);
            $criteria->technician('==', $tech->_id);
            $comments = Comment::model()->findAll($criteria);
            $tech->favourable_count = $comments->count();

            // 微信端状态修改
            TechInfo::updateWeixinStatus($tech->_id, $tech->status);

            $tech->save();
        }
        echo 'done';
    }

    /**
     * 保洁师统计同步
     */
    public function actionCopyTechCount() {
        set_time_limit(0);

        $techs = TechInfo::model()->findAll();
        foreach ($techs as $key => $item) {
            // 接单数统计
            $criteria = new EMongoCriteria();
            $criteria->technician('==', $item->_id);
            $criteria->status('==', 6);
            $orders = ROrder::model()->findAll($criteria);
            $item->order_count = $orders->count();

            // 保洁师好评数
            $criteria = new EMongoCriteria();
            $criteria->score('==', 5);
            $criteria->status('==', 1);
            $criteria->technician('==', $item->_id);
            $comments = Comment::model()->findAll($criteria);
            $item->favourable_count = $comments->count();

            $item->save();
        }

        echo 'done';
    }

}