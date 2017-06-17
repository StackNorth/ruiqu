/**
 * Vue-jQuery 表格插件
 * 暂时只支持单页面内个jQuery对象
 *
 * author    
 * 2015-12-14
 */
;(function($) {
    $.fn.extend({
        /**
         * 初始化表格
         */
        'vtable': function(args) {
            // 默认设置
            var defaults = {
                url: '',                            // 请求的地址
                // tableClass: [],                     // 表格的class列表
                sort: '',                           // 排序字段
                order: 'desc',                      // 排序方式，默认为DESC排序
                pagination: false,                  // 是否分页，默认否
                page: 1,                            // 页码
                rows: 20,                           // 每页条目数
                count: 0,                           // 数据总数
                pageCount: 0,                       // 总页数
                columns: [],                        // 展示字段设置
                query: [],                          // 查询条件设置
                beforeLoad: function() {            // 数据加载钱的回调

                },
                onSelect: function(index, row) {  // 选择Row时的回调函数
                    console.log(row);
                    console.log(index);
                },
                afterLoad: function(data) {         // 数据加载完成后的回调函数
                    console.log(data);
                }
            };

            if (typeof(vtable_options) == 'undefined') {
                vtable_options = $.extend(defaults, args);
            } else {
                vtable_options = $.extend(vtable_options, args);
            }

            // 私有方法
            vtable_privateFunction = {
                // 发送请求
                request: function() {
                    // query及page条件
                    dataOpt = {};
                    $.extend(dataOpt, vtable_options.query);
                    if (vtable_options.pagination) {
                        var pageOpt = {
                            page  : vtable_options.page,
                            rows  : vtable_options.rows,
                            sort  : vtable_options.sort,
                            order : vtable_options.order
                        }
                        $.extend(dataOpt, pageOpt);
                    }

                    $.post(
                        vtable_options.url,
                        dataOpt,
                        function(res) {
                            var data = $.parseJSON(res);

                            v_table.data = data.list;
                            vtable_options.count = data.count;
                            vtable_options.pageCount = parseInt(data.count / vtable_options.rows) + 1;

                            if (vtable_options.page == 1) {
                                v_table.prev = 'am-disabled';
                            } else {
                                v_table.prev = '';
                            }

                            if (vtable_options.page == vtable_options.pageCount) {
                                v_table.next = 'am-disabled';
                            } else {
                                v_table.next = '';
                            }

                            v_table.page = vtable_options.page;
                            v_table.pageCount = vtable_options.pageCount;

                            vtable_options.afterLoad(data);
                        }
                    );
                },
                // 选择事件
                selected: function(index) {
                    var data = v_table.data;
                    var row = data[index];
                    vtable_options.onSelect(index, row);
                },
                // 上一页
                prev: function(key) {
                    console.log('prev');
                    if (vtable_options.page == 1) {
                        alert('已经是第一页了');
                        return true;
                    } else {
                        vtable_options.page--;
                        this.request();
                    }
                },
                // 下一页
                next: function(key) {
                    console.log('next');
                    if (vtable_options['page'] == vtable_options['pageCount']) {
                        alert('已经是最后一页了');
                    } else {
                        vtable_options['page']++;
                        this.request();
                    }
                }
            }

            vtable_options.beforeLoad();

            // 生成表格
            if (typeof(v_table) == 'undefined') {
                // // 添加表格模板
                // var tableClass = vtable_options.tableClass;
                // var tableClassList = '';
                // for (key in tableClass) {
                //     tableClassList += tableClass[key] + ' ';
                // }

                // 表格模板
                var tableTpl  = '<table class="am-table am-table-radius am-table-striped am-table-hover" id="vtable_grid">';
                    tableTpl += '<thead><tr><th v-for="column in columns">{{column.name}}</th></tr></thead>';
                    tableTpl += '<tbody><tr v-for="entry in data" onclick="vtable_privateFunction.selected({{$index}});" index="{{$index}}"><td v-for="column in columns">{{entry[column[\'key\']]}}</td></tr></tbody>';
                    tableTpl += '</tbody>';
                this.append(tableTpl);
                // 分页模板
                if (vtable_options.pagination == true) {
                    pageTpl  = '<ul class="am-pagination am-pagination-centered">';
                    pageTpl += '<li class="am-pagination-prev {{prev}}"><a href="javascript:;" onclick="vtable_privateFunction.prev();" id="vtable_prev"><span class="am-icon-angle-double-left"> 上一页</a></li>';
                    pageTpl += '<li class="am-disabled" style="width:100px;"><span>{{page}}/{{pageCount}}</span></li>'
                    pageTpl += '<li class="am-pagination-next {{next}}"><a href="javascript:;" onclick="vtable_privateFunction.next();" id="vtable_next">下一页 <span class="am-icon-angle-double-right"></span></a></li>';
                    pageTpl += '</ul>';
                    this.append(pageTpl);
                }

                // 初始化v_table[this.vid]
                v_table = new Vue({
                    el: this.selector,
                    data: {
                        columns: vtable_options.columns,
                        data: [],
                        prev: '',
                        next: '',
                        page: 0,
                        pageCount: 0
                    }
                });

                // 初次获取数据
                vtable_privateFunction.request();
            } else {
                // 筛选
                console.log(vtable_options.query);
                vtable_privateFunction.request();
            }

            return this;
        }
    });
})(jQuery);