<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=B349f0b32ef6e78b2e678f45cb9fddaf"></script>
<link href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/o2o/jq-signature.min.js"></script>

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css">
<div>
  <div id="header">
    <div class="am-g">
      <div class="am-u-sm-12">
        <div class="layoutbox">
          订单详情
        </div>
      </div>
    </div>
  </div>
  <!-- 订单详情 -->
  <div id="info">
    <div class="am-popup-bd" id="info_content" style="display: none;">
      <div class="am-g">
        <div class="am-u-sm-4">预约时间</div>
        <div class="am-u-sm-8">{{info.booking_time_str}}</div>
      </div>
      <div class="am-g">
        <div class="am-u-sm-4">客户姓名</div>
        <div class="am-u-sm-8">{{info.address.name}}</div>
      </div>
      <div class="am-g">
        <div class="am-u-sm-4">地址</div>
        <div class="am-u-sm-8">{{info.address.city}}&nbsp;{{info.address.area}}&nbsp;{{info.address.poi.name}}&nbsp;{{info.address.detail}}</div>
      </div>
      <div class="am-g">
        <div class="am-u-sm-4">客户电话</div>
        <div class="am-u-sm-8"><a href="tel:{{info.address.mobile}}">{{info.address.mobile}}</a></div>
      </div>
      <div class="am-g">
        <div class="am-u-sm-4">订单状态</div>
        <div class="am-u-sm-8">{{info.status_str}}</div>
      </div>
      <br>
      <div class="am-g">
        <div class="am-u-sm-6">
          <button class="am-btn am-btn-primary" style="width: 100%;" id="showMap">显示地图</button>
        </div>
        <div class="am-u-sm-6">
          <button class="am-btn am-btn-default" style="width: 100%;" id="hideMap">隐藏地图</button>
        </div>
      </div>
      <div class="am-g">
        <div class="am-u-sm-12">
          <div id="map_box" style="display: none;">
            <div id="map_container" style="margin-top: 10px; width: 100%;"></div>
          </div>
        </div>
      </div>
      <br>
      <div class="am-g">
        <div class="am-u-sm-4">产品</div>
        <div class="am-u-sm-12">
          <ul class="am-list am-list-static am-list-border">
            <li v-for="product in info.products">
              名称:&nbsp;<span id="formProductName">{{info.products[0].product.name}}</span><br>
              价格:&nbsp;{{info.products[0].product.price}}
            </li>
          </ul>
        </div>
      </div>
      <div class="am-g" v-if="info.append_orders.length > 0">
        <div class="am-u-sm-4">追加订单</div>
        <div class="am-u-sm-12">
          <ul class="am-list am-list-static am-list-border">
            <li v-for="append in info.append_orders">
              <ul class="am-list am-list-static am-list-border">
                <li v-for="product in append.products">
                  服务:&nbsp;{{product.name}}<br>
                  价格:&nbsp;{{product.price}}
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
      <div class="am-g am-hide" id="confirm_container">
        <div class="am-u-sm-12">
          <button class="am-btn am-btn-block am-btn-primary" id="confirm_set_out">确认订单</button>
        </div>
      </div>
      <div class="am-g am-hide" id="setout_container">
        <div class="am-u-sm-12">
          <button class="am-btn am-btn-block am-btn-primary" id="confirm_set_out">确认出发</button>
        </div>
      </div>
      <div class="am-g am-hide" id="come_container">
        <div class="am-u-sm-12">
          <button class="am-btn am-btn-block am-btn-primary" id="confirm_come">确认上门</button>
        </div>
      </div>
      <div class="am-g am-hide" id="has_come_container">
        <div class="am-u-sm-12">
          <button class="am-btn am-btn-block am-btn-primary">已上门</button>
        </div>
      </div>
      <div class="am-g am-hide" id="has_come_success">
        <div class="am-u-sm-12">
          <button class="am-btn am-btn-block am-btn-primary am-disabled">已完成</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--手写板-->
