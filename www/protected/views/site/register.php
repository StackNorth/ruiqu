<!doctype html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>壹橙管家后台管理系统</title>
    <link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.2.0/css/bootstrap.css">
    <link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.2.0/css/bootstrap-theme.css">
    <link rel="stylesheet" href="../css/common.css?v20131128">
    <link rel="stylesheet" href="../css/style.css?v20131128">
    <!--[if lt IE 9]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container regist-index">
        <div class="panel panel-default">
            <div class="panel-heading text-right">
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=site/login">已有账号？去登录</a>
            </div>
            <div class="panel-body">
                <p class="text-center">请根据提示填写信息进行注册，通过审核后即可登录</p>
                <?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'register-form',
					'enableAjaxValidation'=>true,
					'htmlOptions' => array('class' => 'form-horizontal')
				)); ?>
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-3 control-label">公司邮箱：</label>
                        <div class="col-sm-9">
                        	<?php echo $form->emailField($model, 'email', array('class' => 'form-control', 'id' => 'inputEmail', 'placeholder' => 'Email')); ?>
                        	<?php echo $form->error($model, 'email', array('style' => 'color:red')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-3 control-label">用户姓名：</label>
                        <div class="col-sm-9">
                        	<?php echo $form->textField($model, 'name', array('class' => 'form-control', 'id' => 'inputName', 'placeholder' => 'Name')); ?>
                        	<?php echo $form->error($model, 'name', array('style' => 'color:red')); ?>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="inputID" class="col-sm-3 control-label">身份证号：</label>
                        <div class="col-sm-9">
                        	<?php echo $form->textField($model, 'idNum', array('class' => 'form-control', 'id' => 'inputID', 'placeholder' => 'ID')); ?>
                        	<?php echo $form->error($model, 'idNum', array('style' => 'color:red')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-3 control-label">设置密码：</label>
                        <div class="col-sm-9">
                        	<?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'id' => 'inputPassword', 'placeholder' => 'Password')); ?>
                        	<?php echo $form->error($model, 'password', array('style' => 'color:red')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPasswordAgain" class="col-sm-3 control-label">重复密码：</label>
                        <div class="col-sm-9">
                        	<?php echo $form->passwordField($model, 'passwordAgain', array('class' => 'form-control', 'id' => 'inputPasswordAgain', 'placeholder' => 'Password Again')); ?>
                        	<?php echo $form->error($model, 'passwordAgain', array('style' => 'color:red')); ?>
                        </div>
                    </div>
                    <div class="text-center">
                    	<?php echo CHtml::submitButton('注册', array('class' => 'btn btn-primary btn-extent', 'id' => 'submitButton')); ?>
                    </div>
                <?php $this->endWidget(); ?>
            </div>
            <div id="tip_div" style="text-align:center;">
                <span id="tip" style="color:red;line-height: 30px;"></span>
            </div>
        </div>
    </div>
    <script src="../js/jquery-1.10.2.min.js"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
<script type="text/javascript">
$(function () {

    $('#submitButton').attr('disabled', true);

    $('#inputPassword').keyup(function(event) {
        var inputPassword = $(this).val();
        if (checkPass(inputPassword) < 3) {
            $('#tip').html('请检查密码复杂度');
            $('#submitButton').attr('disabled', true);
        } else {
            if (checkEqual()) {
                $('#tip').html('');
                $('#submitButton').removeAttr('disabled');
            } else {
                $('#tip').html('');
            }
        }
    });

    $('#inputPasswordAgain').keyup(function(event) {
        var inputPasswordAgain = $(this).val();
        if (checkEqual()) {
            if (checkPass($('#inputPassword').val()) >= 3) {
                $('#tip').html('');
                $('#submitButton').removeAttr('disabled');
            } else {
                $('#tip').html('请检查密码复杂度');
                $('#submitButton').attr('disabled', true);
            }
        } else {
            $('#tip').html('两次密码输入不一致');
            $('#submitButton').attr('disabled', true);
        }
    });

});

// 检查密码是否相同
function checkEqual() {
    var password = $('#inputPassword').val();
    var passwordAgain = $('#inputPasswordAgain').val();

    return password == passwordAgain;
}

//密码复杂度验证
//1、长度大于8
//2、密码必须是字母大写，字母大、小写，数字，特殊字符中任意三个组合。
function checkPass(pass){
    if(pass.length < 8){
        return 0;
    }
    var ls = 0;

    if(pass.match(/([a-z])+/)){
        ls++;
    }
    if(pass.match(/([0-9])+/)){
        ls++;
    }
    if(pass.match(/([A-Z])+/)){
        ls++;
    }
    if(pass.match(/[^a-zA-Z0-9]+/)){
        ls++;
    }

    return ls;
}
</script>
</html>