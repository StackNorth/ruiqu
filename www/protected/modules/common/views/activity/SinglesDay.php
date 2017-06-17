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
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/webapp/o2o/dist/css/main.css?v=2016032401">
  <link rel="stylesheet" href=" <?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-3.3.7.min.css">
  <!--引入js文件-->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-3.1.1.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/AngularJS v1.4.3.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-3.3.7.min.js"></script>

</head>
<body ng-controller="myctrl">
<div class="">
  <!--  <img style="width: 100%"-->
  <!--       src="http://odulvej8l.bkt.clouddn.com/%E6%B0%91%E5%AE%BF%E4%BF%9D%E6%B4%81%E8%AF%A6%E6%83%85%E9%A1%B5.jpg"-->
  <!--       alt="">-->
  <h1></h1>
  <form action="index.php?r=common/Activity/saveAdvisory" method='post'
        class="container  form-horizontal  ng-valid ng-dirty ng-valid-parse" style="margin-bottom: 15%; ">
    <div class="container">
      <!--姓名-->
      <div class="form-group">
        <label>姓名:</label>
        <input type="text" name='name' class="form-control username" placeholder="请输入您的姓名" ng-model="username">
      </div>
      <!--选择区域-->
      <div class="form-group">
        <label>选择区域:</label>
        <select name="area" class="form-control" ng-model="Area" ng-init="Area=AreaList[0].id"
                ng-options="info.id as info.name for info in AreaList" id="">
          <option value="">--请选择--</option>
        </select>
      </div>
      <!--房型-->
      <div class="form-group">
        <label>选择房型:</label>
        <select name="type" class="form-control" ng-model="Type" ng-init="Type=TypeList[0].id"
                ng-options="type.id as type.name for type in TypeList" id="">
          <option value="">--请选择--</option>
        </select>
      </div>
      <!--房源套数-->
      <div class="form-group">
        <label>选择房源套数:</label>
        <select name="num" class="form-control" ng-model="Num" ng-init="Num=NumList[0].id"
                ng-options="num.id as num.name for num in NumList" id="">
          <option value="">--请选择--</option>
        </select>
      </div>
      <!--手机号码-->
      <div class="form-group">
        <label>手机号码:</label>
        <input type="text" name="phone" class="form-control"
               placeholder="请输入您的手机号码" ng-model="phone"/>
      </div>
      <!--性别-->
      <div class="form-group">
        <div style="display: inline-block;max-width: 100%;margin-bottom: 5px;font-weight: 700;">性别:</div>
        <div>
          <label class="radio-inline">
            <input type="radio" name="sex" value="男" ng-model="sex">男
          </label>
          <label class="radio-inline">
            <input type="radio" name="sex" value="女" ng-model="sex">女
          </label>
        </div>
      </div>
      <!--按钮-->
      <div class="form-group">
        <button
          style="background: rgb(42,36,0);color:rgb(178,143,0);border: 0"
          class="btn btn-success col-xs-12"
                data-container="body"
                data-toggle="popover"
                data-placement="top"
                data-content="{{btnContent}}">
          提交
        </button>
      </div>
    </div>
  </form>
</div>
<div class="prevent-scroll">
  <div class="box my-action">
    <!--首页-->
    <div class="width-percent-33">
      <div class="title-container-square">
        <a class="btn-home-action btn-action-my-order" href="/index.php?r=o2o/web/index#">
          <div class="logo logo-my-order" style="margin-bottom: 5px;"></div>
          <div class="name">首页</div>
        </a>
      </div>
    </div>
    <!--LOGO-->
    <div class="width-percent-33">
      <div class="title-container-square">
        <a class="btn-home-action btn-action-logo" href="weixin://contacts/profile/honghaitzz">
          <div class="logo logo-my-logo"></div>
        </a>
      </div>
    </div>
    <!--我的-->
    <div class="width-percent-33">
      <div class="title-container-square">
        <a class="btn-home-action btn-action-coupon">
          <div class="logo logo-coupon" style="margin-bottom: 5px;"></div>
          <div class="name">我的</div>
        </a>
      </div>
    </div>
  </div>