<div id="pics"
     style="position: fixed;width: 100%;background: #fff;top:0;display: none;text-align: center;padding-bottom: 10px;">
  <div class="">
    <div class="">
      <div class="js-signature"></div>
    </div>
    <p style="margin:0;border-top: 1px solid rgba(0,0,0,0.3);padding-top: 4px;">
      <button id="returnBtn" class="btn btn-default">返回</button>
      <button id="clearBtn" class="btn btn-default" onclick="clearCanvas();">清除</button>
      &nbsp;
      <button id="saveBtn" class="btn btn-default" onclick="saveSignature();" disabled>确认财产无误</button>
    </p>
  </div>
</div>
<div style="clear: both"></div>
<!--表单-->
<div id="orderForm"
     class="am-hide"
     style="position: fixed;top: 0;width: 100%;background: rgba(255,255,255,1);color:rgb(0,0,0);">
  <h1 style="text-align: center;margin-top: 1rem" id="formH1">民宿保洁检查表</h1>
  <form action="" id="orderFormTitle" class="form-ul">
    <div class="form-ul" style="position: fixed;5rem;">
      <!--设备检查-->
      <div id="orderForm1" class="am-hide">
        <h3 style="text-align: left">设备检查</h3>
        <input type="checkbox" value="电视机">电视机有遥控器、可开启。<br/>
        <input type="checkbox" value="空调">空调有遥控器、可开启。<br/>
        <input type="checkbox" value="冰箱">冰箱运行中。<br/>
        <input type="checkbox" value="Wifi">Wifi可连接。<br/>
      </div>
      <!--卧室客厅-->
      <div id="orderForm2" class="am-hide">
        <h3 style="text-align: left">卧室客厅</h3>
        <input type="checkbox" value="四件套">四件套,已换新，无污无毛发。<br/>
        <input type="checkbox" value="床底">床底,床沿触手可及处，无异物。<br/>
        <input type="checkbox" value="床头柜"><span>床头柜,抽屉内无前客残留物，台面干净、无灰尘污迹。</span><br/>
        <input type="checkbox" value="地面">地面,扫过一遍，无明显毛发、灰尘、污迹。<br/>
        <input type="checkbox" value="茶几、写字台">茶几、写字台,台面干净、无灰尘污迹。<br/>
      </div>
      <!--洗手间-->
      <div id="orderForm3" class="am-hide">
        <h3 style="text-align: left">洗手间</h3>
        <input type="checkbox" value="马桶">马桶洗刷一遍，清洁无渍，无毛发，马桶盖翻开。<br/>
        <input type="checkbox" value="台盆">台盆,台面台盆整洁，已擦干，无渍无毛发。<br/>
        <input type="checkbox" value="浴缸、淋浴房">浴缸、淋浴房,已擦干，无渍无毛发。<br/>
        <input type="checkbox" value="地面">地面拖过一遍、无明显毛发、灰尘、污迹。<br/>
      </div>
      <!--厨房-->
      <div id="orderForm4" class="am-hide">
        <h3 style="text-align: left">厨房</h3>
        <input type="checkbox" value="冰箱">厨房无前客残留物，内无食物残留污迹。<br/>
        <input type="checkbox" value="微波炉">微波炉内壁托盘无明显食物残留污迹。<br/>
        <input type="checkbox" value="灶台、厨具">灶台、厨具灶台清洁无油渍，使用过的厨具清洗擦拭收纳。<br/>
        <input type="checkbox" value="餐桌">餐桌擦拭一遍，无污迹油渍。。<br/>
        <input type="checkbox" value="水斗">水斗无厨余残存痕迹。<br/>
        <input type="checkbox" value="地面">地面扫过一遍，无明显灰尘、污迹。<br/>
      </div>
      <!--最后检查-->
      <div id="orderForm5" class="am-hide form-group">
        <h3 style="text-align: left">最后检查</h3>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <input type="checkbox" value="窗户">窗户闭合关实。<br/>
          <input type="checkbox" value="电器">电器除冰箱、wifi外，其它家电关闭状态。<br/>
        </div>
        <h4>物料</h4>
        <div class="" style="display: block">
          <span class="col-xs-8 col-md-8">卧室、客厅，有纸巾摆放包数。</span>
          <select name="" class="" id="">
            <option value="0">0</option>
            <option value="0-5">0-5</option>
            <option value="5-10">5-10</option>
            <option value="10-20">10-20</option>
          </select>
        </div>
        <div class="col-lg-12">
          <span class="col-xs-8 col-md-8">厕所，有卷纸摆放包数。</span>
          <br>
          <select name="" class="" id="">
            <option value="0">0</option>
            <option value="0-5">0-5</option>
            <option value="5-10">5-10</option>
            <option value="10-20">10-20</option>
          </select>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <input type="checkbox"> 洗发水、沐浴露预估存于30%以上。
        </div>
      </div>
    </div>
    <div style="text-align: center;position:fixed;bottom:1rem;margin-top: 1rem;" class="col-xs-12 col-sm-12 col-md-12">
      <a class="btn btn-danger" id="formNO">取消</a>
      &nbsp;&nbsp;
      <a class="btn btn-success" id="formYes">确认</a>
    </div>
  </form>
