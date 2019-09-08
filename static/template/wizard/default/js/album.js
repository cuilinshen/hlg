
/**
 * @version $Id$
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @description HongJuZi Framework
 * @copyright Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
HHJsLib.register({
    listTpl: '<ul id="pic-tpl">{list}</ul>',
    itemTpl: '<li id="pic-{id}" class="pic btn-app">'
    + ' <a href="###" data-id="{id}" class="check-item" id="{id}">'
    + ' <img alt="{name}" src="{small}" />'
    + ' <div class="text">'
    + ' <div class="inner description">{description}</div>'
    + ' </div>'
    + ' </a>'
    + ' <div class="tools tools-bottom">'
    + ' <a target="_blank" href="{src}" class="pic-link" title="查看原图"><i class="icon-link"></i></a>'
    + ' <a href="###" class="pic-delete" data-id="{id}" title="删除图片"><i class="icon-remove red"></i></a>'
    + ' </div>'
    + ' <span class="hide label label-info icon-check">&nbsp;</span>'
    + ' <input type="checkbox" id="item-{id}" value="{id}" class="hide check-item"/>'
    + ' </li>',
    init: function() {
        this.initUploadify();
        this.bindToolLinks();
        this.bindDeleteMoreBtn();
    },
    initUploadify: function() {
        var _root   = this;
        HHJsLib.importCss([cdnUrl + "/uploadify/css/uploadify.css"]);
        HHJsLib.importJs([cdnUrl + "/uploadify/jquery.uploadify.min.js"],
            function() {
                $(function() {
                    formData['is_ajax']     = true;
                    $('#file-upload').uploadify({
                        'fileObjName'   : 'path',
                        'buttonText'    : '添加图片',
                        'formData'      : formData,
                        'height'        : 120,
                        'width'         : 100,
                        'swf'           : cdnUrl + '/uploadify/uploadify.swf',
                        'uploader'      : siteUrl + 'index.php/public/resource/aupload',
                        'onUploadSuccess' : function(file, data, response) {
                            data        = $.parseJSON(data);
                            if(false == data.rs) {
                                return HHJsLib.warn(data.message);
                            }
                            var picTpl  = _root.itemTpl.replace(/{id}/g, data.id);
                            picTpl      = picTpl.replace(/{name}/g, data.name);
                            picTpl      = picTpl.replace(/{description}/g, data.name);
                            picTpl      = picTpl.replace(/{src}/g, siteUrl + data.src);
                            picTpl      = picTpl.replace(/{small}/g, siteUrl + data.small);
                            $pic        = $(picTpl).hide().fadeIn('fast');
                            $("#album-list-box").append($pic);
                            _root.bindDeletePic($pic.find("a.pic-delete"));
                            _root.bindCheckLink($pic.find("a.check-item"));
                            HHJsLib.succeed('恭喜，上传成功！');
                        }
                    });
                });
            }
        );
    },
    bindToolLinks: function() {
        this.bindDeletePic("a.pic-delete");
        this.bindCheckLink('a.check-item');
    },
    bindDeletePic: function(target) {
        $(target).click(function() {
            if(!confirm('您真的要从相册里删除此图片吗？')) {
                return;
            }
            var $this   = $(this);
            $.getJSON(
                siteUrl + "index.php/admin/linkeddata/adelete",
                {id: $this.attr('data-id'), rel_model: modelEnName, item_model: 'resource'},
                function(data) {
                    if(false === data.rs) { 
                        return HHJsLib.warn(data.message);
                    }
                    var $parent     = $this.parent().parent();
                    $parent.fadeOut('normal', function() {
                        $parent.remove();
                    });
                    HHJsLib.succeed(':）删除成功！');
                }
            );
        });
    },
    bindCheckLink: function(target) {
        $(target).click(function() {
            var $item   = $('#item-' + $(this).attr('data-id'));
            if(!$item.attr('checked')) {
                $item.attr('checked', true);
                $(this).parent().find('span.icon-check:first').show();
                return;
            }
            $item.attr('checked', null);
            $(this).parent().find('span.icon-check:first').hide();
        });
    },
    bindDeleteMoreBtn: function() {
        $("#delete-more-btn").click(function() {
            var $checkedItems   = $("#album-list-box input:checked");
            if(1 > $checkedItems.length) {
                HHJsLib.info('您还没有选择需要删除的图片！');
                return false;
            }
            if(!confirm('您真的要删除选中的图片吗？')) {
                return false;
            }
            var ids     = [];
            $checkedItems.each(function() {
                ids.push($(this).val());
            });
            $.getJSON(
                siteUrl + "index.php/admin/linkeddata/adelete",
                {id: ids.toString(), rel_model: modelEnName, item_model: 'resource'},
                function(data) {
                    if(false === data.rs) { 
                        HHJsLib.warn(data.message);
                        return;
                    }
                    for(var ele in ids) {
                        $('#pic-' + ids[ele]).fadeOut('fast', function() {
                            $(this).remove();
                        });
                    }
                    HHJsLib.succeed(':）删除成功！');
                }
            );
        });
    }
});
