<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="nofollow">
    <meta name="robots" content="noarchive">
    <link rel="shortcut icon" href="images/favicon.ico">
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
    <div class="container login-index">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title text-center">壹橙管家后台管理系统</h3>
            </div>
            <div class="panel-body">
                <?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'login-form',
					'enableAjaxValidation'=>true,
					'htmlOptions' => array('class' => 'form')
				)); ?>
                    <div class="form-group">
                        <label for="inputEmail" class="control-label">请输入帐号</label>
                        <?php echo $form->textField($model, 'username', array('class' => 'form-control', 'placeholder' => 'Email')); ?>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="control-label">请输入密码</label>
                        <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Password')); ?>
                        <?php echo $form->error($model, 'password', array('style' => 'color:red')); ?>
                    </div>
                    <div class="form-group text-center last-form-group">
                        <label class="checkbox-inline">
                            <input type="checkbox">记住密码
                        </label>
                        <label class="checkbox-inline">
                        	<?php echo $form->checkBox($model, 'rememberMe'); ?>
                            自动登录
                        </label>
                        <?php echo CHtml::submitButton('登录', array('class' => 'btn btn-default')); ?>
                    </div>
                <?php $this->endWidget(); ?>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=site/register">注册帐号</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <a href="javascript:;">忘记密码</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/jquery-1.10.2.min.js"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>