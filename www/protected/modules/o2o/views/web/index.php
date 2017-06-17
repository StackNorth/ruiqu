<!DOCTYPE html>
<html>
<head>
  <title>壹橙管家</title>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet"
        href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/framework/ratchet-2.0.2/dist/css/ratchet.min.css">
  <link rel="stylesheet"
        href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/framework/ratchet-2.0.2/dist/css/ratchet-theme-ios.min.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/o2o/dist/css/main.css?v=2017042100">
  <style>
      html{height:100%}body{position:relative;margin:0;padding:0;width:100%;height:100%;overflow:hidden}.spa-fullscreen{position:absolute;left:0;top:0;margin:0;padding:0;width:100%;visibility:hidden;overflow:hidden;z-index:-1}.spa-page{position:absolute;left:0;top:0;bottom:0;right:0;margin:0;padding:0;overflow:hidden;z-index:2000;-webkit-transform:translateZ(0);-webkit-backface-visibility:hidden;-webkit-transform-style:preserve-3d}.spa-page-bg{position:absolute;left:0;top:0;bottom:0;right:0;margin:0;padding:0}.spa-page-body{position:absolute;left:0;top:0;bottom:0;right:0;margin:0;padding:0;overflow:hidden;-webkit-transform:translateZ(0);-webkit-backface-visibility:hidden;-webkit-transform-style:preserve-3d}.spa-scroll{overflow:auto}.spa-scroll-touch{-webkit-overflow-scrolling:touch}.spa-scroll-x{overflow-y:hidden}.spa-scroll-y{overflow-x:hidden}.spa-cover{display:none;position:absolute;left:0;right:0;top:0;bottom:0;text-align:center;z-index:5000}.spa-loader{position:absolute;left:0;right:0;top:0;bottom:0;text-align:center;overflow:hidden;z-index:5001}.loading{position:absolute;width:100%;height:100%;left:0;background:rgba(255,255,255,0.21);z-index:99}.loading p{position:fixed;left:50%;top:50%;-webkit-transform:translate(-50%,-50%);text-align:center;color:#222;font-size:.8rem}.loading .process{position:absolute;top:0;left:0;-webkit-transform:translate(0%,-10%);width:100%;height:5px;border-radius:0px;border:0px solid #ccc;background:rgba(255,255,255,0.34);overflow:hidden}.loading .process .cs{background:#ffba00;width:0;height:100%;animation:myfirst 4s infinite;-moz-animation:myfirst 4s infinite;-webkit-animation:myfirst 4s infinite;-o-animation:myfirst 4s infinite}@keyframes myfirst{0%{width:0}100%{width:100%}}
  </style>
</head>
<body>
<!-- loading -->
<!-- loading -->
<div class="spa-fullscreen"></div>
<div class="spa-loader">
    <div class="loading">
        <div class="process">
            <div class="cs"></div>
        </div>
    </div>
<!--    <div style="position: absolute;top: 50%;width:60%;left:20%;text-align: center;">加载君拼命加载中。。。</div>-->
</div>
<!--<div class="spa-loader">-->
<!--  <div class="loader" style="width: 15%;margin: 0 auto;margin-top: 60%;">-->
<!--    <div class="loader-inner pacman">-->
<!--      <div></div>-->
<!--      <div></div>-->
<!--      <div></div>-->
<!--      <div></div>-->
<!--      <div></div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
<script type="text/javascript" id="graceMain"></script>
<script type="text/javascript" id="debugMain"></script>
<script type="text/javascript" id="wxMain" data-sign="<?php echo @htmlentities(json_encode($signPackage)); ?>"></script>
<script type="text/javascript">
  (function () {
    var ua = navigator.userAgent.toLowerCase();
    <?php
    if (isset($from_channel)) {
      echo "var from_channel = '$from_channel';";
      if (!empty($userId) && $from_channel == 'baidu') {
        echo "var bd_userId = localStorage.setItem('bd_userId',  '" . $userId . "');";
      }
    }
    ?>
    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
      <?php
      if (!empty($userId) && !empty($appToken)) {
        echo '
            var wxUserID = localStorage.setItem("wxUserID",  "' . $userId . '");
            var appToken = localStorage.setItem("appToken", "' . $appToken . '");
          ';
      }
      ?>
      var userID = localStorage.getItem('wxUserID');
      var appToken = localStorage.getItem('appToken');
      if (!userID || !appToken) {
        localStorage.clear();
        location.href = '<?php echo Yii::app()->request->baseUrl . '/o2o/web/wxIndex'; ?>';
      }
      //document.write('<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"><\/script>');
    }
    if (location.hash == '#rd') {
      location.hash = '';
    }
    var debug = false;
    var main = '<?php echo Yii::app()->request->baseUrl;?>/webapp/o2o/dist/js/main.js?v=2017042100';
    if (debug) {
      main = '<?php echo Yii::app()->request->baseUrl;?>/webapp/o2o/js/main.js?v=' + (new Date()).getTime();
      document.getElementById('debugMain').src = '<?php echo Yii::app()->request->baseUrl; ?>/webapp/common/js/debuggap.js';
    }
    document.getElementById('graceMain').setAttribute('data-main', main);
    document.getElementById('graceMain').src = '<?php echo Yii::app()->request->baseUrl; ?>/webapp/common/js/require.js';
  })();
</script>
</body>

</html>
