<link rel="stylesheet" href="http://cdn.amazeui.org//amazeui/2.5.0/css/amazeui.min.css">
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datetimepicker.css">
<style type="text/css">
body {margin: 8px;}
</style>
<div id="main">
	<div region="west" border="false" style="width: 550px;">
		<table id="dg_content"></table>
		<div id="tb_content">
	        <div class="tb_line">
	            <input id="ss" />
	            <span class="tb_label">状态:</span>
	            <input id="filter_status" />
	            <a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content()">查询</a>
	        </div>
	        <div class="tb_box">
				<a href="#" class="easyui-menubutton" data-options="menu:'#mm1',iconCls:'icon-edit'">更多操作</a>
				<div id="mm1" style="width:150px;">
			        <div data-options="iconCls:'icon-undo'" onclick="multiple_set_status(-1);return false;">批量删除</div>
			        <div data-options="iconCls:'icon-redo'" onclick="multiple_set_status(1);return false;">批量审核</div>
			    </div>

			</div>
	    </div>
	</div>
		<div class="easyui-accordion" region="center">
			<div region="center" title="信息">
				<div class="easyui-layout detail_layout">
					<div data-options="region:'center'" class="detail_center">
						<div class="detail_main">
							<form id="content_form" method="post">
								<ul>
									<li class="f_item">
										<div class="box">
											<div class="f_label">
												<span>用户名: </span>
											</div>
											<div class="box_flex f_content">
												<input type="hidden" name="_id" id="content_id" value=0 />
												<input type="text" class="easyui-validatebox" name="name" style="width: 200px;" />
											</div>
										</div>
									</li>
									<li class="f_item">
										<div class="box">
											<div class="f_label">
												<span>邮箱: </span>
											</div>
											<div class="box_flex f_content">
												<input type="text"  name="email" style="width: 200px;" />
											</div>
										</div>
									</li>
									<li class="f_item">
										<div class="box">
											<div class="f_label">
												<span>注册时间: </span>
											</div>
											<div class="box_flex f_content">
												<input type="text" name="reg_time" disabled="disabled" style="width: 200px;" />
											</div>
										</div>
									</li>
									<li class="f_item">
										<div class="box">
											<div class="f_label">
												<span>上一次登录: </span>
											</div>
											<div class="box_flex f_content">
												<input type="text" name="last_login" disabled="disabled" style="width: 200px;" />
											</div>
										</div>
									</li>
									<li class="f_item">
										<div class="box">
											<div class="f_label">
												<span>登录次数: </span>
											</div>
											<div class="box_flex f_content">
												<input type="text" name="login_times" disabled="disabled" style="width: 200px;" />
											</div>
										</div>
									</li>

									<li class="f_item">
										<div class="box">
											<div class="f_label">
												<span>状态: </span>
											</div>
											<div class="box_flex f_content">
												<input id="new_status" name="status" />
											</div>
										</div>
									</li>

									<li class="f_item">
										<div class="box">
											<div class="f_label">
												<span>角色: </span>
											</div>
											<div class="box_flex f_content">
												<input id="role" class="easyui-combobox" name="role" data-options="editable: false,data: roles,multiple: true" style="width: 200px;">
											</div>
										</div>
									</li>
									<li class="f_item">
										<div class="box">
											<div class="f_label">
												<span>修改密码: </span>
											</div>
											<div class="box_flex f_content">
												<input type="checkbox" id="modify_password" name="modify_password" />
											</div>
										</div>
									</li>
									<li id="modify_password_box" style="display:none;">
										<ul>
											<li class="f_item">
												<div class="box">
													<div class="f_label">
														<span>新密码: </span>
													</div>
													<div class="box_flex f_content">
														<input type="password" id="new_password" name="new_password" class="easyui-validatebox" />
													</div>
												</div>
											</li>
											<li class="f_item">
												<div class="box">
													<div class="f_label">
														<span>确认密码: </span>
													</div>
													<div class="box_flex f_content">
														<input type="password" id="confirm_new_password" name="confirm_new_password" class="easyui-validatebox" validType="equals['#new_password']"/>
													</div>
												</div>
											</li>
										</ul>
									</li>
									<li class="f_item">
						                <div class="box">
						                    <div class="f_label">
						                    </div>
						                    <div class="box_flex f_content">
						                        <span id="action_info" style="color:green;"></span>
						                    </div>
						                </div>
						            </li>
								</ul>
							</form>
						</div>
					</div>
					<div data-options="region:'south'" class="detail_south">
						<div class="detail_toolbar">
							<a href="#" class="easyui-linkbutton set_button" iconCls="icon-save" onclick="save_content();return false;">保存</a>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
<style type="text/css">
.regionTip {
	display: inline-block;
	margin-left: 6px;
	color: red;
}
.father_dpm {
	color: green;
}
.type_list {
	display: inline-block;
	width: 110px;
}
</style>
<script type="text/javascript">
var jq_dg_content = $('#dg_content');
var temp = new Date();
var today = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
var w_width = $(window).width();
var w_height = $(window).height();
var roles = <?php echo json_encode($role); ?>;
var jq_content_form = $('#content_form');
var jq_content_id = $('#content_id');
var jq_role = $('#role');
var jq_new_status = $('#new_status');
var jq_set_button = $('.set_button');
var jq_action_info = $('#action_info');
//var jq_mcs = $('#mcs');
//var jq_mcs = $('#mcs');

var jq_ss = $('#ss');
var status_data = <?php echo json_encode($status); ?>;
var jq_filter_status = $('#filter_status');

var jq_modify_password = $('#modify_password');
var jq_modify_password_box = $('#modify_password_box');
var jq_new_password = $('#new_password');
var jq_confirm_new_password = $('#confirm_new_password');

