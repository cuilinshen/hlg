
/**
 * @version $Id$
 * @create Sat 03 Aug 2013 17:50:39 CST By xjiujiu
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
HHJsLib.register({
    init: function() {
        this.bindStatusChange();
    },
    bindStatusChange: function() {
        $("#status").bind('change', function() {
            if($(this).val()) {
                $("#search-form").submit();
            }
        });
    }
});