</div>
<!--模态框-->
<div class="model" style="width: 100%;position: fixed;top: 0;background: rgba(0,0,0,0.4)">
  <!--错误提示容器-->
  <div class="model-box"
       style="border-radius: 10px;height:130px;display:none;width: 70%;background: #fff;margin: 0 auto;top:25%;left:15%;position: fixed;">
    <div class="model-title"
         style="font-family: '微软雅黑', 'Microsoft YaHei', 'STHeiti Light';width: 100%;text-align: center;font-size: 2.2rem;margin-top: 5%;"></div>
    <div class="model-content"
         style="font-family: '微软雅黑', 'Microsoft YaHei', 'STHeiti Light';margin: 0 auto;width: 80%;background: rgb(42,36,0);color:rgb(178,143,0);font-size: 1.3rem;margin-top: 30px;text-align: center;padding: 5px 0 5px 0"></div>
  </div>
</div>
</body>
</html>
<script>
  var myapp = angular.module('myapp', []);
  myapp.controller('myctrl', ['$scope', function ($scope) {
    $('.model-content').on('click', function () {
      $('.model').css('height', '0');
      $('.model-box').css('display', 'none');
    });
    /*表单提交进行判断*/
    $('button').on('click', function () {
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
        /*性别*/
        if ($scope.sex == undefined) {
          model('请重新选择', '确认');
          return false;
        }
        /*model*/
        function model(title, content) {
          $('.model-box').css('display', 'inline');
          $('.model').height(window.screen.height);
          $('.model-title').html(title);
          $('.model-content').html(content);
        }
      }
    );

    $scope.AreaList = [
      {'id': 1, 'name': '跨多个区', 'parent': 1, type: '2'},
      {'id': 2, 'name': '黄浦区', 'parent': 1, type: '2'},
      {'id': 3, 'name': '徐汇区', 'parent': 1, type: '2'},
      {'id': 4, 'name': '长宁区', 'parent': 1, type: '2'},
      {'id': 5, 'name': '静安区', 'parent': 1, type: '2'},
      {'id': 6, 'name': '普陀区', 'parent': 1, type: '2'},
      {'id': 7, 'name': '虹口区', 'parent': 1, type: '2'},
      {'id': 8, 'name': '杨浦区', 'parent': 1, type: '2'},
      {'id': 9, 'name': '闵行区', 'parent': 1, type: '2'},
      {'id': 10, 'name': '宝山区', 'parent': 1, type: '2'},
      {'id': 11, 'name': '嘉定区', 'parent': 1, type: '2'},
      {'id': 12, 'name': '浦东新区', 'parent': 1, type: '2'},
      {'id': 13, 'name': '金山区', 'parent': 1, type: '2'},
      {'id': 14, 'name': '松江区', 'parent': 1, type: '2'},
      {'id': 15, 'name': '青浦区', 'parent': 1, type: '2'},
      {'id': 16, 'name': '奉贤区', 'parent': 1, type: '2'}
    ];
    $scope.TypeList=[
      {'id': 1, 'name': '一户室', 'parent': 1, type: '3'},
      {'id': 2, 'name': '两户室', 'parent': 1, type: '3'},
      {'id': 3, 'name': '三户及以上', 'parent': 1, type: '3'},
      {'id': 4, 'name': '单间', 'parent': 1, type: '3'},
      {'id': 5, 'name': '多套多房型', 'parent': 1, type: '3'},
    ];
    $scope.NumList=[
      {'id': 1, 'name': '整房三套以下', 'parent': 1, type: '4'},
      {'id': 2, 'name': '整房三套以上', 'parent': 1, type: '4'},
      {'id': 3, 'name': '多套单间', 'parent': 1, type: '4'}
    ]
  }])
</script>