var module_router = site_root + '/index.php?r=user';

$(function(){
	$('#main').css({width: w_width - 25, height: w_height - 18}).layout();
	jq_ss.searchbox({
		width: 150,
        searcher:function(value){
            search_content();
        },
        prompt: '请输入关键字'
	});

	jq_filter_status.combobox({
		width: 100,
		editable: false,
		data: status_data,
		onSelect: function(){
			search_content();
		}
	});

	var new_status_data = $.extend(true, [], status_data);
	new_status_data.shift();

	jq_new_status.combobox({
		width: 100,
		editable: false,
		data: new_status_data
	});

	jq_dg_content.datagrid({
		url: module_router + '/getUser',
		title: '管理员列表',
		width: 540,
		height: w_height - 18,
	    fitColumns: true,
	    autoRowHeight: true,
	    striped: true,
	    multiSort: true,
	    toolbar: '#tb_content',
	    singleSelect: true,
	    pagination: true,
	    pageList: [20, 30, 50],
	    pageSize: 20,
	    nowrap: false,
	    idField: '_id',
	    sortName: '_id',
        sortOrder: 'asc',
	    checkOnSelect: false,
	    selectOnCheck: false,
	    queryParams: {},
	    columns:[[
	    	{field:'ck', checkbox: true},
	    	{field:'_id', title:'ID', width:15},
	        {field:'name', title:'用户名', width:25},
	        {field:'email', title:'邮箱', width:80},
	        {field:'reg_time', title:'注册时间', width:60},

	        {field:'status', title:'状态', width:25,
	        	formatter: function(value, row){
	        		return get_filed_text(value, new_status_data);
	        	}
	        }
	    ]],
	    onSelect: function(index, row){
	    	var data = $.extend(true, {}, row);
	    	data.role = data.role.split(',');
			jq_content_form.form('load', data);

			jq_set_button.linkbutton('enable');
			//jq_mcs.multiple_city_selector('refresh', data.admin_area_arr);
			init_reset_password();
			if (data['action_user'] != ''){
                jq_action_info.html('信息已被编辑: ' + data['action_user'] + ' ' + data['action_time']);
            } else {
                jq_action_info.html('');
            }
		},
		onLoadSuccess: function(){
			$(this).datagrid('clearChecked');
			jq_content_form.form('clear');
			jq_set_button.linkbutton('disable');
			init_reset_password();
			//jq_mcs.multiple_city_selector('refresh', []);
			jq_action_info.html('');
		}
	});
	jq_content_form.form({
		url: module_router + '/updateUser',
		onSubmit: function(param){
			if (jq_content_id.val() <= 0){
				return false;
			}
			param.new_role = jq_role.combobox('getValues').join(',');
			var isValid = $(this).form('validate');
			if (!isValid){
				return isValid;
			}
			if (jq_modify_password.is(':checked')){
				param.modify_password = 1;
				var new_password = jq_new_password.val();
				var confirm_new_password = jq_confirm_new_password.val();
				if (new_password == ''){
					$.messager.show({
						title: '提示',
						msg: '请输入新的密码',
						timeout: 5000,
						showType: 'slide'
					});
					jq_new_password.focus();
					return false;
				} else if (confirm_new_password == ''){
					$.messager.show({
						title: '提示',
						msg: '请确认新的密码',
						timeout: 5000,
						showType: 'slide'
					});
					jq_confirm_new_password.focus();
					return false;
				}
			} else {
				param.modify_password = 0;
			}
			$.messager.progress();
			return isValid;
		},
		success: function(res){
			$.messager.progress('close');

			var res = JSON.parse(res);
			if (res.success){
				jq_dg_content.datagrid('reload');
			}
			$.messager.show({
				title: '提示',
				msg: res.message,
				timeout: 3500,
				showType: 'slide'
			});
		}
	});

	jq_modify_password.on('click', function(){
		switch_reset_password();
	});
})
function save_content(){
	var a_id = jq_content_id.val();
	if (!a_id){
		return false;
	}
	jq_content_form.submit();
}
function search_content(){
	var search = jq_ss.searchbox('getValue');
	var filter_status = jq_filter_status.combobox('getValue');
	var param = {
		search: search,
		filter_status: filter_status
	};
	jq_dg_content.datagrid({
		pageNum: 1,
		queryParams: param
	});
}
function init_reset_password(){
	jq_modify_password.attr('checked', false);
	switch_reset_password();
}
function switch_reset_password(){
	if (jq_modify_password.is(':checked')){
		jq_modify_password_box.show();
	} else {
		jq_modify_password_box.hide();
	}
	jq_new_password.val('');
	jq_confirm_new_password.val('');
}
//批量修改状态
function multiple_set_status(status){
	var rows = jq_dg_content.datagrid('getChecked');
	var status_text = '激活';
	if (status == -2){
		status_text = '删除';
	}
	if (rows.length == 0){
		$.messager.show({
			title: '提示',
			msg: '请选择需要批量' + status_text + '的用户~',
			timeout: 3500,
			showType: 'slide'
		});
		return false;
	}
	var ids = [];
	for (i = 0;i < rows.length;i ++){
		ids.push(rows[i]['_id']);
	}
	$.messager.progress({
		title: '操作中，请稍后!'
	});
	$.ajax({
		url: module_router + '/multipleSetStatus',
		type: 'post',
		data: {status : status,ids : ids.join(',')},
		dataType: 'json',
		success: function(res){
			$.messager.progress('close');
			$.messager.show({
				title: '提示',
				msg: res.message,
				timeout: 3500,
				showType: 'slide'
			});
			if (res.success){
				jq_dg_content.datagrid('reload');
			}
		}
	});
}
</script>
