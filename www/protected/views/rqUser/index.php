<style>
	.f_label {width: 90px;}
	.accordion-body {padding: 0;}
	#view_select_position {
		display:inline-block;
		padding:1px 4px 1px 4px;
		border:1px solid #999999;
		text-decoration:none;
		color:#333333;
	}
</style>

<div id="main">
	<div region="west" border="false" id="west_panel">
		<table id="dg_content"></table>
		<div id="tb_content">
			<div class="tb_line">
				<input id="ss" />
			</div>
			<div class="tb_line">
				<span class="tb_label">注册时间</span>
				<span class="tb_label">开始</span>
				<input type="text" id="date_start_order" style="width:100px;" />
				<span class="tb_label">结束</span>
				<input type="text" id="date_end_order" style="width:100px;" />
				<div class="right">
					<a href="#" class='easyui-linkbutton' iconCls="icon-search" plain="true" onclick="search_content();return false;">查询</a>
				</div>
			</div>

		</div>
	</div>
	<div id="acc_container" class="accordion" region="center">
		<div region="center" title="订单信息" data-options="iconCls:'icon-save',selected:true">
			<div class="easyui-layout detail_layout">
				<div data-options="region:'center'" class="detail_center">
					<div class="detail_main">
						<form id="content_form" method="post">
							<ul>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>ID: </span>
										</div>
										<div class="box_flex f_content">
											<span id="id_str"></span>
										</div>
									</div>
								</li>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>用户名: </span>
										</div>
										<div class="box_flex f_content">
											<span id="user_name"></span>
										</div>
									</div>
								</li>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>注册时间: </span>
										</div>
										<div class="box_flex f_content">
											<span id="regist_time_i"></span>
										</div>
									</div>
								</li>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>手机号: </span>
										</div>
										<div class="box_flex f_content">
											<span id="phone"></span>
										</div>
									</div>
								</li>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>钱包余额: </span>
										</div>
										<div class="box_flex f_content">
											<span id="account_money"></span>
										</div>
									</div>
								</li>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>总收入: </span>
										</div>
										<div class="box_flex f_content">
											<span id="count_money"></span>
										</div>
									</div>
								</li>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>总支出: </span>
										</div>
										<div class="box_flex f_content">
											<span id="pay_money"></span>
										</div>
									</div>
								</li>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>话题数: </span>
										</div>
										<div class="box_flex f_content">
											<span id="post_num"></span>
										</div>
									</div>
								</li>
								<li class="f_item">
									<div class="box">
										<div class="f_label">
											<span>动态数: </span>
										</div>
										<div class="box_flex f_content">
											<span id="flist_count"></span>
										</div>
									</div>
								</li>





							</ul>
						</form>
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
</div>
<div style="display:none;">
	<div id="refund_tip_dialog" style="padding: 30px 0;">
		<div style="text-align:center;"><span id="refund_tip"></span></div>
	</div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/coolautosuggest/jquery.coolautosuggest.css" />
