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
    })
  </script>
</head>
<body ng-controller="myctrl">
<div class="container" style="margin-top: 2rem">
  <form action="" method='post'
        class="container  form-horizontal  ng-valid ng-dirty ng-valid-parse" id='enterprise'
        style="margin-bottom: 15%; ">
      <!--姓名-->
      <div class="form-group">
        <label>姓名:</label>
        <input type="text" name='user_name' class="form-control username" placeholder="请输入您的姓名" ng-model="username">
      </div>
      <!--选择区域-->
      <div class="form-group">
        <label>选择区域:</label>
        <select name="area" class="form-control" ng-model="Area" ng-init="Area=AreaList[0].id"
                ng-options="info.name as info.name for info in AreaList" id="">
          <option value="">--请选择--</option>
        </select>
      </div>
      <!--房型-->
      <div class="form-group">
        <label>选择服务:</label>
        <select name="tech_content" class="form-control" ng-model="AechContent" ng-init="AechContent=AechContentList[0].id"
                ng-options="type.name as type.name for type in AechContentList" id="">
          <option value="">--请选择--</option>
        </select>
      </div>

      <!--手机号码-->
      <div class="form-group">
        <label>手机号码:</label>
        <input type="text" name="mobile" class="form-control"
               placeholder="请输入您的手机号码" ng-model="phone"/>
      </div>
      <!--性别-->
      <div class="form-group">
        <div style="display: inline-block;max-width: 100%;margin-bottom: 5px;font-weight: 700;">性别:</div>
        <div>
          <label class="radio-inline">
            <input type="radio" name="sex" value="先生" ng-model="sex">先生
          </label>
          <label class="radio-inline">
            <input type="radio" name="sex" value="女士" ng-model="sex">女士
          </label>
        </div>
      </div>
      <!--按钮-->
      <div class="form-group">
        <a id="enterpriseBtn"
            style="background: rgb(42,36,0);color:rgb(178,143,0);border: 0"
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
        <a class="btn-home-action btn-action-coupon" href="/index.php?r=o2o/web/index">
          <div class="logo logo-coupon"style="margin-bottom: 0.5rem;"></div>
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
         style="font-family: '微软雅黑', 'Microsoft YaHei', 'STHeiti Light';width: 100%;text-align: center;font-size: 1.1rem;margin-top: 5%;"></div>
    <div class="model-content"
         style="font-family: '微软雅黑', 'Microsoft YaHei', 'STHeiti Light';margin: 0 auto;width: 80%;background: rgb(42,36,0);color:rgb(178,143,0);font-size: 1.3rem;margin-top: 30px;text-align: center;padding: 5px 0 5px 0"></div>
  </div>
</div>
</body>
</html>
<script src="//cdn.bootcss.com/angular.js/1.4.3/angular.min.js"></script>
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
    /*表单提交进行判断*/
    $('#enterpriseBtn').on('click', function () {

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

          $.ajax({
            url: 'index.php?r=o2o/Advisory/enterprise',
            type: 'POST',
            dataType: 'html',
            data: $('#enterprise').serialize(),
            success: function (message) {
              model(message,'返回首页');

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
    $scope.AechContentList=[
      {'id': 1, 'name': '写字楼日常保洁', 'parent': 1, type: '3'},
      {'id': 2, 'name': '展会日常保洁', 'parent': 1, type: '3'},
      {'id': 3, 'name': '开荒保洁', 'parent': 1, type: '3'},
      {'id': 4, 'name': '地毯清洁', 'parent': 1, type: '3'},
      {'id': 5, 'name': '地板打蜡', 'parent': 1, type: '3'},
      {'id': 6, 'name': '沙发清洗', 'parent': 1, type: '3'},
      {'id': 7, 'name': '电器清洗', 'parent': 1, type: '3'},
      {'id': 8, 'name': '玻璃清洗', 'parent': 1, type: '3'},
      {'id': 9, 'name': '整体消毒', 'parent': 1, type: '3'},
      {'id': 10, 'name': '电器消毒', 'parent': 1, type: '3'},
      {'id': 11, 'name': '除尘除螨', 'parent': 1, type: '3'}
    ];

  }])


</script>