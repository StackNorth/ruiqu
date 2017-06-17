<!--/**
 * Created by PhpStorm.
 * User: Jinguo
 * Date: 2016/11/11
 * Time: 13:14
 */-->
<!DOCTYPE html>
<!--HTML5 doctype-->
<html ng-app="myapp">
<head>
  <title>壹橙管家</title>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--引入css文件-->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/common/css/common.css">
  <link rel="stylesheet" href=" <?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-3.3.7.min.css">
  <!--引入js文件-->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-3.1.1.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-3.3.7.min.js"></script>
  <!--修改title-->
  <script>
    $(function () {
      document.title = "壹橙管家";
	    $('img').height($(window).height());
	    $('img').width($(window).width());
    })
  </script>
</head>
<body>
  <img src="http://odulvej8l.bkt.clouddn.com/1110%E4%BA%8C%E7%BB%B4%E7%A0%81.jpg" alt="联系我们" style="width: 100%;margin-top: -0.2rem">
<div class="prevent-scroll">
  <div class="box my-action">
    <!--首页-->
    <div class="width-percent-33">
      <div class="title-container-square">
        <a class="btn-home-action btn-action-my-order" href="/index.php?r=o2o/web/index">
          <div class="logo logo-my-order" style="margin-bottom: 0.5rem;"></div>
          <div class="name">首页</div>
        </a>
      </div>
    </div>
    <!--LOGO-->
    <div class="width-percent-33">
      <div class="title-container-square">
        <a class="btn-home-action btn-action-logo" href="/index.php?r=o2o/advisory/im">
          <div class="logo logo-my-logo"></div>
        </a>
      </div>
    </div>
    <!--我的-->
    <div class="width-percent-33">
      <div class="title-container-square">
        <a class="btn-home-action btn-action-coupon">
          <div class="logo logo-coupon"style="margin-bottom: 0.5rem;"></div>
          <div class="name">我的</div>
        </a>
      </div>
    </div>
  </div>
</div>
</body>
</html>