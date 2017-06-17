<?php
/**
 * Created by JetBrains PhpStorm.
 * User: charlie
 * Date: 13-11-29
 * Time: 下午4:47
 * To change this template use File | Settings | File Templates.
 */
class User extends MongoAr
{
    public $_id;
    public $email = '';         //邮箱
    public $name = '';          //姓名or用户名
    public $id_num = '' ;
    public $pass = '';          //密码
    public $status = 0;         //状态
    public $reg_time = 0;       //注册时间
    public $last_login = 0;     //上次登陆时间
    public $login_times = 0;    //登陆次数
    public $type = 1;           //类型
    public $fake_users = array();
    public $scheme;   // 提成方案
    public $service_type;//array(1, 2, 3);
    public $coverage;//array() 服务范围 array(array('provice'=>'上海市', 'city'=> '上海市', 'area'=>'静安区', points=>array()));

    public $is_member;          // 是否为企业微信成员，默认为否
    public $userid;             // 企业微信号内部ID
    public $wx_info;            // 微信信息
    /*  
    $wx_info = array(
       'name'          => '张三',                   // 企业内部姓名
       'department'    => [1, 2],                   // 所属部门
       'position'      => '技术',                   // 职位
       'mobile'        => '13012345678',            // 用户手机，唯一
       'weixinid'      => 'zhangsandeweixin',       // 成员微信号
       'gender'        => 1,                        // 性别
    );
    */
    
    public static $status_option = array(
        1  => array('name' => '正常',   'color' => 'green', 'wx' => 1),
        0  => array('name' => '待审核', 'color' => 'blue',  'wx' => -1),
        -1 => array('name' => '已删除', 'color' => 'red',   'wx' => 0)
    );

    public static $is_member_option = array(
        0 => array('name' => '否'),
        1 => array('name' => '是'),
    );

    public static $gender_option = array(
        0 => array('name' => '未填写'),
        1 => array('name' => '男'),
        2 => array('name' => '女'),
    );

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'users';
    }

    public static function get($_id) {
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $_id);
        $model = self::model()->find($criteria);
        if ($model) {
            return $model;
        } else {
            return false;
        }
    }

    public static function getAdminByFakeUserId($fake_user_id){
        $criteria = new EMongoCriteria();
        $criteria->fake_users('==', $fake_user_id);
        $model = self::model()->find($criteria);
        if ($model) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * UserModel整理函数
     * @author     2015-11-31
     */
    public function parseRow($row, $output=array()) {
        $newRow = array();

        // 基本信息
        $newRow['_id']           = (string)$row['_id'];
        $newRow['email']         = CommonFn::get_val_if_isset($row, 'email', '');
        $newRow['name']          = CommonFn::get_val_if_isset($row, 'name', '');
        $newRow['id_num']        = CommonFn::get_val_if_isset($row, 'id_num', '');
        $newRow['pass']          = CommonFn::get_val_if_isset($row, 'pass', '');
        $newRow['status']        = CommonFn::get_val_if_isset($row, 'status', 1);

        $reg_time                = CommonFn::get_val_if_isset($row, 'reg_time', 0);
        $newRow['reg_time']      = $reg_time != 0 ? date('Y-m-d H:i', $reg_time) : '';

        $last_login              = CommonFn::get_val_if_isset($row, 'last_login', 0);
        $newRow['last_login']    = $last_login != 0 ? date('Y-m-d H:i', $newRow['last_login']) : '';
        $newRow['login_times']   = CommonFn::get_val_if_isset($row, 'login_times', 0);
        $newRow['type']          = CommonFn::get_val_if_isset($row, 'type', 1);
        $newRow['fake_users']    = CommonFn::get_val_if_isset($row, 'fake_users', array());
        $newRow['member_enable'] = CommonFn::get_val_if_isset($row, 'member_enable', -1);
        $newRow['userid']        = CommonFn::get_val_if_isset($row, 'userid', '');
        $newRow['wx_info']       = CommonFn::get_val_if_isset($row, 'wx_info', array());

        // 提成方案
        $newRow['scheme_str']    = CommonFn::get_val_if_isset($row, 'scheme', '');
        $scheme_option           = Commision::$scheme_option;
        $newRow['scheme']        = -1;
        foreach ($scheme_option as $key => $value) {
            if ($value['alias'] == $newRow['scheme_str']) {
                $newRow['scheme'] = $key;
            }
        }

        // 微信信息
        $newRow['is_member']     = CommonFn::get_val_if_isset($row, 'is_member', 0);
        $wx_info                 = $newRow['wx_info'];
        $newRow['wx_name']       = CommonFn::get_val_if_isset($wx_info, 'name', '');
        $newRow['department']    = CommonFn::get_val_if_isset($wx_info, 'department', array());
        $newRow['position']      = CommonFn::get_val_if_isset($wx_info, 'position', array());
        $newRow['mobile']        = CommonFn::get_val_if_isset($wx_info, 'mobile', '');
        $newRow['weixinid']      = CommonFn::get_val_if_isset($wx_info, 'weixinid', '');
        $newRow['gender']        = CommonFn::get_val_if_isset($wx_info, 'gender', 0);

        // 编辑信息
        $newRow['action_user']   = CommonFn::get_val_if_isset($row,'action_user',"");
        $newRow['action_time']   = CommonFn::get_val_if_isset($row,'action_time',"");
        $newRow['action_log']    = CommonFn::get_val_if_isset($row,'action_log',"");

        // 后台角色
        $auth = Yii::app()->authManager;
        $roles = array_keys($auth->getAuthAssignments($newRow['_id']));
        $newRow['role'] = implode(',', $roles);

        // 服务类型
        $newRow['service_type'] = CommonFn::get_val_if_isset($row, 'service_type', array());

        // 服务范围
        $newRow['coverage'] = CommonFn::get_val_if_isset($row, 'coverage', array());

        return $this->output($newRow, $output);
    }
}