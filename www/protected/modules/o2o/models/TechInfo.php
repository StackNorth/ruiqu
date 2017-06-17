<?php
class TechInfo extends MongoAr {

    public $_id;//使用后台用户id作为保洁师信息表主键
    public $name;//姓名
    public $desc = '';//描述
    public $avatar;//头像
    public $favourable_count=0;//好评数
    public $order_count=0;//服务次数
    public $coverage = [];//服务商圈镜像字段
    public $business = [];//服务商圈
    public $status;//状态，同User表
    public $weixin_userid = '';//微信企业号内ID
    public $mobile = '';//手机号
    public $weixin_info = [];//微信企业号成员信息
    public $scheme = 'no_scheme';//提成方案
    public $service_type = [];//服务类型

    public static $status_option = [
        1  => ['name' => '正常',   'wx' => 1],
        0  => ['name' => '待审核', 'wx' => 0],
        -1 => ['name' => '已删除', 'wx' => 0],
    ];

    public function __construct($scenario='insert') {
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public static function get($_id) {
        $criteria = new EMongoCriteria();
        $criteria->_id('==', intval($_id));
        $tech = self::model()->find($criteria);
        return $tech;
    }

    public static function getByUserid($userid) {
        $criteria = new EMongoCriteria();
        $criteria->weixin_userid = $userid;
        $tech = self::model()->find($criteria);
        return $tech;
    }

    public static function getByMobile($mobile) {
        $criteria = new EMongoCriteria();
        $criteria->mobile = $mobile;
        $tech = self::model()->find($criteria);
        return $tech;
    }

    public static function updateWeixinStatus($_id, $status) {
        $tech = self::get($_id);
        if ($tech) {
            $weixin_userid = $tech->weixin_userid;
            if ($weixin_userid) {
                $weixin_enable = self::$status_option[$status]['wx'];
                $user_data = [
                    'userid' => $weixin_userid,
                    'enable' => $weixin_enable,
                ];
                $option = WechatConfig::getIns()->getLinkOption();
                $secret = WechatConfig::getIns()->getSecret('admin_dev');
                $wechat = new QyWechat($option);
                $wechat->checkAuth($option['appid'], $secret);
                return $wechat->updateUser($user_data);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getCollectionName() {
        return 'technician_info';
    }

    public function parseRow($row, $output=[]) {
        $newRow = [];

        $newRow['_id']              = intval($row['_id']);
        $newRow['name']             = CommonFn::get_val_if_isset($row, 'name', '');
        $newRow['desc']             = CommonFn::get_val_if_isset($row, 'desc', '');
        $newRow['avatar']           = CommonFn::get_val_if_isset($row, 'avatar', '');
        $newRow['favourable_count'] = CommonFn::get_val_if_isset($row, 'favourable_count', 0);
        $newRow['order_count']      = CommonFn::get_val_if_isset($row, 'order_count', 0);
        $newRow['business']         = CommonFn::get_val_if_isset($row, 'business', []);
        $newRow['coverage']         = CommonFn::get_val_if_isset($row, 'coverage', []);
        $newRow['coverage_json']    = json_encode($newRow['coverage']);
        $newRow['status']           = CommonFn::get_val_if_isset($row, 'status', 0);
        $newRow['status_str']       = self::$status_option[$newRow['status']]['name'];
        $newRow['weixin_userid']    = CommonFn::get_val_if_isset($row, 'weixin_userid', '');
        $newRow['weixin_info']      = CommonFn::get_val_if_isset($row, 'wechat_info', (object)[]);
        $newRow['mobile']           = CommonFn::get_val_if_isset($row, 'mobile', '');
        $newRow['service_type']     = CommonFn::get_val_if_isset($row, 'service_type', []);

        $scheme                     = CommonFn::get_val_if_isset($row, 'scheme', 'no_scheme');
        $scheme_option              = Commision::$scheme_option;
        if ($scheme == 'no_scheme') {
            $newRow['scheme'] = -1;
            $newRow['scheme_str'] = '未选择方案';
        } else {
            foreach ($scheme_option as $key => $item) {
                if ($item['alias'] == $scheme) {
                    $newRow['scheme'] = $key;
                    $newRow['scheme_str'] = $item['name'];
                    break;
                }
            }
        }

        return $this->output($newRow, $output);
    }

}