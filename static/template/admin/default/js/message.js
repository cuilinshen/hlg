HHJsLib.register({
	init: function() {
		this.bindReplyAddBtn();
		this.bindSkuDelBtn();
	},
	bindReplyAddBtn: function() {
		var that = this;
		$('#reply-add-btn').click(function() {
			var content = $('#reply-content').val();
			var id = $('#id').val();
			if(typeof id == 'undefined' || id == '') {
				HHJsLib.info('请先添加好评论');
				return;
			}
			$.getJSON(
				queryUrl + 'admin/reply/addpost',
				{
					content: content,
					id: id,
					type: 2
				},
				function(res) {
					if(res.rs) {
						var tpl = that._getTpl(res.data);
						$('#sku-tbody').prepend(tpl);
						that.bindSkuDelBtn();
					}else{
						HHJsLib.warn(res.message);
					}
				}
			);

		});
	},
	_getTpl: function(data) {
		    var tpl = '<tr class="odd" id="sku-' + data.id + '">'
                      + '<td class="field field-id"> ' + data.id + '</td>'
                      + '<td class="field field-name">' + data.content +'</td>'
                      + '<td class="field field-name">' + data.create_time + '</td>'
                      + '<td> <div class="btn-group"> '
                      + '<a href="###" title="删除信息" data-id="' + data.id + '" class="btn btn-mini btn-danger delete sku-del"><i class="icon-trash"></i></a> '                                          
					  + '</div></td> </tr> ';

			return tpl;
                                                               
	},
	bindSkuDelBtn: function() {
		var that = this;
		$('a.sku-del').unbind('click').bind('click', function() {
			var id = $(this).attr('data-id');
			$.getJSON(
				queryUrl + 'admin/reply/del',
				{
					id: id
				},
				function(res) {
					if(res.rs) {
						$('#sku-' + id).remove();
					}else{
						HHJsLib.warn(res.message);
					}
				}
			);

		});
	}
})