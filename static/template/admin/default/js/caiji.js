HHJsLib.register({
	init: function() {
		this.bindCaiJiBtn();
	},
	bindCaiJiBtn: function() {
		var that = this;
		$('#caiji-btn').click(function() {
            if($('#type').val() == 2) {
                HHJsLib.warn('未开放抓取');
                return;
            }
			 $.getJSON(
                siteUrl + "index.php/admin/product/docaiji",
                {
                	type: $('#type').val(),
                	city_id: $('#city_id').val(), 
                	cate_id: $('#cate_id').val(), 
                },
                function(data) {
                    if(false === data.rs) { 
                        return HHJsLib.warn(data.message);
                    }
                    var tpl = '成功执行' + data.data + '条数据';
                    $('#result-ul').append(tpl);
                }
            );


		});
	}
});