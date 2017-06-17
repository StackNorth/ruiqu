<div id="main">
	<table id="tg_content"></table>
	<div id="tb_content">
	    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add_menu(1);return false">新增同级目录</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add_menu();return false">新增子目录</a>
	    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit_menu();return false">编辑</a>
	    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delete_menu();return false">删除</a>
	    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="save_menu();return false">保存</a>
	    <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="cancel_menu();return false">取消</a>
	    <div class="right">
	    	<a href="#" onclick="expand_tree();return false;" iconCls="icon-add" class="easyui-linkbutton" plain="true">展开</a>
    		<a href="#" onclick="collapse_tree();return false;" iconCls="icon-remove" class="easyui-linkbutton" plain="true">收起</a>
	    	<span>状态: </span>
	    	<input id="filter_status" />
	    </div>
	</div>
</div>
<script type="text/javascript">
var status_data = <?php echo json_encode($status); ?>;
var jq_tg_content = $('#tg_content');
var temp_id = 100000;
var max_id = 100000;
var max_id_decrese = 100000;
var max_level = 3;
var edit_row_id = '';
var prevent_edit = 0;
var w_width = $(window).width();
var w_height = $(window).height();
var jq_filter_status = $('#filter_status');
var tree_status = 1;
$(function(){
	jq_filter_status.combobox({
		editable: false,
		data: status_data,
		onSelect: function(){
			search_content();
		}
	});
	jq_tg_content.treegrid({
		height: w_height - 20,
		width: w_width - 25,
		title: '目录管理',
		idField: '_id',
    	treeField: 'name',
		url: site_root + '/index.php?r=adminMenu/getRows',
    	toolbar: '#tb_content',
    	nowrap: false,
    	fitColumns: true,
    	singleSelect: true,
	    queryParams:{},
	    pagination: true,
	    pageSize: 10,
	    pageList: [10,20,50],
	    onClickRow: function(row){
	    	if (edit_row_id != '' && edit_row_id != row._id){
	    		save_menu();
	    	}
	    },
	    onDblClickRow: function(row){
	    	var menu = $(this).treegrid('getSelected');
	    	if (menu && prevent_edit == 0){
	    		edit_menu();
	    	}
	    },
		columns: [[
	        {field:'name', title:'名称', width:100, editor:{type: 'validatebox', options:{required: true}}},
	        {field:'url', title:'链接', width:100, editor:{type: 'validatebox', options:{}}},
	        {field:'sort', title:'排序', width:100, editor:{type: 'numberbox', options:{required: true}}},
			{field:'status', title:'状态', width:50,
	        	formatter: function(value, row){
					if (value == 1){
						return '正常';
					} else {
						return '删除'
					}
			  	}
			}
	    ]],
	    onBeforeExpand: function(row){
	    	prevent_edit = 1;
    		setTimeout(function(){
				prevent_edit = 0;
    		}, 500);
	    	if (!row.hasOwnProperty('children') || row.children.length == 0){
				return false;
			}
	    },
	    onBeforeEdit: function(row){
	    	$(this).treegrid('addEditor', {field : 'status', editor : {type : 'checkbox', options: {on : 1, off : -1}}});
	    	var that = this;
			setTimeout(function(){			
				var editors = $(that).treegrid('getEditors', row._id);
				var use_editors = {};
				for (var x in editors){
					if ($.inArray(editors[x]['field'], ['url', 'name', 'sort']) > -1){
						use_editors[editors[x]['field']] = editors[x];
					}
				}
				var roots = $(that).treegrid('getRoots');
				var sort = '';
	    		if (row.level == 1){ 
					sort = roots.length;
	    		} else {   //答案	
	    			var parent = $(that).treegrid('getParent', row._id);
	    			sort = parent.children.length;
	    		}
	    		if (use_editors['sort'].target.numberbox('getValue') == 0){
	    			use_editors['sort'].target.numberbox('setValue', sort);
	    		}
				for (x in use_editors){
					if (use_editors[x]['type'] != 'combobox'){
						use_editors[x].target.bind('keyup', function(event){
							if (event.keyCode == '13'){
								save_menu();
						   	}
						});
					}
				}
			}, 100);
	    },
	    onAfterEdit: function(row, changes){
	    	var act = '';
	    	if (row.id >= max_id){
    			act = 'insertRow';
    		} else {
    			act = 'updateRow';
	    	}
	    	$.messager.progress();
	    	var that = this;
	    	$.ajax({
				type: 'post',
	         	url: site_root + '/index.php?r=adminMenu/' + act,
	     	 	dataType: 'json',
		     	data: row,
		     	success: function(data){	     		
		     		$.messager.progress('close');
		     		if (data.success){
		     			edit_row_id = '';
		     			if (act == 'insertRow'){	     				
		     				var old_row = $(that).treegrid('pop', row._id);
		     				old_row._id = data.data._id;
		     				old_row.id = -- max_id_decrese;
		     				$(that).treegrid('append', {parent: row.parent, data: [old_row]});
		     				$(that).treegrid('select', data.data._id);   				
		     			} else {
		     				$(that).treegrid('select', row._id);
		     			}
		     			var filter_status = jq_filter_status.combobox('getValue');
			    		if (filter_status < 10){
			    			if ((row.status != filter_status)){
			    				$(that).treegrid('remove', row._id);
			    			}
			    		}
			    		if (changes.hasOwnProperty('sort') && act == 'updateRow'){
			    			$(that).treegrid('reload');
			    		}   			
		     			$.messager.show({
							title: '提示',
							msg: data.message,
							timeout: 2500,
							showType: 'slide'
						});
		     		} else {		
		     			edit_menu();
						$.messager.alert('提示', data.message, 'error');
		     		}
		      	}
			});
	    },
	    onLoadSuccess: function(row, data){
	    	if (data.hasOwnProperty('total')){
	    		edit_row_id = '';
	    	}
	    	var expand_ids = $(this).data('expand_ids');
	    	if (expand_ids != undefined){
	    		for (var i = 0;i < expand_ids.length;i ++){
	    			$(this).treegrid('expand', expand_ids[i]);
	    		}	    		
	    	}
	    },
	    onExpand: function(row){
	    	var expand_ids = $(this).data('expand_ids');
	    	if (expand_ids == undefined){
	    		expand_ids = [];
	    	}
	    	if ($.inArray(row._id, expand_ids) == -1){
	    		expand_ids.push(row._id);
	    	}    	
	    	$(this).data('expand_ids', expand_ids);
	    },
	    onCollapse: function(row){
	    	var expand_ids = $(this).data('expand_ids');
	    	if (expand_ids == undefined){
	    		expand_ids = [];
	    	}
	    	if ($.inArray(row._id, expand_ids) > -1){
	    		expand_ids.splice($.inArray(row._id, expand_ids), 1);
	    	}    	
	    	$(this).data('expand_ids', expand_ids);
	    }
	});
});
//新增目录
function add_menu(){
	if (edit_row_id != ''){
		return false;
	}
	var add_folder = arguments[0] ? arguments[0] : '';	
	var row = jq_tg_content.treegrid('getSelected');
	var parent = '';
	var level = 1;
	if (add_folder == ''){
		if (row){
			if (row.level < max_level){
				level = row.level + 1;
				parent = row._id;
				jq_tg_content.treegrid('expand', row._id);
			} else {
				parent = row.parent;
				level = max_level;
			}
		} else {
			$.messager.alert('提示', '请选择一个目录', 'warning');
			return false;
		}
	} else {
		if (row){
			level = row.level;
			parent = row.parent;
			jq_tg_content.treegrid('expand', row.parent);
		}		
	}
	temp_id ++;
	var state = 'closed';
	if (level < max_level){
		state = 'closed';
	}
	var new_id = 'new_menu';
	jq_tg_content.treegrid('append', {
		parent: parent,
		data: [{
			_id: new_id,
			id: temp_id,
			parent: parent,
			name: '',
			status: 1,
			level: level,
			state: state,
			sort: 0,
			children: []
		}]
	}).treegrid('unselectAll').treegrid('select', new_id);
	edit_menu();
}
//编辑目录
function edit_menu(){
	if (edit_row_id != ''){
		jq_tg_content.treegrid('select', edit_row_id);
	}
	var row = jq_tg_content.treegrid('getSelected');
	if (row){
		edit_row_id = row._id;
		jq_tg_content.treegrid('beginEdit', row._id);	
	} else {
		$.messager.alert('提示', '请选择一个目录', 'warning');
		return false;
	}
}
//保存目录
function save_menu(){
	var row = jq_tg_content.treegrid('find', edit_row_id);
	if (row){
		jq_tg_content.treegrid('endEdit', row._id);
	}
}
//取消编辑
function cancel_menu(){
	var row = jq_tg_content.treegrid('find', edit_row_id);
	if (row){
		if (row.id >= max_id){
			jq_tg_content.treegrid('remove', row._id);
		} else {
			jq_tg_content.treegrid('cancelEdit', row._id);
		}
	}
	edit_row_id = '';
}
//删除目录
function delete_menu(){
	var params = {};
	if (edit_row_id != ''){
		var row = jq_tg_content.treegrid('find', edit_row_id);
		if (row.id >= max_id){
			jq_tg_content.treegrid('remove', edit_row_id);
			edit_row_id = '';
			return false;
		}
	}
	var row = jq_tg_content.treegrid('getSelected');
	if (!row){
		$.messager.alert('提示', '请选择需要删除的目录', 'warning');
		return false;
	}
	params._id = row._id;
	var confirm_note = '你确定要删除该目录吗?';
	if (row.children){
		if (row.children.length > 0){
			confirm_note += "<div style='color:red;margin-top:4px;'>该目录下面还有子目录，它们也会一起被删除!</div>";
		}
	}
	$.messager.confirm('提示', confirm_note, function(r){
		if (r){		
			$.messager.progress(); 
			$.ajax({
				type: 'post',
	         	url : site_root + '/index.php?r=adminMenu/deleteRow',
	     	 	dataType: 'json',
		     	data: params,
		     	error: function(){
		     		$.messager.progress('close');
		     	},
		     	success: function(data){
		     		$.messager.progress('close'); 
		     		if (data.success){
						jq_tg_content.treegrid('remove', row._id);
						$.messager.show({
							title: '提示',
							msg: data.message,
							timeout: 2500,
							showType: 'slide'
						});
		     		} else {
						$.messager.alert('提示', data.message, 'error');
		     		}
		      	}
			});
		}
	});
}
//筛选
function search_content(){
	var filter_status = jq_filter_status.combobox('getValue');
	jq_tg_content.treegrid({
		queryParams: {filter_status : filter_status},
		pageNumber: 1
	})
}
//展开tree
function expand_tree(){
	jq_tg_content.treegrid('expandAll');
	tree_status = 1;
}
//闭合tree
function collapse_tree(){
	jq_tg_content.treegrid('collapseAll');
	tree_status = 0;
}
</script>