<script type="text/javascript">
  var jq_dg_content = $('#dg_content');
  var jq_content_form = $('#content_form');
  var module_router = site_root + '/index.php?r=RqUser';
  var w_width = $(window).width();
  var w_height = $(window).height();
  var jq_ss = $('#ss');

  var jq_date_start_order = $('#date_start_order');
  var jq_date_end_order = $('#date_end_order');
  var jq_acc = $('#acc_container');
  var jq_resetTec_form = $('#resetTec_form');
  $(function(){

    jq_acc.accordion({
      height: w_height - 18,
      onSelect: function(title) {

      }
    });

    jq_date_start_order.datebox({});
    jq_date_end_order.datebox({});






    var buttons = $.extend([], $.fn.datebox.defaults.buttons);
    buttons[0].text = '确定';
    buttons[0].handler=function(){
      var vals = $('#booking_time').datetimebox('spinner').spinner('getValue').split(':');
      $('#booking_time_str').val(  parseInt($('#booking_time_str').val())-(parseInt($('#booking_time_str').val())+8*3600)%86400 +vals[0]*3600+vals[1]*60);

      var d=new Date(parseInt($('#booking_time_str').val())*1000);

      $('#booking_time').datetimebox('setText',formatDate(d));
      $('#booking_time').datetimebox('hidePanel');
    };

    $('#booking_time').datetimebox({
      required: false,
      showSeconds:false,
      buttons:buttons,
      onSelect: function(date){
        var currentDate = new Date();
        //console.log(date)
        // if(currentDate>=date){
        //     $.messager.show({
        //         title: '提示',
        //         msg: '必须选择现在之后的日期',
        //         timeout: 3500,
        //         showType: 'slide'
        //     });
        // }else{
        $('#booking_time_str').val(date.getTime()/1000);
        // }
      }
    });

    $('#booking_time_add').datetimebox({
      required: false,
      showSeconds:false,
      //buttons:buttons_add,
      onSelect: function(date){
        var currentDate = new Date();
        $('#booking_time_str_add').val(date.getTime()/1000);
      }
    });

    $('#order_time_add').datetimebox({
      required: false,
      showSeconds:false,
      //buttons:buttons_add,
      // onSelect: function(date){
      //     var currentDate = new Date();
      //     $('#order_time_str_add').val(date.getTime()/1000);
      // }
    });

    $('#deal_time').datetimebox({
      required: false,
      showSeconds:false,
      //buttons:buttons_deal,
      // onSelect: function(date){
      //     console.log(date);
      //     $('#deal_time_str').val(date.getTime()/1000);
      // }
    });




    var p_width = parseInt(w_width / 2);
    if (p_width < 520){
      p_width = 520;
    }
    var d_width = p_width - 10;
    $('#west_panel').css({width : p_width});
    $('#main').css({width: w_width - 25, height: w_height - 18}).layout();

    jq_ss.searchbox({
      width: 130,
      searcher:function(value){
        search_content();
      },
      prompt: '请输入关键字'
    });




    jq_dg_content.datagrid({
      url: module_router + '/list',
      title: '用户列表',
      width: d_width,
      height: w_height - 18,
      fitColumns: true,
      autoRowHeight: true,
      striped: true,
      toolbar: '#tb_content',
      singleSelect: true,
      selectOnCheck: false,
      checkOnSelect: false,
      pagination: true,
      pageList: [20, 30, 50],
      pageSize: 20,
      nowrap: false,
      idField: 'id',
      sortName: 'order_time',
      sortOrder: 'desc',
      queryParams: get_param_obj(),
      frozenColumns:[[
        {field:'ck',checkbox:true}
      ]],
      columns:[[
        {field:'id', title:'id', hidden:true},
	      {field:'uid',title:'UID',width:60,sortable:true},
	      {field:'user_name',title:'用户名称',width:60,sortable:true},
        {field:'regist_time_i', title:'注册时间', width:60,sortable:true,formatter: function(value, row){
          var now=new Date(value*1000);

          var   month=now.getMonth()+1;
          var   date=now.getDate();
          var   hour = now.getHours();
          return   month+"-"+date+" "+hour+":00";
        }},
        {field:'phone',title:'手机号',width:60,sortable:true},

      ]],

      onSelect: function(index, row){
				$.ajax({
          url: module_router + '/detail',
          type: 'POST',
          data: {
            uid : row.uid
          },
          dataType: 'json',
					success : function (e) {
						var data = e.data;
            $('#id_str').html(data.uid);
            $('#count_money').html(data.count_money);
            $('#pay_money').html(data.pay_money);
            $('#post_num').html(data.post_num);
            $('#flist_count').html(data.flist_count);
            $('#account_money').html(data.account_money);
            $('#phone').html(data.phone);
            var now=new Date(data.regist_time_i*1000);
            var   month=now.getMonth()+1;
            var   date=now.getDate();
            var   hour = now.getHours();
            $('#regist_time_i').html(month+"-"+date+" "+hour+":00");
            $('#user_name').html(data.user_name);
          },
					error : function (e) {
						console.log(e);
          }
				});

      },

      onLoadSuccess: function(){
      }
    });

    jq_ss.searchbox({
      width: 140,
      searcher:function(value){
        search_content();
      },
      prompt: '请输入关键字'
    });








    jq_content_form.form({
      url: module_router + '/edit',
      onSubmit: function(param){
        if ($('#order_id').val() == ""){
          return false;
        }
        var isValid = $(this).form('validate');
        if (!isValid){
          $.messager.progress('close');
        }
        return isValid;
      },
      success: function(res){
        $.messager.progress('close');
        var res = JSON.parse(res);

        if (res.success){
          jq_dg_content.datagrid('reload');
        }
        if(res.success){
          $.messager.show({
            title: '提示',
            msg: '保存成功',
            timeout: 3500,
            showType: 'slide'
          });

          $('#technician_id').val(0);
        }else{
          $.messager.show({
            title: '提示',
            msg: res.message,
            timeout: 3500,
            showType: 'slide'
          });
        }
      }
    });


  });

  function search_content(){
    var date_start_order = jq_date_start_order.datebox('getValue');
    var date_end_order = jq_date_end_order.datebox('getValue');

    var search = jq_ss.searchbox('getValue');
    jq_dg_content.datagrid({
      pageNum: 1,
      queryParams: {
        search : search,
        date_start_order : date_start_order,
        date_end_order : date_end_order,
      }
    });
  };

  function save_content(){

  }



  function   formatDate(now){
    var   year=now.getFullYear();
    var   month=now.getMonth()+1;
    var   date=now.getDate();
    var  hour = now.getHours();
    var  minute = now.getMinutes();
    return   year+"-"+month+"-"+date+" "+hour+":"+minute;
  }






</script>