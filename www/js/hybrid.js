function os_proxy(fn){
    var u = navigator.userAgent.toLowerCase();
    if (!fn.hasOwnProperty('browser')){
        fn['browser'] = function(){
            console.log('没有定义方法');
            return false;
        }
    }
    if ((/android|linux/i.test(u)) && fn.hasOwnProperty('android')){
        return fn['android']();
    } else if ((/iphone|ipad|ipod/i.test(u)) && fn.hasOwnProperty('ios')){
        return fn['ios']();
    } else{
        return fn['browser']();
    }
}
//获取用户的信息
function get_user_info(){
    os_proxy({
        android: function(){
            window.jsapi.getUserInfo('{"callback":"notifyGetUserInfo"}');
        },
        ios: function(){
            window.location.href = 'http://callclient?method=getUserInfo&callback=notifyGetUserInfo';
        }
    });
}
//保存用户的信息
function notifyGetUserInfo(result){
    //alert('notifyGetUserInfo called');
    var reg=new RegExp('(\r\n|\r|\n)', 'g');
    var _res = JSON.stringify(result).replace(reg, '');
    $('#result').val(_res);
    var res = JSON.parse(_res);

    res = os_proxy({
        android: function(){
            if (res.success == 1){
                res.success = true;
            } else {
                res.success = false;
            }
            return res;
        },
        ios: function(){
            if (res && res.hasOwnProperty('success')){
                if (res.success == 1){
                    res.success = true;
                } else {
                    res.success = false;
                }
            }
            return res;
        }
    });
    if (res){
        if (res.hasOwnProperty('version')){
            app_version = res.version;
        }
        if (res.hasOwnProperty('deviceId')){
            deviceId = res.deviceId;
        }
        if (res.hasOwnProperty('osVersion')){
            osVersion = res.osVersion;
        }

        if (res.hasOwnProperty('success') && res.success){
            do_login(res);
        }
    }
}
//根据客户端接口或web登录接口初始化用户信息
function do_login(res){
    if (res.hasOwnProperty('success')){
        user_info = res.data;
    }
    if (res.hasOwnProperty('success')){
        user_id = user_info.id;
    }

    //$('#result').val('user_id:'+user_id+'<br />'+'osVersion:'+osVersion+'<br />'+'deviceId:'+deviceId+'<br />'+'app_version:'+app_version+'<br />'+'user_name:'+user_info.user_name+'<br />');
}
//分享
function do_share(opts){
    var share_img = 'http://www.yiguanjia.me/images/logo.png';
    var share_str = '';
    var share_url = '';
    var title = '';
    if (opts.hasOwnProperty('str')){
        if (typeof(opts.str) == 'function'){
            share_str = opts.str();
        } else {
            share_str = opts.str;
        }
    }
    if (opts.hasOwnProperty('img')){
        share_img = opts.img;
    }
    if (opts.hasOwnProperty('url')){
        share_url = opts.url;
    }
    if (opts.hasOwnProperty('title')){
        title = opts.title;
    }
    var share_param = {
        share_title : title,
        share_string : share_str,
        share_img_url: encodeURIComponent(share_img),
        share_url: encodeURIComponent(share_url)
    };
    os_proxy({
        android: function(){
            share_param.share_img_url = share_img;
            share_param.share_url = share_url;
            window.jsapi.doShare(JSON.stringify(share_param));
        },
        ios: function(){
            window.location.href = 'http://callclient?method=doShare&param=' + JSON.stringify(share_param);
        }
    });
}
//退出webview
function exit_webview(){
    os_proxy({
        android: function(){
            window.jsapi.exitWebView();
        },
        ios: function(){
            var url = 'http://callclient?method=exitWebView';
            window.location.href = url;
        }
    });
}
//跳转到登录页面
function go_login(){
    os_proxy({
        android: function(){
            window.jsapi.goLogin('{"callback":"notifyGetUserInfo"}');
        },
        ios: function(){
            var url = 'http://callclient?method=goLogin';
            url += '&callback=notifyGetUserInfo';
            window.location.href = url;
        }
    });
}

//打开/关闭IOS的左滑返回
function switch_pop_gesture(flag){
    os_proxy({
        ios: function(){
            var url = 'http://callclient?method=switchPopGesture';
            url += '&param={"enable":'+flag+'}';
            window.location.href = url;
        }
    });
}

var user_id = 0;
var user_info = null;
var osVersion = '';
var deviceId = '';
var app_version = '2.0';

$(function(){
    //get_user_info();
})
