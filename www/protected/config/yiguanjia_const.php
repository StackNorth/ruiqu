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
$wz['cookie'] = 'UM_distinctid=15bccaf972e1b5-08d94ddabbc11e-396a7807-13c680-15bccaf972fe96; pgv_pvi=8607036416; opencom_uniqid=5909643f83a5d; _qddaz=QD.llwk1x.hu5sy6.j28imwkb; __AdinAll_SSP_NUID=f16139fe947b0a7d80182d0b30e72b03; tanindex_20160405=2017-06-17; OUTFOX_SEARCH_USER_ID_NCOO=431690427.4502184; make_from=gailan; Hm_lvt_15120f6526def7c8c1561d60de5abf89=1497538766,1497677381; Hm_lvt_b8c68976fe13d819f264767901121325=1497677380; OpenCom=PyLb51cJswsP0ulwiLzvA0TRhGwXkeAGppX0n0uLf%2BCe3ulyexQRPTeoICuLRZ83RU2miHJHN2n04JeXI8pivHmPESIAsGspJy19E7Sj01HCDOrVXRaaMdJEC%2BSnl21zeyDrk8yp2IgbG7A9UWXaQxJekYp2OuQOxvdY5U2eUcfQ6BEgsSNPmmbqbul1z%2FfyL33omsLJfmb2FylAbafsMYPt513W%2F7KCeRDF9MvylaIy9ajzSJVbSCqFMYy1tpx2dHDuzWE7Zoy3UC5S61Yddq5KzWRotrDcnfOuDVj98haVyEn0QUanv4jki5o86FTxNJ5hE25ZWk2ubuy7bD5bT1vKF5s9Km98FyFB205r1t9m6kHOQGgqMPMtXTpPYPa2Jj1368N24etR%2Bv2rDMe%2B5zvCdmb8Z88b7M%2BGYXWU1Hw9SZ3Uw752C5aPtQ25uZoHdg7NmlItKuRSrZBKmcRvSohiZ%2FkIDNF7QuzeVjMC2CciExLTtC0LIoygJtWeGlUIwlNz0Uvsu3T1NsAF0x4R7pIA4EYe8w330C5dwQdcLrG17R6K%2FGZ3O7sQ3YJ0fHiTXLaT6WcwIYpBPR53xqGM0ipelNrtopg0oTxX848eveZuik8HFzYkLVWmLW9zPvw7gbr%2BMLF76kDp8bi6hc1o8l2UaaRtKn5TLmj5%2FifdZNTymoXdEJ4H91HNKBxVmUxWiQ6%2F3Y%2FQ0exJ7EJLiU9Av3ZPoHI28DYQa3bjrnDkoVAcofsieUg%2FbW5ae6q8X%2FxkIFKxVEz7MkgwAdPpDEkpAkGOP0hm9wch49TStC4tJxTP0eW6WbW0M8u4italEiEEsnMgkxtBivyU5aUa2D1ND212FwmL4YAEBqt%2FgGLZZPuWXTfmDP32UzmkMF0Ch6VqblvUeXY0MQB5JsCpTMkqEuj5ZFpe5PKVMUueuNN65bcthfsvgJhDbNqq5IZNXsd4xC8hC5c6QLx0hLGFwQIGzA%3D%3D; oc_login_name=Sama; oc_login_uid=1575621; oc_reg_time=1452572196; oc_img_id=65194238; PHPSESSID=lgb1q0rrkpkqdeubmh8cvf24s5';

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
