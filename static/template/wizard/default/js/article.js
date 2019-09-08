
/**
 * @version $Id$
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @description HongJuZi Framework
 * @copyright Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
HHJsLib.register({
    len: 0,
    isFenci: false,
    init: function() {
        this.bindXIconListClick();
        this.bindAutoFenci();
        this.bindNamePinYin();
        this.bindOpenAddCatBoxBtn();
        this.bindCancelCatBtn();
        this.bindAddCatBtn();
        this.bindTreeCheckBoxList();
    },
    bindTreeCheckBoxList: function() { 
        if(1 > treeCheckboxList.length) {
            return;
        }
        var self    = this;
        var setting = {
            check: {enable: true },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                onCheck: function() {
                    self.setParentIds();
                }
            }
        };
        HHJsLib.importCss([cdnUrl + "/jquery/plugins/ztree/css/metroStyle/metroStyle.css"]);
        HHJsLib.importJs([
            cdnUrl + "/jquery/plugins/ztree/js/jquery.ztree.core-3.5.min.js",
            cdnUrl + "/jquery/plugins/ztree/js/jquery.ztree.excheck-3.5.min.js"
        ], function() {
            for(var ele in treeCheckboxList) {
                $.fn.zTree.init($(treeCheckboxList[ele].dom), setting, treeCheckboxList[ele].data);
            }
        });
    },
    setParentIds: function() {
        var zTree   = $.fn.zTree.getZTreeObj("parent_id-tree");
        var nodes   = zTree.getCheckedNodes(true);
        var ids     = [];
        for(var ele in nodes) {
            ids.push(nodes[ele].id);
        }
        $("#parent_id").val(ids.join(','));
    },
    bindAddCatBtn: function() {
        var self    = this;
        $("#add-cat-btn").click(function() {
            try {
                HHJsLib.isEmptyByDom('#new-cat-name', '新分类名称');
                HHJsLib.isEmptyByDom('#new-parent_id', '所属分类');
                $.getJSON(
                    queryUrl + 'admin/category/anew',
                    {name: encodeURIComponent($('#new-cat-name').val()), pid: $('#new-parent_id').val()},
                    function(response) {
                        if(false === response.rs) {
                            return HHJsLib.warn(response.message);
                        }
                        $('#add-category-box').fadeOut('fast');
                        var zTree   = $.fn.zTree.getZTreeObj("parent_id-tree"); 
                        var pNode   = zTree.getNodeByParam('id', $('#new-parent_id').val());
                        zTree.addNodes(pNode, response.node);
                        self.setParentIds();
                        $('#new-cat-name').val('');
                    }
                );
            } catch(e) {
                return HHJsLib.warn(e);
            }
        });
    },
    bindCancelCatBtn: function() {
        $('#cancel-add-cat-btn').click(function() {
            $('#add-category-box').hide();
        });
    },
    bindOpenAddCatBoxBtn: function() {
        $('#open-new-cat-box-btn').click(function() {
            $("#add-category-box").show();
        });
    },
    bindNamePinYin: function() {
        $('#name').change(function() {
            if(!$(this).val()) {
                return;
            }
            $.getJSON(
                queryUrl + 'public/tstring/apinyin',
                {data: encodeURIComponent($(this).val())},
                function(response) {
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    $('#identifier').val(response.data);
                }
            );
        });
    },
    bindXIconListClick: function() {
        $('#xele-list li a').click(function() {
            $('#extend_class').val($(this).html());
        });
    },
    bindAutoFenci: function() {
        var self    = this;
        var process = false;
        setInterval(function() {
            if(true === process) {
                return;
            }
            if('undefined' === typeof(HHJsLib.editor['content'])) {
                return;
            }
            var content     = HHJsLib.editor['content'].getContentTxt();
            if(!content) {
                return;
            }
            var contentLen  = content.length;
            if(self.len == contentLen && self.isFenci == true) {
                return;
            }
            $('#description').val(content.substring(0, 200));
            self.len    = contentLen;
            process     = true;
            $.post(
                queryUrl + 'public/tstring/afenci',
                {data: content},
                function (response) {
                    process     = false;
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    var tagTopHtml  = '建议：';
                    for(var ele in response.data) {
                        tagTopHtml  += '<a href="###">' + response.data[ele].word + '</a>';
                    }
                    self.isFenci    = true;
                    $('#tags-top-list').html(tagTopHtml);
                    self.bindAddTag();
                },
                'json'
            );
        }, 3000);
    },
    bindAddTag: function() {
        var _root       = this;
        $('#tags-top-list a').click(function() {
            var tag         = $(this).html();
            var tagsName    = $('#tags_name').val();
            if(tagsName && 0 <= tagsName.indexOf(',' + tag + ',')) {
                $("#tags").removeTag(tag);
                return;
            }
            $("#tags").addTag(tag);
        });
    }
});
