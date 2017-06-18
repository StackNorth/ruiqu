<?php
/**
 * 自定义参数配置文件
 * 使用方法:
 * $defaultGroupAvatar = Yii::app()->params['defaultGroupAvatar'];
 *
 */

// $wz['city_info_list'] = 'city_info';//用户城市信息异步处理队列

$wz['avatar_list'] = 'wx_avatar';//头像异步处理队列名

$wz['send_coupons_push_list'] = 'send_coupons_list';//发送优惠券通知队列

$wz['defaultUserAvatar'] = 'http://7oxep6.com2.z0.glb.clouddn.com/user_avatar_default.png';

$wz['defaultImage'] = 'http://7oxep6.com2.z0.glb.clouddn.com/image_default.png';

$wz['emailReg'] = '/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i';

$wz['phoneReg'] = '/1[34578]{1}\d{9}$/';

$wz['JingBai'] = '99C91A2E9219390F43B7F7E5DAEFC073';
$wz['ROrderListPageSize'] = 10;//订单列表分页大小

$wz['indexPageSize'] = 20;//首页每页数量

$wz['pagesize'] = 10;
$wz['O2oCommentListPageSize'] = 10;//产品评论分页大小

$wz['app'] = 'web';

$wz['adminEmail'] = 'weixin@yichenguanjia.com';

$wz['wxConfig'] = array(
    'appId' => 'wx0c02ff3a467afe9e',
    'appSecret' => '4381b4c033ac83c95bae7d9b62f04345'
);
$wz['xyhWxConfig'] = array(
  'appId' =>'wx637ee1a426503bdb',
  'appSecret' => '35938d1813453e91d732b085a939ad87'
);
$wz['cookie'] = 'UM_distinctid=15bccaf972e1b5-08d94ddabbc11e-396a7807-13c680-15bccaf972fe96; pgv_pvi=8607036416; opencom_uniqid=5909643f83a5d; _qddaz=QD.llwk1x.hu5sy6.j28imwkb; OUTFOX_SEARCH_USER_ID_NCOO=431690427.4502184; make_from=gailan; Hm_lvt_b8c68976fe13d819f264767901121325=1497677380; OpenCom=PyLb51cJswsP0ulwiLzvA0TRhGwXkeAGppX0n0uLf%2BCe3ulyexQRPTeoICuLRZ83RU2miHJHN2n04JeXI8pivHmPESIAsGspJy19E7Sj01HCDOrVXRaaMdJEC%2BSnl21zeyDrk8yp2IgbG7A9UWXaQxJekYp2OuQOxvdY5U2eUcfQ6BEgsSNPmmbqbul1z%2FfyL33omsLJfmb2FylAbafsMUpW%2BOMRE6R0kMw3Dp4sW%2B9JWjpRBy2Q9ig83FWZil5n4PNYBLCd9HB4sgfyiuL7EXL2fnBztCTl6xBNdMP%2BAxGUQvn5xwNOWDxr6cyaJPlYq%2Flt0yRmWL2lYQcrM5pVNVRJ7XIiU4beZGw4yf1KsT4radCxHRb0om34Y1erDnXgeaVLdCExjE26j%2BE0uyu%2FB7q70H0X7HBjNROc1TpRdo4UxrBHUtqufnJmEuzqtso1OO5diiLBh87hisGlQTZuaeCW2Z20HIyXndAwEVC8Pk%2Fxx%2BZ8EG8QVE%2B4DNOzS6PLFu7rzFOsCXZKgorKRCsAHl4K4ZI400HjvJtGi8jsQkMUWqBrGILMoLtNDctTvi7AddzC2NKZDUwKSCWYkZZpy9bH%2B9R9epW5hwrhdkLiIe6npFEXi4osM%2BQFunnJfvG8MwHD46BNa0aKaiS1rQCWAcrM9AZCcZF7pbbXZQg7qkPCLqajU9C3VdsDakGJCJ%2BtxzWcuKdcYiqaHLCyZrGJcMuYrHZorWGoW9SFKawnF0UCyWmbbLHxsL0gCtIzGQPOa5Qyui7ZMOnI%2FDeectrNetzrFxbJzzosNIiZ1LVB%2FJ3JK%2B1hVmT5vxT7H%2BzGeOkAOMIofqEMGoJhaicKQgjzvyVCpIWZ6ajhwjlcEJTNMwo6nyrhgU0TThUki5kCAJOMNuakEJ0bKSh3E%2Bupp%2FaikX4v8XT8dBI%2B4yIegxaNosb602TUb3%2BbGPLRR13fMIfQTHul7DRVFp0svfu1gMK5Lg%3D%3D; oc_login_name=Sama; oc_login_uid=1575621; oc_reg_time=1452572196; oc_img_id=65194238; PHPSESSID=lgb1q0rrkpkqdeubmh8cvf24s5; tanindex_20160405=2017-06-18';


$wz['o2o_service'] = array(
    1=>array("name"=>"日常清洁"),
    2=>array("name"=>"深度清洁"),
    3=>array("name"=>"除螨杀菌"),
    4=>array("name"=>"家电清洗"),
    5=>array("name"=>"民宿保洁"),
    6=>array("name"=>"新居开荒"),
    8=>array("name"=>"母婴房清洁"),
    9=>array("name"=>"租房清洁"),
    10=>array("name"=>"擦玻璃"),
    11=>array("name"=>"活动产品"),
    12=>array("name"=>"长期订"),
);

//新手礼包
$wz['new_user_coupon_ids'] = array(
    '58bbab13ce93adb2048b4570',
);
//新手礼包金额
$wz['new_user_coupons_value'] = 20;
