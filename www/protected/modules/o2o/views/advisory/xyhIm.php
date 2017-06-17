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
<img src="http://odulvej8l.bkt.clouddn.com/%E4%BA%8C%E7%BB%B4%E7%A0%81%E8%81%8A%E5%A4%A9-01.jpg" alt="联系我们"
     style="width: 100%;margin-top: -0.2rem">
</body>

</html>