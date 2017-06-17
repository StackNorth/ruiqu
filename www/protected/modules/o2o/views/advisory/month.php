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
	<!--css-->
	<style>
		.gradient {
			color: #ffffff;
			font-size: 2rem;
			width: 100%;
			line-height: 40px;
			background: -webkit-linear-gradient(#4e1a42, #4e1a42, #4e1a42, #4e1a42); /* Safari 5.1 - 6.0 */
			background: -o-linear-gradient(#4e1a42, #4e1a42, #4e1a42, #4e1a42); /* Opera 11.1 - 12.0 */
			background: -moz-linear-gradient(#4e1a42, #4e1a42, #4e1a42, #4e1a42); /* Firefox 3.6 - 15 */
			background: linear-gradient(#4e1a42, #4e1a42, #4e1a42, #4e1a42); /* 标准的语法 */
		}
	</style>
	<!--引入css文件-->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/common/css/common.css">
	<link rel="stylesheet" href=" <?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-3.3.7.min.css">
	<link rel="stylesheet" href=" <?php echo Yii::app()->request->baseUrl; ?>/css/common_o2o/LCalendar.css">
	<!--引入js文件-->
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/AngularJS v1.4.3.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-3.3.7.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/o2o/LCalendar.js"></script>
	<!--修改title-->
	<script>
		$(function () {
			document.title = "壹橙管家";
		})
	</script>
</head>
<body ng-controller="myctrl">
<div class="gradient" style="font-size: 12px;padding-left: 10px;">返回</div>
<h1 style="text-align: center">咨询</h1>
<div class="">
	<form action="" method='post'
	      class="container  form-horizontal  ng-valid ng-dirty ng-valid-parse" id='singlesDay'
	      style="margin-bottom: 15%; ">
		<div class="container">
			<!--姓名-->
			<div class="form-group">
				<label>姓名:</label>
				<input type="text" name='user_name' class="form-control username" placeholder="请输入您的姓名" ng-model="username">
			</div>
			<!--手机号码-->
			<div class="form-group">
				<label>手机号码:</label>
				<input type="text" name="mobile" class="form-control"
				       placeholder="请输入您的手机号码" ng-model="phone"/>
			</div>
			<!--房型-->
			<div class="form-group">
				<label>咨询套餐:</label>
				<select name="homeType" onchange="theforever(this.value)" class="form-control" ng-model="Type"
				        ng-init="Type=TypeList[0].id"
				        ng-options="type.name as type.name for type in TypeList" id="">
					<option value="">--请选择--</option>
				</select>
			</div>
			<!--数据-->
			<input type="text" name="area" value="享月会" style="position: absolute;top:1000px;">
			<!--预产期-->
			<div class="form-group">
				<input name="num" class="form-control" style="background-color: #fff;" id="demo1" type="text" readonly=""
				       placeholder="请选择日期"/>
			</div>
			<!--按钮-->
			<div class="form-group">
				<a id="SinglesDayBtn"
				   style="background: #2f004e;color:rgb(255,255,255);border: 0"
				   class="btn btn-success col-xs-12"
				   data-container="body"
				   data-toggle="popover"
				   data-placement="top"
				   data-content="{{btnContent}}">
					提交
				</a>
			</div>
		</div>
	</form>
</div>

<!--模态框-->
<div class="model" style="width: 100%;position: fixed;top: 0;background: rgba(0,0,0,0.4)">
	<!--错误提示容器-->
	<div class="model-box"
	     style="border-radius: 10px;height:130px;display:none;width: 70%;background: #fff;margin: 0 auto;top:25%;left:15%;position: fixed;">
		<div class="model-title"
		     style="font-family: '微软雅黑', 'Microsoft YaHei', 'STHeiti Light';width: 100%;text-align: center;font-size: 1.1rem;margin-top: 5%;"></div>
		<div class="model-content"
		     style="font-family: '微软雅黑', 'Microsoft YaHei', 'STHeiti Light';margin: 0 auto;width: 80%;background: #2f004e;color:rgb(255, 255, 255);font-size: 1.3rem;margin-top: 30px;text-align: center;padding: 5px 0 5px 0"></div>
	</div>
</div>
</body>
</html>
<script>
	var myapp = angular.module('myapp', []);
	myapp.controller('myctrl', ['$scope', function ($scope) {
		$('.model-content').on('click', function () {
			if ($('.model-content').html() == '返回首页') {
				window.location.href = '/index.php?r=o2o/web/index';
			} else {
				$('.model').css('height', '0');
				$('.model-box').css('display', 'none');
			}
		});
		$('.gradient').on('click',function () {
			window.location.href = '/index.php?r=o2o/web/index';
		})
		/*表单提交进行判断*/
		$('#SinglesDayBtn').on('click', function () {
				/*姓名是否为空*/
				if ($scope.username == undefined) {
					model('请重填', '确认您的姓名');
					return false;
				}
				/*验证手机号码*/
				if (!(/^1[34578]\d{9}$/.test($scope.phone)) || $scope.phone == 0) {
					model('请重填', '手机号码有误');
					return false;
				}
				/*model*/
				$.ajax({
					url: 'index.php?r=o2o/Advisory/SinglesDay',
					type: 'POST',
					dataType: 'html',
					data: $('#singlesDay').serialize(),
					success: function (message) {
						model(message, '返回首页');
					},
					error: function (message) {
						model('标题', '咨询失败');
					}
				});
				function model(title, content) {
					$('.model-box').css('display', 'inline');
					$('.model').height(window.screen.height);
					$('.model-title').html(title);
					$('.model-content').html(content);
				}
			}
		);
		$scope.TypeList = [
			{'id': 1, 'name': '至惠套餐', 'parent': 1, type: '3'},
			{'id': 2, 'name': '至悦套餐', 'parent': 1, type: '3'},
			{'id': 3, 'name': '至享套餐', 'parent': 1, type: '3'},
			{'id': 4, 'name': '至尊套餐', 'parent': 1, type: '3'}
		];
	}])
</script>
<script>
	var calendar = new LCalendar();
	calendar.init({
		'trigger': '#demo1', //标签id
		'type': 'date', //date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择,
		'minDate': '1900-1-1', //最小日期
		'maxDate': (new Date().getFullYear() + 10) + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() //最大日期
	});
</script>