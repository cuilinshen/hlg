
/**
 * @version $Id$
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @description HongJuZi Framework
 * @copyright Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */

 /**
  * 注册文件选择列表
  * 
  * @author xjiujiu <xjiujiu@foxmail.com>
  * @since 1.0.0
  */
 HHJsLib.register({
     addNodeTpl: ' <li>'
     + '<a href="{admin-url}" data-rel="colorbox" class="cboxElement add-file-btn" title="去编辑：{name}" target="_blank">'
     + ' <img src="{src}">'
     + ' <div class="text">'
     + ' <div class="inner">{name}<br/>大小：{size}<br/>类型：{extension}</div>'
     + ' </div> </a> <div class="tools tools-bottom">'
     + ' <a href="{url}" title="前台查看资源" target="_blank" ><i class="icon-link"></i></a>'
     + ' <a href="{admin-url}" target="_blank" title="编辑资源"><i class="icon-pencil"></i></a>'
     + ' <a href="###" class="add-btn" data-id="{id}" title="添加到此专辑"><i class="icon-check green"></i></a>'
     + ' </div> <span class="icon-check green checked"></span></li>',
     selectedNodeTpl: ' <li>'
     + '<a href="{admin-url}" data-rel="colorbox" class="cboxElement add-file-btn" title="去编辑：{name}" target="_blank">'
     + ' <img src="{src}">'
     + ' <div class="text">'
     + ' <div class="inner">{name}<br/>大小：{size}<br/>类型：{extension}</div>'
     + ' </div> </a> <div class="tools tools-bottom">'
     + ' <a href="{url}" title="前台查看资源" target="_blank" ><i class="icon-link"></i></a>'
     + ' <a href="{admin-url}" target="_blank" title="编辑资源"><i class="icon-pencil"></i></a>'
     + ' <a href="###" class="remove-btn" data-id="{linked-id}" title="从此专辑移除"><i class="icon-remove red"></i></a>'
     + ' </div> </li>',
     init: function() {
         this.bindSearchBtn();
         this.bindRemoveFileBtn();
     },
     bindSearchBtn: function() {
         var _root = this;
         $("#select-file-search-btn").click(function() {
             try {
                 HHJsLib.isEmptyByDom('#select-keyword', '关键词');
                 $.getJSON(
                     queryUrl + "admin/files/asearch",
                     {keywords: $('#select-keyword').val()},
                     function(response) {
                         if(false === response.rs) {
                             return HHJsLib.warn(response.message);
                         }
                         $("#select-files-list").html("");
                         _root.appendSelectFileList(response.data);
                     }
                 );
             } catch(e) {
                 return HHJsLib.warn(e);
             }
         });
     },
     appendSelectFileList: function(data) {
         if(!data) {
             $("#hint-box").html('没有找到相关资源！');
             return;
         }
         $("#hint-box").html("一共找到“" + data.totalRows + "”个资源。");
         var liHtml     = '';
         for(var ele in data.list) {
            liHtml      += this.formatFileNodeHtml(data.list[ele], this.addNodeTpl);
         }
         $("#select-files-list").append(liHtml);
         this.bindAddFileBtn($("#select-files-list"));
     },
     formatFileNodeHtml: function(file, tpl) {
         return tpl.replace(/{id}/g, file.id)
         .replace(/{name}/g, file.name)
         .replace(/{src}/g, siteUrl + file.image_path)
         .replace(/{size}/g, file.size)
         .replace(/{extension}/g, file.extension)
         .replace(/{url}/g, queryUrl + "files?id=" + file.id)
         .replace(/{admin-url}/g, queryUrl + "admin/files/editview?id=" + file.id);
     },
     bindAddFileBtn: function($target) {
         var _root      = this;
         $target.find("a.add-btn").click(function() {
             try {
                 var id         = $(this).attr('data-id');
                 var relId      = $("#id").val();
                 var $parent    = $(this).parent().parent();
                 if(!id) {
                     throw '编号不能为空';
                 }
                 $.getJSON(
                     queryUrl + "admin/files/aaddalbum",
                     {rel_model: 'album', item_id: id, rel_id: relId},
                     function(response) {
                         if(false === response.rs) {
                             return HHJsLib.warn(response.message);
                         }
                         $parent.addClass('selected');
                         $("#selected-file-list").append(
                             _root.formatFileNodeHtml(
                                 response.data.file, 
                                 _root.selectedNodeTpl
                             ).replace(/{linked-id}/g, response.data.lid)
                         );
                         _root.updateTotalFiles(1);
                         _root.bindRemoveFileBtn();
                     }
                 );
             } catch(e) {
                 return HHJsLib.warn(e);
             }
         });
     },
     updateTotalFiles: function(number) {
         var totalFiles     = parseInt($("#total_files").val()) + number;
         $("#total_files").val(totalFiles);
     },
     bindRemoveFileBtn: function() {
         $("a.remove-btn").click(function() {
             try {
                 var id         = $(this).attr('data-id');
                 var $parent    = $(this).parent().parent();
                 if(!id) {
                     throw '编号不能为空';
                 }
                 $.getJSON(
                     queryUrl + "admin/linkeddata/adelete",
                     {id: id},
                     function(response) {
                         if(false === response.rs) {
                             return HHJsLib.warn(response.message);
                         }
                         $parent.fadeOut('fast', function() {
                             _root.updateTotalFiles(-1);
                             this.remove();
                         });
                     }
                 );
             } catch(e) {
                 return HHJsLib.warn(e);
             }
         });
     }
 });
