
/**
 * @version $Id$
 * @create 2012-9-26 10:34:25 By xjiujiu
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
(function($) {
    /**
     * 自动生成模块页面对应的JS工具类 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @package js 
     * @since 1.0.0
     */
    var HModelManagerInfo    = {
        init: function () {
            this.bindSpanChoise();
            this.bindLoadTableInfo();
            this.bindReloadTables();
            HHJsLib.autoSelect('#table-list');
        },
        bindSpanChoise: function() {
            $("div.span-item span").each(function() {
                $(this).click(function(e) {
                    if($(e.target).attr('type') == 'checkbox') {
                        return true;;
                    }
                    var $field   = jQuery(this).find("input");
                    if($field.attr('checked') == 'checked') {
                        $field.attr('checked', false);
                    } else {
                        $field.attr('checked', true);
                    }
                });
            });
        },
        bindLoadTableInfo: function() {
            $("#table-list").bind("change", function() {
                $("#identifier").val(HModelManagerInfo.formatModelIdentifier($(this).val()));
                $.getJSON(
                    siteUrl + '/index.php/wizard/modelmanager/atableinfo',
                    {table: $(this).val()},
                    function(data) {
                        if(false === data.rs) {
                            alert(data.message);
                            return;
                        }
                        HModelManagerInfo.autoCompleteFieldInfo(data.comment);
                    }
                );
            });
        },
        autoCompleteFieldInfo: function(comment) {
            if(typeof comment == 'undefined' || '' == comment) {
                return;
            }
            var modelInfo   = comment.replace("｜", "|").split("|");
            if(2 > modelInfo.length) {
                $("#description").val(modelInfo[0]);
                $("#seo-desc").val(modelInfo[0]);
                return;
            }
            $("#name").val(modelInfo[0]);
            $("#description").val(modelInfo[1]);
            $("#seo-keywords").val(modelInfo[0]);
            $("#seo-desc").val(modelInfo[1]);
        },
        formatModelIdentifier: function(tableName) {
            var identifier  = '';
            var items       = tableName.split('_');
            for(var ele in items) {
                if(0 == ele) {
                    continue;
                }
                identifier  += items[ele];
            }

            return identifier;
        },
        bindReloadTables: function() {
            $("#reload-tables").click(function() {
                $.getJSON(
                    siteUrl + '/index.php/wizard/modelmanager/atables',
                    function(data) {
                        if(data.rs != true) {
                            alert(data.info);
                            return;
                        }
                        var tablesHtml  = '<option value="">--请选择数据表--</option>';
                        for(var ele in data.tables) {
                            for(var key in data.tables[ele]) {
                                tablesHtml += '<option value="' + data.tables[ele][key] + '">' + data.tables[ele][key] + '</option>';
                            }
                        }
                        $("#table-list").html(tablesHtml);
                    } 
                );
            });
        }
    };
    //注册初始化方法
    HHJsLib.register(HModelManagerInfo, HModelManagerInfo.init, 'init');
})(jQuery);
