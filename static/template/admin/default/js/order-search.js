/**
 * @version $Id$
 * @create 2012-9-26 10:29:57 By xjiujiu
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */

/**
 * Order Search订单搜索info JS 
 * 
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @package js 
 * @since 1.0.0
 */
HHJsLib.register({
    init: function() {
        datetimeList.push('#start_time');
        datetimeList.push('#end_time');
        this.bindDateTimeList();
    },
    bindDateTimeList: function() {
        HHJsLib.importCss([cdnUrl + "/bootstrap/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css"]); 
        HHJsLib.importJs(
            [cdnUrl + "/bootstrap/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"],
            function() {
                for(var ele in datetimeList) {
                    $(datetimeList[ele]).datetimepicker({
                        format: 'yyyy-mm-dd hh:ii:ss',
                        autoclose: true,
                        todayBtn: true,
                        minuteStep: 2,
                        todayHighlight: 1,
                        language: 'zh-CN'
                    }); 
                }
            }
        );
    },
    bindDateList: function() {
        HHJsLib.importCss([siteUri + "/css/datepicker.css"]); 
        HHJsLib.importJs(
            [siteUri + "/js/bootstrap-datepicker.min.js"],
            function() {
                for(var ele in dateList) {
                    $(dateList[ele]).datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        todayBtn: true,
                        minuteStep: 2,
                        todayHighlight: 1,
                        language: 'zh-CN'
                    }); 
                }
            }
        );
    }
});
