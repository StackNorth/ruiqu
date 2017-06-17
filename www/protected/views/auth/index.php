<div id="main">
	<div id="aa">
		<div title="角色管理">
			<table id="dg_role"></table>
			<div id="tb_role">
				<a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_role.edatagrid('addRow');" iconCls="icon-add" plain="true" >新增</a>
			    <a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_role.edatagrid('destroyRow');" iconCls="icon-remove" plain="true" >删除</a>
			    <a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_role.edatagrid('saveRow');" iconCls="icon-save" plain="true" >保存</a>
			    <a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_role.edatagrid('cancelRow');" iconCls="icon-undo" plain="true" >取消</a>
			</div>
		</div>
		<div title="任务管理">
			<table id="dg_task"></table>
			<div id="tb_task">
				<a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_task.edatagrid('addRow');" iconCls="icon-add" plain="true" >新增</a>
			    <a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_task.edatagrid('destroyRow');" iconCls="icon-remove" plain="true" >删除</a>
			    <a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_task.edatagrid('saveRow');" iconCls="icon-save" plain="true" >保存</a>
			    <a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_task.edatagrid('cancelRow');" iconCls="icon-undo" plain="true" >取消</a>
			</div>
		</div>
		<div title="操作管理">
			<table id="dg_operation"></table>
			<div id="tb_operation">
			    <a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_operation.edatagrid('saveRow');" iconCls="icon-save" plain="true" >保存</a>
			    <a href="#" class="easyui-linkbutton" onclick="javascript:jq_dg_operation.edatagrid('cancelRow');" iconCls="icon-undo" plain="true" >取消</a>
			    <div class="right">
			    	<a href="#" class="easyui-linkbutton" onclick="javascript:scan_operation();return false;" iconCls="icon-search" plain="true">扫描控制器</a>
			    </div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var post_url = 'post_data/';
var get_url = 'get_data/';

var jq_dg_role = $('#dg_role');
var jq_dg_task = $('#dg_task');
var jq_dg_operation = $('#dg_operation');

