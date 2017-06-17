<!DOCTYPE html>
<html>
<head>
  <title>壹橙管家</title>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/framework/ratchet-2.0.2/dist/css/ratchet.min.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/framework/ratchet-2.0.2/dist/css/ratchet-theme-ios.min.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/o2o/dist/css/main.css?v=2016032401">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/o2o/css/ratchetOverloading.css?v=2016032205">
  <style>
  html{height:100%;}body{position:relative;margin:0;padding:0;width:100%;height:100%;overflow:hidden;}.spa-fullscreen{position:absolute;left:0;top:0;margin:0;padding:0;width:100%;visibility:hidden;overflow:hidden;z-index:-1;}.spa-page{position:absolute;left:0;top:0;bottom:0;right:0;margin:0;padding:0;overflow:hidden;z-index:2000;-webkit-transform:translateZ(0);-webkit-backface-visibility:hidden;-webkit-transform-style:preserve-3d;}.spa-page-bg{position:absolute;left:0;top:0;bottom:0;right:0;margin:0;padding:0;}.spa-page-body{position:absolute;left:0;top:0;bottom:0;right:0;margin:0;padding:0;overflow:hidden;-webkit-transform:translateZ(0);-webkit-backface-visibility:hidden;-webkit-transform-style:preserve-3d;}.spa-scroll{overflow:auto;}.spa-scroll-touch{-webkit-overflow-scrolling:touch;}.spa-scroll-x{overflow-y:hidden;}.spa-scroll-y{overflow-x:hidden;}.spa-cover{display:none;position:absolute;left:0;right:0;top:0;bottom:0;text-align:center;z-index:5000;}.spa-loader{position:absolute;left:0;right:0;top:0;bottom:0;text-align:center;overflow:hidden;z-index:5001;}.spa-loader-animate{position:absolute;top:50%;left:50%;}.spa-loader-animate .bg{position:absolute;width:64px;height:64px;margin:0 auto;top:-32px;left:-32px;border-radius:50%;background:#2C3E50;opacity:0.5;}.spa-loader-animate .ball{display:block;float:left;padding:8px;margin-top:-8px;margin-left:-10px;-webkit-border-radius:50%;-moz-border-radius:50%;-ms-border-radius:50%;-o-border-radius:50%;border-radius:50%;}.spa-loader-animate span:nth-child(2){background:#16A085;-webkit-animation:move-left 800ms ease-in-out infinite alternate;-moz-animation:move-left 800ms ease-in-out infinite alternate;-ms-animation:move-left 800ms ease-in-out infinite alternate;-animation:move-left 800ms ease-in-out infinite alternate;}.spa-loader-animate .ball:nth-child(3){background:#E67E22;-webkit-animation:move-right 800ms ease-in-out infinite alternate;-moz-animation:move-right 800ms ease-in-out infinite alternate;-ms-animation:move-right 800ms ease-in-out infinite alternate;animation:move-right 800ms ease-in-out infinite alternate;}@-webkit-keyframes move-left{to{-webkit-transform:translate(20px,0);transform:translate(20px,0);background:#e85932;}}@-webkit-keyframes move-right{to{-webkit-transform:translate(-20px,0);transform:translate(-20px,0);background:#44bbcc;}}
  </style>
  <script type="text/javascript" src="https://dn-bughd-web.qbox.me/bughd.min.js" crossOrigin="anonymous"></script>
  <script type="text/javascript">
    window.bughd = window.bughd || function(){};
    bughd("create",{key:"aebe40c5d5b2975056629dc7980676ce"})
  </script>
</head>

<body>
  <!-- loading -->
  <div class="spa-fullscreen"></div>
  <div class="spa-loader">
    <div class="spa-loader-animate">
      <div class="bg"></div>
      <span class="ball"></span>
      <span class="ball"></span>
    </div>
  </div>

  <script type="text/javascript" id="graceMain"></script>
  <script type="text/javascript" id="debugMain"></script>
  <script type="text/javascript" id="wxMain" data-sign="<?php echo @htmlentities(json_encode($signPackage)); ?>"></script>
  <script type="text/javascript">
    function getCookie(name) 
    { 
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
     
        if(arr=document.cookie.match(reg))
     
            return unescape(arr[2]); 
        else 
            return null; 
    } 
    (function(){
    var ua = navigator.userAgent.toLowerCase();

    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
      var userID = getCookie("wxUserID");
      if (!userID) {
        location.href = '<?php echo Yii::app()->request->baseUrl."/login/wxOpen&redirect_uri=".$current_uri; ?>';
      }
      document.write('<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"><\/script>');
    }
    if (location.hash == '#rd') {
      location.hash = '';
    }
    var debug = false;
    var main = '<?php echo Yii::app()->request->baseUrl;?>/webapp/o2o/dist/js/main.js?v=20160660804';
    if (debug) {
      main = '<?php echo Yii::app()->request->baseUrl;?>/webapp/o2o/js/main.js?v=' + (new Date()).getTime();
      //document.getElementById('debugMain').src = '<?php echo Yii::app()->request->baseUrl; ?>/webapp/common/js/debuggap.js';
    }
    document.getElementById('graceMain').setAttribute('data-main', main);
    document.getElementById('graceMain').src = '<?php echo Yii::app()->request->baseUrl; ?>/webapp/common/js/require.js';
    })();
    </script>
    <!-- cnzz 统计代码 -->

</body>

</html>
