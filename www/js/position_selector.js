;
(function ($) {

    var defaults = {
        width:800,//弹框显示宽度
        height:500,//弹框显示高度
        zoom:18,  //缩放级别
        locat:'上海',//默认城市
        can_edit:true,
        address:'',//地址
        lat:'',
        lng:'',
        func_callback:function(val){console.log(val);},//选择成功之后的回调函数
        element_id:'map_container'//弹窗ID
    };
    var options = null;

    var privateVal = function() {
            return {
                controllerHTML: ['<div id="r-result" style="padding:5px 0;">请输入:<input type="text" id="suggestId" size="20" value="余姚路288号汇智创意园" /> <input type="text" readonly="" id="position" >&nbsp;&nbsp;&nbsp;<a href="#" class="easyui-linkbutton l-btn l-btn-small" data-options=“iconCls:\'icon-add\'" group="" id="ret_val_botton"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">选择坐标</span></span></a></div><div id="searchResultPanel"></div><div id="allmap"></div></body></html>'].join(""),
                controllerCSS: '.tangram-suggestion-main{z-index:10000}#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;}#suggestId{width:200px;height:25px;line-height:25px;}#position{display:inline-block;background:#EBEBE4;border:#7F9DB9 solid 1px;color:#555;width:160px;height:25px;line-height:25px;font-size:14px;font-weight:700}#searchResultPanel{width:150px;height:auto;}',
                map : null,
                myValue:null,
                result:[]
            }
    } ();
    var privateFunction = {
        setPlace:function(){
            privateVal.map.clearOverlays();    //清除地图上所有覆盖物
            function myFun(){
                var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                privateVal.map.centerAndZoom(pp, options.zoom);
                privateVal.map.addOverlay(new BMap.Marker(pp));    //添加标注
                privateVal.result = [pp.lng,pp.lat,$('#suggestId').val()];
                $('#position').val(pp.lng+','+pp.lat);
            }
            var local = new BMap.LocalSearch(privateVal.map, { //智能搜索
                onSearchComplete: myFun
            });
            local.search(privateVal.myValue);
        },
        returnVal:function(){
            if(privateVal.result.length == 3){
                options.func_callback(privateVal.result);
            }else{
                $.messager.show({
                    title: '提示',
                    msg: '地址/坐标获取失败',
                    timeout: 3500,
                    showType: 'slide'
                });
            }
        },
        address_select:function(e){
            var _value = e.item.value;
            privateVal.myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            $("#searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />privateVal.myValue = " + privateVal.myValue;
            privateFunction.setPlace();
        },
        get_address_by_point:function(point,func){
            var gc = new BMap.Geocoder();
            gc.getLocation(point, function(rs){
                func(rs.address||'');
            });
        },
        address_hover:function(e){
            var str = "";
            var _value = e.fromitem.value;
            var value = "";
            if (e.fromitem.index > -1) {
                value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            }
            str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

            value = "";
            if (e.toitem.index > -1) {
                _value = e.toitem.value;
                value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            }
            str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
            $("#searchResultPanel").innerHTML = str;
        },
        create_window:function(){
            var boarddiv = "<div id='"+options.element_id+"'></div>";
            if(!$('#'+options.element_id)){
                $(document.body).append(boarddiv);
            }
            $('#'+options.element_id).window({
                width:options.width,
                height:options.height+50,
                title:'选择坐标',
                modal:true
            }).html('').append(privateVal.controllerHTML);

        },
        createCSS:function() {
            var j = document.createElement("style");
            j.type = "text/css";
            j.innerHTML = privateVal.controllerCSS;
            document.getElementsByTagName("head")[0].appendChild(j)
        },
        set_default_center:function(){
            privateVal.map.centerAndZoom(options.locat,options.zoom);// 初始化地图,设置中心点坐标和地图级别。
        },
        map_click:function(e){
            if(options.can_edit){
                privateVal.map.clearOverlays();
                var _point = new BMap.Point(e.point.lng,e.point.lat);
                var marker1 = new BMap.Marker(_point);  // 创建标注
                privateVal.map.addOverlay(marker1);              // 将标注添加到地图中
                $('#position').val(e.point.lng+','+e.point.lat);
                privateFunction.get_address_by_point(_point,function(_address){
                    privateVal.result = [_point.lng,_point.lat,$('#suggestId').val()];
                    $('#suggestId').val(_address);
                });
            }
        }
    }


    var methods = {

        init: function(args) {
            options = $.extend(defaults, args);
            privateFunction.createCSS();
            privateFunction.create_window();
            privateVal.map = new BMap.Map("allmap");

            if(!options.can_edit){
                $('#ret_val_botton').hide();
                $('#suggestId').attr('readonly',true);
            }
            privateVal.map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
            privateVal.map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
            privateVal.map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
            privateVal.map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
            privateVal.map.addControl(new BMap.OverviewMapControl());              //添加默认缩略地图控件
            privateVal.map.addControl(new BMap.OverviewMapControl({isOpen:true, anchor: BMAP_ANCHOR_TOP_RIGHT}));   //右上角，打开

            privateVal.map.addEventListener("click",function(e){
                privateFunction.map_click(e);
            });
            var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
                {
                    "input" : "suggestId"
                    ,"location" : options.locat
                });
            ac.addEventListener("onhighlight", function(e){
                privateFunction.address_hover(e);
            });

            ac.addEventListener("onconfirm", function(e){
                privateFunction.address_select(e);
            });

            $("#ret_val_botton").click(function(){
                privateFunction.returnVal();
                $('#'+options.element_id).window('close');
            });
            $('#suggestId').on('keydown',function(event){
                if(event.keyCode == "13")
                {
                    console.log($('#suggestId').val()+'#search');
                    // 创建地址解析器实例
                    var myGeo = new BMap.Geocoder();
                    // 将地址解析结果显示在地图上,并调整地图视野
                    myGeo.getPoint($('#suggestId').val(), function(point){
                        if (point) {
                            privateVal.map.clearOverlays();    //清除地图上所有覆盖物
                            privateVal.map.centerAndZoom(point, options.zoom);
                            privateVal.map.addOverlay(new BMap.Marker(point));    //添加标注
                            $('#position').val(point.lng+','+point.lat);
                            privateVal.result = [point.lng,point.lat,$('#suggestId').val()];
                        }else{
                            $.messager.show({
                                title: '提示',
                                msg: '地址查询不到呢',
                                timeout: 3500,
                                showType: 'slide'
                            });
                        }
                    }, options.locat);
                }
            });

            if(options.lat && options.lng){
                var pit = new BMap.Point(options.lng,options.lat);
                privateVal.map.centerAndZoom(pit, options.zoom);
                privateVal.map.addOverlay(new BMap.Marker(pit));
                $('#position').val(pit.lng+','+pit.lat);
                privateFunction.get_address_by_point(pit,function(_address){
                    $('#suggestId').val(_address);
                    privateVal.result = [pit.lng,pit.lat,$('#suggestId').val()];
                });
            }else if(options.address){
                var myGeo = new BMap.Geocoder();
                $('#suggestId').val(options.address);
                // 将地址解析结果显示在地图上,并调整地图视野
                myGeo.getPoint(options.address, function(point){
                    if (point) {
                        privateVal.map.centerAndZoom(point, options.zoom);
                        privateVal.map.addOverlay(new BMap.Marker(point));
                        $('#position').val(point.lng+','+point.lat);
                        privateFunction.get_address_by_point(point,function(_address){
                            $('#suggestId').val(_address);
                            privateVal.result = [point.lng,point.lat,$('#suggestId').val()];
                        });
                    }else{
                        privateFunction.set_default_center();
                    }
                }, options.locat);
            }else{
                privateFunction.set_default_center();
            }
        },
        destroy: function() {
            $('#'+options.element_id).window('close');
        }
    };

    $.fn.position_selector = function() {
        var method = arguments[0];
        if(methods[method]) {
            method = methods[method];
        } else if( typeof(method) == 'object' || !method ) {
            method = methods.init;
        } else {
            return this;
        }

        var args = arguments[1];
        return method.call(this,args);
    }

})(jQuery);