var temp = new Date();
var today = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
var w_width = $(window).width();
var w_height = $(window).height();
var operations = [];
var tasks = [];
$(function(){
	$('#aa').accordion({
		width: w_width - 25
	});
	jq_dg_role.edatagrid({
		url: site_root + '/index.php?r=auth/getItems',
		width: w_width - 50,
		height: w_height - 110,
	    fitColumns: true,
	    autoRowHeight: true,
	    striped: true,
	    multiSort: true,
	    toolbar: '#tb_role',
	    singleSelect: true,
	    pagination: true,
	    pageList: [20, 30, 50],
	    pageSize: 20,
	    nowrap: false,
	    idField: '_id',
	    queryParams: {type : 2},
	    columns:[[
	        {field:'name', title:'角色', width:40, sortable: true, editor: {type:'validatebox', options: {required: true}}},
	        {field:'desc', title:'说明', width:40, editor: {type:'validatebox'}},
	        {field:'children', title:'操作', width:40, editor: {type:'combobox', options: {multiple: true, data:[]}},
	        	formatter: function(value, row){
	        		if (value){
	        			var arr = value.split(',');
	        			return arr.join(',');
	        		} else {
	        			return '';	
	        		}   		
	        	}
	        }
	    ]],
	    onBeforeEdit: function(index, row){
	    	var that = this;
	    	setTimeout(function(){
	    		var children_editor = $(that).edatagrid('getEditor', {index: index, field: 'children'});
	    		if (children_editor){
	    			$(children_editor.target).combobox('loadData', tasks);
	    		}	
	    	}, 100);
	    },
	    saveUrl: site_root + '/index.php?r=auth/insertItem',
	    updateUrl: site_root + '/index.php?r=auth/updateItem',
	    destroyUrl: site_root + '/index.php?r=auth/removeItem',
	    dataSave: {type : 2},
		dataUpdate: {type : 2},
	    onSave: function(index, row){
			$(this).edatagrid('reload');
		},
		onDestroy: function(index, row){
			$(this).edatagrid('reload');
		},
		newRow: {children: ''},
		onLoadSuccess: function(res){
			if (res.hasOwnProperty('more')){
				roles = res.more;
			}
		}
	});
	jq_dg_task.edatagrid({
		url: site_root + '/index.php?r=auth/getItems',
		width: w_width - 50,
		height: w_height - 110,
	    fitColumns: true,
	    autoRowHeight: true,
	    striped: true,
	    multiSort: true,
	    toolbar: '#tb_task',
	    singleSelect: true,
	    pagination: true,
	    pageList: [20, 30, 50],
	    pageSize: 20,
	    nowrap: false,
	    idField: '_id',
	    queryParams: {type : 1},
	    columns:[[
	        {field:'name', title:'任务', width:40, sortable: true, editor: {type:'validatebox', options: {required: true}}},
	        {field:'desc', title:'说明', width:40, editor: {type:'validatebox'}},
	        {field:'children', title:'操作', width:40, editor: {type:'combobox', options: {multiple: true, data:[]}},
	        	formatter: function(value, row){
	        		if (value){
	        			var arr = value.split(',');
	        			return arr.join(',');
	        		} else {
	        			return '';	
	        		}   		
	        	}
	        }
	    ]],
	    onBeforeEdit: function(index, row){
	    	var that = this;
	    	setTimeout(function(){
	    		var children_editor = $(that).edatagrid('getEditor', {index: index, field: 'children'});
	    		if (children_editor){
	    			$(children_editor.target).combobox('loadData', operations);
	    		}	
	    	}, 100);
	    },
	    saveUrl: site_root + '/index.php?r=auth/insertItem',
	    updateUrl: site_root + '/index.php?r=auth/updateItem',
	    destroyUrl: site_root + '/index.php?r=auth/removeItem',
	    dataSave: {type : 1},
		dataUpdate: {type : 1},
	    onSave: function(index, row){
			$(this).edatagrid('reload');
		},	
		onDestroy: function(index, row){
			$(this).edatagrid('reload');
		},
		newRow: {children: ''},
		onLoadSuccess: function(res){
			if (res.hasOwnProperty('more')){
				tasks = res.more;
			}
		}
	});
	jq_dg_operation.edatagrid({
		url: site_root + '/index.php?r=auth/getItems',
		width: w_width - 50,
		height: w_height - 110,
	    fitColumns: true,
	    autoRowHeight: true,
	    striped: true,
	    multiSort: true,
	    toolbar: '#tb_operation',
	    singleSelect: true,
	    pagination: true,
	    pageList: [20, 30, 50],
	    pageSize: 20,
	    nowrap: false,
	    idField: '_id',
	    queryParams: {type : 0},
	    sortName: 'name',
	    columns:[[
	        {field:'name', title:'操作', width:40, sortable: true},
	        {field:'desc', title:'说明', width:40, editor: {type:'validatebox'}}
	    ]],
	    updateUrl: site_root + '/index.php?r=auth/updateItem',
		dataUpdate: {type : 0},
	    onSave: function(index, row){
			$(this).edatagrid('reload');
		},	
		onDestroy: function(index, row){
			$(this).edatagrid('reload');
		},
		onLoadSuccess: function(res){
			if (res.hasOwnProperty('more')){
				operations = res.more;
			}
		}
	});
});
function scan_operation(){
	$.messager.confirm('确认', '确认扫描控制器?', function(r){
		if (r){
			$.messager.progress({
				msg : '扫描中'
			})
			$.ajax({
				url: site_root + '/index.php?r=auth/scanOperation',
				type: 'get',
				dataType: 'json',
				success: function(res){
					if (res.success){
						$.messager.progress('close');
						jq_dg_operation.edatagrid('reload');
					}			
				}
			});
		}
	});
}
</script>