</div>
<script type="text/javascript">
  /*设置全局变量*/
  var url;
  var index = 0;
  /*表单js*/
  $(function () {
    /*取消*/
    $('#formNO').on('click', function () {
      if (index == 0) {
        $('#orderForm').addClass('am-hide');
        $('#orderForm1').addClass('am-hide');
        return false;
      }
      if (index == 1) {
        $('#orderForm1').removeClass('am-hide');
        $('#orderForm2').addClass('am-hide');
        index = 0;
        return false;
      }
      if (index == 2) {
        $('#orderForm2').removeClass('am-hide');
        $('#orderForm3').addClass('am-hide');
        index = 1;
        return false;
      }
      if (index == 3) {
        $('#orderForm3').removeClass('am-hide');
        $('#orderForm4').addClass('am-hide');
        index = 2;
        return false;
      }
      if (index == 4) {
        $('#orderForm4').removeClass('am-hide');
        $('#orderForm5').addClass('am-hide');
        index = 3;
        return false;
      }
    });
    /*确认*/
    $('#formYes').on('click', function () {
      if (index == 0) {
        $('#orderForm1').addClass('am-hide');
        $('#orderForm2').removeClass('am-hide');
        index = 1;
        return false;
      }
      if (index == 1) {
        $('#orderForm2').addClass('am-hide');
        $('#orderForm3').removeClass('am-hide');
        index = 2;
        return false;
      }
      if (index == 2) {

        $('#orderForm3').addClass('am-hide');
        $('#orderForm4').removeClass('am-hide');
        index = 3;
        return false;
      }
      if (index == 3) {

        $('#orderForm4').addClass('am-hide');
        $('#orderForm5').removeClass('am-hide');
        index = 4;
        return false;
      }
      if (index == 4) {
        $('#orderForm5').addClass('am-hide');

        //隐藏表单
        $('#orderForm').addClass('am-hide');
        /*调用手写板*/
        $('#has_come_container').addClass('am-hide');
        $('#has_come_success').removeClass('am-hide');
        $('#pics').css('display', 'block');
        $('.js-signature').eq(0).on('jq.signature.changed', function () {
          $('#saveBtn').attr('disabled', false);
        });
      }

    });
    /*返回*/
    $('#returnBtn').on('click', function () {
      index = 0;
      $('#pics').css('display', 'none');
      $('#has_come_container').removeClass('am-hide');
      $('#has_come_success').addClass('am-hide');
    })
  });
  /*form样式调整*/
  $(function () {
    /*设置ul与li样式*/
    $('ul.form-ul').css("margin", '0').css('padding', '0');
    $('ul.form-ul').children('li').css("list-style", 'none');
    /*设置ul的宽度*/
    $('ul.form-ul').children('li').width(($(window).width()));
    /*设置ul的高度  orderFormOne*/
    $('ul.form-ul').height($('#orderForm1').height());
    /*设置li的宽度*/
//    $('ul.form-ul').children('li').width($(window).width());
    /*设置表单的margin-top*/
    var formMargin = ((($(window).height()) - ($('#orderFormTitle').height())) / 6) - (($('#formH1').height()) * 3);
    $('#orderFormTitle').css('margin-top', formMargin);
    $('#orderForm').height($(window).height());
    /*设置body的外边距为0*/
    $('body').css('margin', '0');
  });
  /*手写板js*/
  $(document).on('ready', function () {
    if ($('.js-signature').length) {
      $('.js-signature').jqSignature();
    }
  });
  $('.js-signature').eq(0).on('jq.signature.changed', function () {
    $('#saveBtn').attr('disabled', false);
  });
  $('#pics').height($(window).height());//设置底层div高度
  $("canvas").width($(window).width());//设置手写板宽度
  $("canvas").height($(window).height() - ($('#saveBtn').height()));//设置手写板高度
  document.title = '我的订单-详情';
  var order = <?php echo json_encode($order); ?>;
  var user = <?php echo json_encode($user); ?>;
  var showMap = false;
  var w_height = $(window).height();
  var map_height = w_height * 0.6;
  $('#map_container').css({height: map_height + 'px'});
  var v_order = new Vue({
    el: '#info',
    data: {
      info: []
    }
  });
  $(function () {
    getOrderInfo(order, user);
    // 显示地图
    $('#showMap').click(function () {
      address = v_order.info.address;
      position = address.position;
      if (!position) {
        $.valert('定位信息未记录');
        return false;
      }

      $('#map_box').show();

      map = new BMap.Map('map_container');
      point = new BMap.Point(position[0], position[1]);
      marker = new BMap.Marker(point);
      map.addOverlay(marker);
      map.centerAndZoom(point, 15);

      windowOpts = {
        width: 200,
        height: 100,
        title: address.area
      }
      infoWindow = new BMap.InfoWindow(address.detail, windowOpts);
      marker.addEventListener("click", function () {
        map.openInfoWindow(infoWindow, point);
      });
    });
    // 隐藏地图
    $('#hideMap').click(function () {
      $('#map_box').hide();
    });
    //确认订单
    $('#confirm_container').click(function () {
      $.vloading('open');
      $.post(
        '/index.php?r=o2o/myOrder/techConfirmOrder',
        {
          order: order,
          user: user
        },
        function (res) {
          $.vloading('close');
          var data = $.parseJSON(res);
          if (data.success) {
            $('#confirm_container').addClass('am-hide');
            $('#setout_container').removeClass('am-hide');
            $('#come_container').addClass('am-hide');
            $('#has_come_container').addClass('am-hide');
          } else {
            $.valert(data.msg);
          }
        }
      );
    });
    // 确认出发
    $('#setout_container').click(function () {
      $.vloading('open');
      $.post(
        '/index.php?r=o2o/myOrder/techSetout',
        {
          order: order,
          user: user
        },
        function (res) {
          $.vloading('close');
          var data = $.parseJSON(res);
          if (data.success) {
            $('#setout_container').addClass('am-hide');
            $('#come_container').removeClass('am-hide');
            $('#has_come_container').addClass('am-hide');
          } else {
            $.valert(data.msg);
          }
        }
      );
    });
    // 确认上门
    $('#confirm_come').click(function () {
      $.vloading('open');
      $.post(
        '/index.php?r=o2o/myOrder/techCome',
        {
          order: order,
          user: user
        },
        function (res) {
          $.vloading('close');
          var data = $.parseJSON(res);
          if (data.success) {
            $('#setout_container').addClass('am-hide');
            $('#come_container').addClass('am-hide');
            $('#has_come_container').removeClass('am-hide');
            $('#has_come_success').addClass('am-hide');
          } else {
            $.valert(data.msg);
          }
        }
      );
    });
    //已上门

    $('#has_come_container').click(function () {
      console.log();
      if($('#formProductName').html()=='民宿保洁'){
        /*调出表单*/
        $('#orderForm').removeClass('am-hide');
        $('#orderForm1').removeClass('am-hide');
      }else {
        /*调用手写板*/
        $('#has_come_container').addClass('am-hide');
        $('#has_come_success').removeClass('am-hide');
        $('#pics').css('display', 'block');
        $('.js-signature').eq(0).on('jq.signature.changed', function () {
          $('#saveBtn').attr('disabled', false);
        });
      }
//      return false;

    });
  });
  function putb64() {
    var pic = url.replace("data:image/png;base64,", "");//需要提交的base64
    var uri = "http://up.qiniu.com/putb64/-1";//提交地址
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        $.post(
          '/index.php?r=o2o/myOrder/techComplete',
          {
            order: order,
            user: user,
            o2oImage: xhr.responseText
          },
          function (res) {
            $.vloading('close');
            var data = $.parseJSON(res);
            if (data.success) {
              $('#setout_container').addClass('am-hide');
              $('#come_container').addClass('am-hide');
              $('#has_come_container').addClass('am-hide');
              $('#has_come_success').removeClass('am-hide');
            } else {
              $.valert(data.msg);
            }
          }
        );
      }
    }
    xhr.open("POST", uri, true);
    xhr.setRequestHeader("Content-Type", "application/octet-stream");
    xhr.setRequestHeader("Authorization", "UpToken rjs8hPzTLArsZ7qkDRpEMripCvdDUumMaUWUqtLz:APTrcQNSbu2CHw9wd8s9-GN4G9Y=:eyJzY29wZSI6ImF2YXRhcnMiLCJkZWFkbGluZSI6MTQ5MTMwMTAzOTAwfQ==");
    xhr.send(pic);
  }
  function saveSignature() {
    $('#signature').empty();
    url = $('.js-signature').eq(0).jqSignature('getDataURL');
    if (url != undefined) {
      $('#pics').css('display', 'none');
      //七牛上传照片
      putb64();
    }
  }
  function clearCanvas() {
    $('.js-signature').eq(0).jqSignature('clearCanvas');
    $('#saveBtn').attr('disabled', true);
  }
  function getOrderInfo(order, user) {
    if (order.length == 0 || user.length == 0) {
      $.valert('订单信息未录入');
      return false;
    }

    $.vloading('open');
    $.post(
      '/index.php?r=o2o/o2oApp/getOrderInfo',
      {
        id: order,
        user: user
      },
      function (res) {
        $.vloading('close');
        var data = $.parseJSON(res);
        if (data.success == false) {
          $.valert(data.msg);
        } else {
          var content = data.content;
          v_order.info = content;
          for (var j in content.technicians) {
            if (content.technicians[j]['technician_id'] == user) {
              if (content.status != 4 && content.status != 5 && content.status != 6 && $.inArray(content.status, [1, 2, 3]) != -1) {
                $('#confirm_container').removeClass('am-hide');
                $('#setout_container').addClass('am-hide');
                $('#come_container').addClass('am-hide');
                $('#has_come_container').addClass('am-hide');
              } else if (content.status == 4) {
                $('#setout_container').addClass('am-hide');
                $('#come_container').removeClass('am-hide');
                $('#has_come_container').addClass('am-hide');
              } else if (content.status == 5) {
                $('#setout_container').addClass('am-hide');
                $('#come_container').addClass('am-hide');
                $('#has_come_container').removeClass('am-hide');
              } else if (content.status == 6) {
                $('#setout_container').addClass('am-hide');
                $('#come_container').addClass('am-hide');
                $('#has_come_container').addClass('am-hide');
                $('#has_come_success').removeClass('am-hide');
              }
              break;
            }
          }
          $('#info_content').show();
        }
      }
    );
  }
</script>