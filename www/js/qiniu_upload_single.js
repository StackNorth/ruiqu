;
(function ($) {
    var Qiniu = function (args) {
        this.defaults = {
            button:"#qiniu_uploader",
            qiniu_upload_url : 'http://up.qiniu.com',
            bucket:'',
            token:'',
            before_upload:function(){return true;},//上传之前调用的函数   各种判断和验证
            success_callback:function(result){},//上传成功之后的回调
            fail_callback:function(){}//上传失败之后的回调
        }
        this.privateVal = {
            token:{
                icons:'rjs8hPzTLArsZ7qkDRpEMripCvdDUumMaUWUqtLz:xYEXlmxKUnB-arJKsA8m_Dk_VLA=:eyJzY29wZSI6Imljb25zIiwiZGVhZGxpbmUiOjEuNDg2OTU0NzhlKzYwfQ==',
                pics:'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr:PhjO5GeGx1VECe1W7AlqUHZrxhg=:eyJzY29wZSI6InBpY3MiLCJkZWFkbGluZSI6MTQ3NDQ1MTg0OTAwMDAwMDAwMH0=',
                avatars:'rjs8hPzTLArsZ7qkDRpEMripCvdDUumMaUWUqtLz:ReX_j5RXbwFbzuhKlUCEuKAWEc0=:eyJzY29wZSI6ImF2YXRhcnMiLCJkZWFkbGluZSI6MTQ4Njk1NDc4MDAwMDAwMDAwfQ==',
                test:'rjs8hPzTLArsZ7qkDRpEMripCvdDUumMaUWUqtLz:PpMMGeMgfC0TmRJFYSJy66m6u6g=:eyJzY29wZSI6InRlc3QiLCJkZWFkbGluZSI6MS40ODY5NTQ3OGUrNjB9',
                video:'rjs8hPzTLArsZ7qkDRpEMripCvdDUumMaUWUqtLz:jjm7tc-k60b_F6Adfy_zZZrr37Q=:eyJzY29wZSI6InZpZGVvIiwiZGVhZGxpbmUiOjEuNDg2OTU0NzhlKzYwfQ=='
            },
            url_prefix:{
                pics:'http://olas7i3jz.bkt.clouddn.com',
                icons:'http://olasdjcaw.bkt.clouddn.com',
                avatars:'http://olas3bg3b.bkt.clouddn.com',
                test:'http://olask18vd.bkt.clouddn.com',
                video:'http://olasblwyl.bkt.clouddn.com'
            }
        }
        this.init(args);
    };

    Qiniu.prototype.init= function(args) {
        var options = $.extend(this.defaults, args);
        console.log('---------methodsoptions', options);
        var host = document.domain;
        if(host=='admin.yichenguanjiadev.me' || host=='admintest.yichenguanjia.me'){
            options.bucket = 'avatars';
        }

        options.token = this.privateVal.token[options.bucket];

        this.create_items(options);
    }
    Qiniu.prototype.create_items = function(options){
        var that = this;
        if($(options.button)){
            var width = $(options.button).width(),
                height = $(options.button).height(),
                time = new Date().getTime();
            $(options.button).after('<input type="file" id="file'+time+'"  name="file"  style="height: '+height+'px;left: -'+width+'px;opacity: 0;position: relative;display: inline;top: 0;width: '+width+'px;">');
            $('#file'+time).on('change',function(e){
                var result = options.before_upload();
                if(result){
                    if ($(this)[0].files && $(this)[0].files.length > 0) {
                        // 上传文件大小检查
                        // if ($(this)[0].files[0].size > 102400) {
                        //     $.messager.alert('提示', '上传的文件太大了噢', 'warning');
                        //     $.messager.progress('close');
                        //     video_count = 0;
                        //     return false;
                        // }

                        that.qiniu_upload($(this)[0].files[0], options);
                    }
                }
            })

        }else{
            console.log('元素不存在');
            return false;
        }
    }
    Qiniu.prototype.qiniu_upload = function(f, options) {
        var that = this;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', options.qiniu_upload_url, true);
        //xhr.setRequestHeader("Content-Type", "multipart/form-data; boundary=------WebKitFormBoundary7S6LNB4lnXhKZt1I");
        //xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        var formData, startDate;
        formData = new FormData();
        formData.append('token', options.token);
        formData.append('file', f);

        var name = f.name;
        var point = name.lastIndexOf('.'),
            type = name.substr(point),
            key = Math.random().toString(16).substring(2) + (+new Date()) + type;

        formData.append('key', key);

        xhr.onreadystatechange = function(response) {
            if (xhr.readyState == 4 && xhr.status == 200 && xhr.responseText != "") {
                var blkRet = JSON.parse(xhr.responseText);
                blkRet.url =  that.privateVal.url_prefix[options.bucket]+'/'+blkRet.key;
                //console && console.log(blkRet);
                 console.log(blkRet);
                options.success_callback(blkRet);
            } else if (xhr.status != 200 && xhr.responseText) {
                options.fail_callback();
            }
        };
        $("#progressbar").show();
        xhr.send(formData);
    }

    $.fn.qiniu_upload_single = function() {
        var method = arguments[0];
        // if(Qiniu[method]) {
        //     method = Qiniu[method];
        // } else if( typeof(method) == 'object' || !method ) {
        //     method = Qiniu.init;
        // } else {
        //     return this;
        // }
        var args = arguments[1];
        // debugger;
        return new Qiniu(args);
    }

})(jQuery);