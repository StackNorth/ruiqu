<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/cropbox/jquery.cropbox.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/cropbox/hammer.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/cropbox/jquery.mousewheel.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/cropbox/jquery.cropbox.js?v=2016032201"></script>

<form class="am-form tech-form">
  <fieldset>
    <legend><?=$name?><small>个人信息</small></legend>
    <div class="am-form-group">
      <label for="doc-ta-1">介绍</label>
      <textarea class="intro" rows="5" id="doc-ta-1"><?=$desc?></textarea>
    </div>

    <div class="am-form-group am-form-file">
      <label for="doc-ipt-file-2">头像</label>
      <div>
        <button type="button" class="am-btn am-btn-default am-btn-sm tech-avatar-btn">
          <i class="am-icon-cloud-upload"></i> 选择图片</button>
        <input type="file" id="tech-avatar" style="top: initial;bottom: 0;font-size: 1.4rem;padding: .25em 1em;">
      </div>
    </div>
    <p class="img-preview">
      <img src="<?=$avatar?>" alt="" class="" width="200" height="200">
    </p>
    <p>
      <!-- <button type="submit" class="am-btn am-btn-default btn-edit">编辑</button> -->
      <button type="submit" class="am-btn am-btn-success btn-submit">提交</button>
    </p>
  </fieldset>
</form>
<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">提示信息</div>
    <div class="am-modal-bd">
      介绍或者头像不能为空
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn">确定</span>
    </div>
  </div>
</div>
<script>
$(function(){
  /* 初始化参数 */
  var tech_name   = <?=json_encode($name)?>;          // 保洁师姓名
  var tech_id     = <?=json_encode($_id)?>;           // 保洁师后台ID
  var token       = <?=json_encode($qiniu_token)?>;   // 七牛上传token
  var url         = <?=json_encode($qiniu_url)?>;     // 七牛上传url_prefix
  var avatar = <?=json_encode($avatar)?>;
  var intro = <?=json_encode($desc)?>;
  var cropImg = '';
  var apiUrl = 'http:// api.yiguanjia.me';
  if (location.host == ' apitest.yiguanjia.me') {
    apiUrl = 'http:// apitest.yiguanjia.me';
  } else if (location.host == 'api.yiguanjiadev.me') {
    apiUrl = 'http://api.yiguanjiadev.me';
  }

  function edit() {
    $('.tech-form').find('.intro').removeAttr('readonly');
    $('#tech-avatar').removeAttr('disabled');
    $('.tech-avatar-btn').removeClass('am-disabled');
  }
  function submit() {
    $('.tech-form').find('.intro').attr('readonly', 'true');
    $('#tech-avatar').attr('disabled', 'disabled');
    $('.tech-avatar-btn').addClass('am-disabled');
  }

  $('.intro').on('blur', function(event) {
    event.preventDefault();
    intro = $(this).val();
  });

  $('.tech-form').on('click', '.btn-edit', function(event) {
    event.preventDefault();
    edit();
  });

  $('.tech-form').on('click', '.btn-submit', function(event) {
    event.preventDefault();
    if (intro == '') {
      alert('介绍不能为空');
    } else if (avatar == '') {
      alert('请上传头像');
    } else {
      submit();
      $.ajax({
        url: apiUrl + '/index.php?r=o2o/techHome/updateInfo',
        jsonp: "callback",
        dataType: "jsonp",
        data: {
          tech_id: tech_id,
          desc: intro,
          avatar: avatar + cropImg
        }
      })
      .done(function(res) {
        alert(res.message);
      })
      .fail(function(res) {
        alert(res.message);
      })
      .always(function() {
        edit();
      });
    }
  });

  $('.tech-form').on('change', '#tech-avatar', function(event) {
    event.preventDefault();

    var files = event.target.files;
    var formData = new FormData();
    formData.append('token', token);
    formData.append('file', files[0]);
    var name = files[0].name;
    var point = name.lastIndexOf('.'),
        type = name.substr(point),
        key = Math.random().toString(16).substring(2) + (+new Date()) + type;
    formData.append('key', key);

    $.ajax({
      url: 'http://upload.qiniu.com',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false
    })
    .done(function(res) {
      avatar = url + res.key
      $('.img-preview').html('<img src="'+ url + res.key +'" alt="" class="cropimage">');
      crop();
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  });

  function crop() {
    $('.cropimage').cropbox({
      width: 200,
      height: 200,
      controls: ''
    }, function() {
      //on load

    }).on('cropbox', function(event, results) {
      var X = results.cropX;
      var Y = results.cropY;
      var W = results.cropW;
      var H = results.cropH;
      cropImg = '?imageMogr/v2/crop/!' + W + 'x' + H + 'a' + X + 'a' + Y;
    });
  }
})
</script>
