;
(function ($) {

    var defaults = {
        max_pics:8,
        browse_button: 'add_pic',
        container: 'container',
        drop_element: 'container', 
        max_file_size: '10mb',
        dragdrop: true,
        chunk_size: '4mb',
        uptoken_url: site_root + '/index.php?r=site/Gettoken&type=1',
        domain: 'http://iyaya-neighborhood.u.qiniudn.com/',
        auto_start: true
    };
    var options = null;
    
    var privateVal = function() {
        return {
            post_lock:false,
            loading_pic: site_root + '/images/load_img.gif',//需要修改
            no_img : site_root + '/image/no_img.png',//需要修改       
            runtimes: 'html5,html4,flash',
            thumb_suffix: '?imageView2/1/w/100/h/100',//缩略图的后缀
            fancy_js_url: site_root + '/js/fancybox/source/jquery.fancybox.js',
            fancy_css_url: site_root + '/js/fancybox/source/jquery.fancybox.css',
            ele_id:'',//显示图片编辑器的dom
            controllerHTML:'<div class="tip_layer"><div class="photo_list" style="display: block;"><ul><li class="on" id="add_pic"></li></ul><p class="text_tip">最多可上传options_max_pics张图片</p></div></div>',
            controllerCSS:'' +
                    '.tip_layer {\
                        position: relative;\
                        color: #333;\
                    }\
                    .photo_list {\
                        margin: 15px 10px;\
                        padding-top: 5px;\
                    }\
                    .photo_list li {\
                        float: left;\
                        margin: 0 15px 15px 0;\
                        position: relative;\
                    }\
                    .photo_list .on, .mask_lay{\
                        width: 60px;\
                        height: 60px;\
                    }\
                    .photo_cut {\
                        width: 60px;\
                        height: 60px;\
                        overflow: hidden;\
                    }\
                    .photo_cut img {\
                        min-width: 80px;\
                        max-width: 80px;\
                    }\
                    .c_btn {\
                        right: -10px;\
                        top: -10px;\
                        width: 25px;\
                        height: 25px;\
                        background: url(http://dzqun.gtimg.cn/quan/images/sprBg.png?t=) no-repeat;\
                        background-size: 400px auto;\
                        -webkit-background-size: 400px auto;\
                        text-indent: -9999px;\
                        position: absolute;\
                    }\
                    .photo_list .on {\
                        background: #d9d9d9 url(http://dzqun.gtimg.cn/quan/images/sprBg.png?t=) no-repeat -244px -4px;\
                        margin-right: 0;\
                        cursor: pointer;\
                    }\
                    .mask_lay {\
                        position: absolute;\
                        left: 0;\
                        top: 0;\
                        -moz-opacity: 0.6;\
                        opacity: 0.6;\
                        background-color: #000;\
                    }\
                    .text_tip {\
                        width: 100%;\
                        line-height: 21px;\
                        font-size: medium;\
                        color: #afafaf;\
                        clear: both;\
                    }',
            extensions:"jpg,gif,png,jpeg"
        }
    } ();
  
    var privateFunction = {
        
        return_val:function(){

        },
        show_info:function(info){
            $.messager.show({
                title: '提示',
                msg: info,
                timeout: 3500,
                showType: 'slide'
            });
        },
        init_uploader:function(){
            var me  = this;
            var uploader = new Qiniu.uploader({
                runtimes: privateVal.runtimes,
                chunk_size : '1mb',

                browse_button: options.browse_button,
                container: options.container,
                drop_element: options.drop_element,
                max_file_size: options.max_file_size,
                flash_swf_url: 'plupload/Moxie.swf',
                dragdrop: options.dragdrop,
                uptoken_url: options.uptoken_url,
                domain: options.domain,
                auto_start: true,
                //unique_names: true,
                // 默认 false，key为文件名。若开启该选项，SDK会为每个文件自动生成key（文件名）
                //save_key: true,
                // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK在前端将不对key进行任何处理

                filters : {
                    max_file_size: options.max_size,
                    prevent_duplicates:true,
                    mime_types: [
                        {title : "Image files", extensions: privateVal.extensions}
                    ]
                },
                resize: {
                    width : 800,
                    height : 600,
                    quality : 90,
                    crop: true
                },
                init: {

                    FilesAdded: function(up, files) {

                        var totalfiles = files.length + options.uploaded_pics.length;
                        if (totalfiles > options.max_pics) {
                            me.showInfo('最多只能上传'+options.max_pics+'张图片呢');
                            //uploader.splice();
                            return false;
                        }

                        plupload.each(files, function(file) {
                            console.log(file);
                            var loading_img = new Image();
                            loading_img.src = privateVal.loading_pic;
                            loading_img.onload = function(){
                                var loading = '<li class="upload_loading"><div class="photo_cut"><img src="'+privateVal.loading_pic+'" width="60" height="60"/></div></li>';
                                $('#'+options.browse_button).before(loading);
                            }

                        });
                    },
                  
                    FileUploaded:function (up, file, info) {
                        var res = $.parseJSON(info);
                        var url = options.domain + encodeURI(res.key);
                        var imageThumb = url+privateVal.thumb_suffix;

                         
                        console.log('info:');

                        console.log(res.key); 
                        console.log('file:');

                        console.log(file.id);

                        var img = new Image();
                        img.src = imageThumb;
                        img.onload = function(){
                        

                         //   var del_img = privateFunction.Del_img();
                            $('.photo_list ul li.upload_loading').last().find('img').attr('src',imageThumb).parent().append('<a href="javascript:;" id="img_'+file.id+'" key="'+res.key+'" class="c_btn spr db " title="">关闭</a>').parent().removeClass('upload_loading');
                            $('#img_'+file.id).on("click",function(){
                                console.log($(this).prev().attr('init').replace(privateVal.thumb_suffix, ""));

                                //console.log($(this).prev().attr('init').replace(privateVal.thumb_suffix, ""));
                                //privateFunction.remove(options.uploaded_pics,($(this).prev().attr('src').replace(privateVal.thumb_suffix, "")));
                                privateFunction.remove(options.uploaded_pics,($(this).prev().attr('init').replace(privateVal.thumb_suffix, "")));
                                $(this).parent().parent().remove();
                                return false;
                            });
                        };

                        options.uploaded_pics.push(url);
                        console.log(options.uploaded_pics);
                    },
                    'Key': function(up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在 unique_names: false , save_key: false 时才生效
                        var key = 'admin_'+Date.parse(new Date());
                        // do something with key here
                        return key;
                    },
                    UploadComplete:function(up, files){//所有上传成功
                        //$('#success').show();
                    },
                    'Error': function(up, err, errTip) {
                        console.log(err);
                        console.log(errTip);
                        me.show_info('上传失败，请稍后再试');
                        $('.photo_list ul li.upload_loading').last().remove();
                    }
                }

            });
        },
        indexOf:function(arr,val) {
            for (var i = 0; i < arr.length; i++) {
                if (arr[i] == val) return i;
            }
            return -1;
        },
        remove:function(arr,val) {

           
            var index = privateFunction.indexOf(arr,val);
            if (index > -1) {
                arr.splice(index, 1);
            }

            console.log(arr);
        },
        include_fancybox:function(){$("<script>").attr({
                src: privateVal.fancy_js_url
            }).appendTo("head");

            $("<link>").attr({ rel: "stylesheet",
                    type: "text/css",
                    href: privateVal.fancy_css_url
                }).appendTo("head");
        },
        include_resource:function(){
            var resources = [site_root + '/js/plupload/plupload.full.min.js', site_root + '/js/qiniu.js'];
            for(r in resources){
                $("<script>").attr({
                    src: resources[r]
                }).appendTo("head");
            }

        },
        createCSS: function() {
            var j = document.createElement("style");
            j.type = "text/css";
            j.innerHTML = privateVal.controllerCSS;
            document.getElementsByTagName("head")[0].appendChild(j);
        },
        createHTML: function() {
            $(".image_editor").remove();
            var j = document.createElement("div");
            j.id = "editor_" + new Date().getTime();
            j.className="image_editor";
            j.innerHTML = privateVal.controllerHTML.replace('options_max_pics',options.max_pics);
            $('#'+options.ele_id).before(j).css('padding-top',50); 
        },
        init_events:function(){
            
            $('.photo_cut a.c_btn').on("click",function(){
                //alert('click');
                console.log($(this).prev().attr('init').replace(privateVal.thumb_suffix, ""));
                privateFunction.remove(options.uploaded_pics,$(this).prev().attr('init').replace(privateVal.thumb_suffix, ""));
                $(this).parent().parent().remove();
                return false;
            });
        },
        init_dom:function(){

            //var _class = (options["addClass"])?"photo_cut "+options.addClass:"photo_cut";
            for(var i in options.uploaded_pics){
                var img = new Image();
                img.src = options.uploaded_pics[i]+privateVal.thumb_suffix;
                img.id = options.uploaded_pics[i].substring(60,69).replace(/\.jpg$/,'')+new Date().getTime();
                var loading = '<li class="upload_loading"><div class="photo_cut"><img id="img_'+img.id+'" src="'+privateVal.loading_pic+'" init="'+options.uploaded_pics[i]+'"/></div></li>';

                 if(options["fancyboxType"])
                    {
                        img.src = options.uploaded_pics[i];
                        var loading = '<li class="upload_loading"><div class="photo_cut">'+
                                      '<a href="'+img.src+'" class="fancybox" rel="fancybox-textarea">'+
                                      '<img id="img_'+img.id+'" src="'+privateVal.loading_pic+'"   init="'+options.uploaded_pics[i]+'" style="width: 100px;"/>'+
                                      '</a></div></li>';
                    }

                $('#'+options.browse_button).before(loading);

                img.onload = function(){
                    //var img_dom = '<li class="upload_loading"><div class="photo_cut"><img src="'+options.uploaded_pics[i]+'" /></div></li>';
                    //$('#'+options.browse_button).before(img_dom);

                    //$('.photo_list ul li.upload_loading').last().find('img').attr('src',this.src).parent().append('<a href="javascript:;" id="img_'+this.id+'" class="c_btn spr db " title="">关闭</a>').parent().removeClass('upload_loading');
                    
                    $("#img_"+this.id).attr('src',this.src);
                    $("#img_"+this.id).parent().append('<a href="javascript:;" id="remove_'+this.id+'" class="c_btn spr db " title="">关闭</a>').parent().removeClass('upload_loading');

                    $('#remove_'+this.id).on("click",function(){
                                console.log($(this).prev().attr('init'));
                                console.log($(this).prev().attr('init').replace(privateVal.thumb_suffix, ""));
                                privateFunction.remove(options.uploaded_pics,$(this).prev().attr('init').replace(privateVal.thumb_suffix, ""));
                                $(this).parent().parent().remove();
                                return false;
                            });
                } 
            }
        },
        other_events:function()
        {
           
            if(options["readOnlyImg"])
            {
                $("#add_pic,.text_tip").remove();
            }
        }

    }


    var methods = {
        init: function(args) {
            options = $.extend(defaults, args);

            if(!options.uploaded_pics){
                privateFunction.show_info('uploaded_pics参数不能为空');
            }

            privateFunction.include_resource(); //加载QiNiu  js
            privateFunction.createCSS();
            privateFunction.createHTML();
             if(options.include_fancybox==1)
             {

                privateFunction.include_fancybox(); 
             }
            if(options.uploaded_pics.length){


                // for(var x in options.uploaded_pics)
                // {
                //     options.uploaded_pics[x] = encodeURI(options.uploaded_pics[x]);

                //     //console.log(options.uploaded_pics[x]); 
                // }
                // console.log(options.uploaded_pics);
                privateFunction.init_dom();
            }
            privateFunction.init_events();
            privateFunction.init_uploader();
            privateFunction.other_events();
           
        },
        pushYourFace:function(args)
        {

           // console.log(args);

        },
        getPics:function()
        {
            return options.uploaded_pics;
        }
    };



    $.fn.image_editor = function() {
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
    
    if (typeof(site_root) == 'undefined'){
		site_root = 'http://admin.ddxq.mobi';
	}

})(jQuery);