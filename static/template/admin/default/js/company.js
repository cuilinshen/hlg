/**
 * @version $Id$
 * @create 2012-9-26 10:29:57 By xjiujiu
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */

/**
 * 详细信息页的初始化信息 
 * 
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @package js 
 * @since 1.0.0
 */
HHJsLib.register({
	init: function() {
		this.bindParentChange();
	},
	bindParentChange: function() {
		$('#province_id').change(function() {
			var provinceid = $(this).val();
			$('#city_id').empty();
			$.getJSON(
				queryUrl + 'admin/company/agetcityidlist',
				{
					parent_id: provinceid
				},
				function(data) {
					console.log(data);
					var tpl     = '';
	                for(var index in data) {
	                    tpl += '<option value="' + data[index].id + '">' + data[index].name + '</option>';
	                }
	                console.log(tpl);
	                $('#city_id').append(tpl);

				}
			);
		});
	}
});