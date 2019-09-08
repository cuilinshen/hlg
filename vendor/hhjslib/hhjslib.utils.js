/**
 * @version $Id$
 * @create 2012-10-21 16:57:09 By xjiujiu
 * @description HongJuZi Framework
 * @Copyright Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
 (function($) {
	 
     /**
      * HHJsLib扩展工具类 
      * 
      * 收集一些用户的小功能，小工具函数等 
      * 
      * @author xjiujiu <xjiujiu@foxmail.com>
      * @package hhjslib
      * @since 1.0.0
      */
     var Utils    = {
		
    	/**
	     * 高亮显示当前链接元素[现在还得基于Jquery]
	     * 
	     * <pre>
	     * //HTML部分:
	     * <div class="navmenu">
	         * <ul>
	             * <li><a href="link1">link1</a></li>
	             * <li><a href="link2">link2</a></li>
	             * <li><a href="link3">link3</a></li>
	         * </ul>
	     * </div>
	     * //JS部分：
	     *  HHJsLib.highLightElement('.navmenu ul li', 'active', 0);
	     * </pre>
	     *
	     * @param string target 需要高亮的元素
	     * @param string currentClass 当前选中的样式名
	     * @param string level 相对于当前查到元素的层级关系，往下是-N, 往上是+N
	     * @param string callback 对找到的目标附加的函数调用
	     *
	     * @return {HHJsLib} 对象
	     */
	     highLightElement: function(target, currentClass, level, callback) {
            return this.highLightElementByUrl(window.location.href, target, currentClass, level, callback);
	     },
		
    	/**
	     * 高亮显示当前链接元素[现在还得基于Jquery]指定了URL
	     * 
	     * <pre>
	     * //HTML部分:
	     * <div class="navmenu">
	         * <ul>
	             * <li><a href="link1">link1</a></li>
	             * <li><a href="link2">link2</a></li>
	             * <li><a href="link3">link3</a></li>
	         * </ul>
	     * </div>
	     * //JS部分：
	     *  HHJsLib.highLightElementByUrl(url, '.navmenu ul li', 'active', 0);
	     * </pre>
	     *
	     * @param string target 需要高亮的元素
	     * @param string currentClass 当前选中的样式名
	     * @param string level 相对于当前查到元素的层级关系，往下是-N, 往上是+N
	     * @param string callback 对找到的目标附加的函数调用
	     *
	     * @return {HHJsLib} 对象
	     */
         highLightElementByUrl: function(url, target, currentClass, level, callback) {
	        var shortUrl    = url.substring(url.lastIndexOf('/') + 1);/*拿到当前访问的页面及查询内容*/
            url             = decodeURIComponent(url);
	        $(target).each(function() {
	            $(this).find('a').each(function() {
                    var curHref     = decodeURIComponent($(this).attr('href'));
                    if(typeof curHref !== 'undefined' && curHref !== '') {
                        if(curHref === url || curHref === shortUrl) {
                            var targetDom = curTargetDom = $(this);
                            if(level < 0) {
                                for(var i = 0; i > level; i --) {
                                    targetDom    = jQuery(targetDom).children('a'); 
                                }
                            } else if(level > 0) {
                                for(var i = 0; i < level; i ++) {
                                    targetDom    = jQuery(targetDom).parent(); 
                                }
                            }
                            if(typeof callback != 'undefined') {
                                callback(targetDom, curTargetDom[0]);
                            }
                            $(targetDom[0]).addClass(currentClass);
                            
                            return false;
                        }
                    }
                });
	        });
         },
    		 
         /**
          * 回到页面的顶部 
          * 
          * <pre>
          * HHJsLib.goTop('btn-top-id');
          * </pre>
          * 
          * @author xjiujiu <xjiujiu@foxmail.com>
          * @param {String} btnId 按钮的ID
          * @return void
          * @throws none
          */
        goTop: function(target) {
            $(target).hide();
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $(target).fadeIn();
                } else {
                    $(target).fadeOut();
                }
            });
            $(target).click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });

            return this;
        },
        
        /**
          * 重定向到其它页面 
          * 
          * <pre>
          * HHJsLib.redirect('http://www.xjiujiu.com', target);
          * </pre>
          * 
          * @author xjiujiu <xjiujiu@foxmail.com>
          * @param {String} url 目标地址
          * @param {String} target 重定向的目标位置: self, blank
          */
        redirect: function(url, target) {
            if(typeof target == 'undefined') {
                target  = 'self';
            }
            switch(target) {
                case 'blank':
                    window.open(url);
                    break;
                case 'self':
                default:
                    window.location.href    = url;
                    break;
            }
        },
       
        /**
          * 标记当前内容的状态
          * 
          * <pre>
          * HHJsLib.maskStatus('td.pass', {"是": "pass-ok", "否": "pass-no"});
          * </pre>
          * 
          * @author xjiujiu <xjiujiu@foxmail.com>
          * @param  Map<String, String> 值对应的状态样式表,如：{1: "ok-status"}
          * @return {HHJsLib} 当前的对象 
          */
        maskStatus: function(map) {
        	this.$target.each(function() {
        		var value 	= $.trim($(this).html());
        		if(typeof map[value] != 'undefined') {
        			$(this).addClass(map[value]);
        		}
        	});

            return this;
        },
        
        /**
          * 打印预览，默认为A4的纸大小
          * 
          * <pre>
          * HHJsLib("#print-btn-id").printPriview("我是需要打印的内容");
          * </pre>
          * 
          * @author xjiujiu <xjiujiu@foxmail.com>
          * @param {String} callback 回调函数 得到需要打印的内容
          * @param {String} title 打印页面的标题
          * @return {HHJsLib} 当前的对象
          */
        printPriview: function(target, callback, title) {
        	$(target).click(function() {
        		var content 	= callback();
        		var htmlTpl 	= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">"
								+ "<html xmlns=\"http://www.w3.org/1999/xhtml\">"
								+ "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=8\">"
		        				+ "<head>"
		        				+ "{link}"
		        				+ "<style text=\"text/css\">"
		        				+ "		.print-btn-line { text-align: right; padding-right: 10px;}"
		        				+ "		.print-page { width: 1000px; margin: 0px auto; padding-bottom: 10px; }"
		        				+ "		.page-title { text-align: center; }"
		        				+ " 	{style}"
		        				+ "</style>"
		        				+ "</head>"
		        				+ "<p class=\"print-btn-line\">"
        						+ "<a href=\"javascript:void(0);\" id=\"start-print-id\">点击确认打印</a></p>"
		        				+ "{title}"
		        				+ "<div class=\"print-page\" id=\"content-id\">{content}</div>"
		        				+ "<script type=\"text/javascript\">"
		        				+ "document.getElementById(\"start-print-id\").onclick = function() {"
		        				+ "this.innerHTML = '';"
		        				+ "window.print();"
		        				+ "this.innerHTML = '点击确认打印'"
		        				+ "}"
		        				+ "</script>"
		        				+ "</html>";
    			newWindow 		= window.open("", "打印预览", "fullscreen");
    			newWindow.title = "打印预览";
    			newWindow.document.open("text/html", "replace");
    			newWindow.moveBy(0, 0);
    			newWindow.resizeTo(window.screen.availWidth, window.screen.availHeight);
        		var links 		= "";
    			$("link").each(function() {
    				links 		+= "<link rel=\"stylesheet\" href=\"" + $(this).attr("href") + "\" type=\"text/css\" />";
    			});
        		var styles 		= "";
    			$("style").each(function() {
    				styles 		+= $(this).html();
    			});
    			
    			htmlTpl 		= htmlTpl.replace(/{link}/, links);
    			htmlTpl 		= htmlTpl.replace(/{style}/, styles);
    			if(typeof title != 'undefined' && "" != title) {
	    			htmlTpl 	= htmlTpl.replace(/{title}/, "<h3 class=\"page-title\">" + title + "</h3>");
    			} else {
	    			htmlTpl 	= htmlTpl.replace(/{title}/, "");
    			}
    			htmlTpl 		= htmlTpl.replace(/{content}/, content);
    			newWindow.document.write(htmlTpl);
    			newWindow.document.close();
    		});
        },
        
        /**
          * 删除Table指定的列
          * 
          * <pre>
          * HHJsLib.deleteTableColumn($("#table-content-area").html(), [0, 12]);
          * </pre>
          * 
          * @author xjiujiu <xjiujiu@foxmail.com>
          * @param  {Array} subInfo 需要删除的列信息
          * @return {String} 删除列后的表格HTML代码
          */
        deleteTableColumn: function(subInfo) {
        	if(typeof subInfo == 'undefined') {
        		return this.$target[0].outerHTML;
        	}
			var row 		= -1;
        	this.$target.find("tr").each(function() {
        		row ++;
        		var subColumns 	= [];
        		if(typeof subInfo[row] != 'undefined') {
        			subColumns 	= subInfo[row];
        		} else if(typeof subInfo['a'] != 'undefined') {
        			subColumns 	= subInfo['a'];
        		}
	    		for(var i = 0; i < subColumns.length; i ++) {
	    			/*减去刚才去掉的那些列们，且它们刚好是i的数量*/
	        		$(this).find("td:eq(" + (subColumns[i] - i) + ")").remove();
	        	}
        	});
    		
	    	return this.$target[0].outerHTML;
        },

        /**
          * 二级下拉效果
          * 
          * <pre>
          * //JS CODE:
          * HHJsLib.dropMenu("ul.main-menu li", "dl.sub-link", "selected");
          * //HTML CODE:
          * <ul class="main-menu">
          * <li><a href="#">主菜单</a></li>
          * <li>
          *     <a href="#">主菜单</a>
          *     <dl class="sub-link">
          *      <dd><a href="#">子菜单</a></dd>
          *      <dd><a href="#">子菜单</a></dd>
          *      <dd><a href="#">子菜单</a></dd>
          *     </dl>
          * </li>
          * </ul>
          * </pre>
          * 
          * @author xjiujiu <xjiujiu@foxmail.com>
          * @param  {Array} subElementClass 子菜单的样式名 
          * @param  {Array} currentClass 选中后的样式
          * @return 当前HHJsLib对象 
          */
        dropMenu: function (target, subElementClass, currentClass){
            $(target).each(function(){
                var theSpan = $(this);
                var theMenu = theSpan.find(subElementClass);
                var tarHeight = theMenu.height();
                theMenu.css({height:0,opacity:0});
                theSpan.hover(
                    function(){
                        $(this).addClass(currentClass);
                        theMenu.stop().show().animate({height:tarHeight,opacity:1},400);
                    },
                    function(){
                        $(this).removeClass(currentClass);
                        theMenu.stop().animate({height:0,opacity:0},400,function(){
                            $(this).css({display:"none"});
                        });
                    }
                );
            });

            return this;
        },

        /*fix sub-nav width*/
        fixSubNavWidth: function(target) {
            var $this = $(target);
            if(1 > $this.length) {
                return ;
            }
            var width = 0;
            $this.each(function() {
                width += parseFloat($(this).css('width'))
                + parseFloat($(this).css('padding-left')) 
                + parseFloat($(this).css('padding-right')) 
                + parseFloat($(this).css('margin-left')) 
                + parseFloat($(this).css('margin-right'));
            });
            $this.parent().css('width', width + "px");
        },

        /**
          * 二级下拉效果
          * 
          * //JS CODE:
          * <pre>
          * HHJsLib("ul.main-menu li").slideUpDown("dl.sub-link", "selected");
          * //HTML CODE:
            <ul class="menu-list">
                <li><a href="###">链接一</a>
                	<ul class="sub-menu-list">
                        <li><a href="#">下级链接一</a></li> 
                        <li><a href="#">下级链接二</a></li> 
                     </ul>
                </li>
            </ul>                
          * </pre>
          * 
          * @author xjiujiu <xjiujiu@foxmail.com>
          * @param {String} target 需要处理的对象
          * @param  object callback 回调函数
          * @return 当前HHJsLib对象
          */
        slideUpDown: function(target, callback) {
			$(target).click(function () {
				$(this).parent().siblings().find("ul").slideUp("normal");
				$(this).next().slideToggle("normal"); 
				if(typeof callback != 'undefined') {
					callback($(this));
				}
				return true;
			});

            return this;
        },

         /**
         * 字段域提示工具 
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param {String} def 提示的信息
         * @return 当前HHJsLib对象
         */
        fieldHint: function(target) {
            $(target).each(function() {
                var $this   = $(this);
                var def     = !$this.val() && $this.attr('data-def') ? $this.attr('data-def') : $this.val();
                $(this).val(def)
                .css('color', '#999')
                .click(function() {
                    if($(this).attr('data-def') == $(this).val()) {
                        $(this).val("");
                    }
                    $(this).css('color', '#000')
                }).blur(function() {
                    if('' == $(this).val()) {
                        $(this).val($(this).attr('data-def'));
                        $(this).css('color', '#999');
                    }
                });
            });

            return this;
        },

        /**
         * 设置成主页 
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param {Object} obj 当前的DOM对象
         * @param {String} val 当前的网址
         */
        setHomePage: function (obj, val){
            try{
                obj.style.behavior='url(#default#homepage)';
                obj.setHomePage(val);
            } catch(e){
                if(window.netscape) {
                    try {
                        netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
                    } catch (e)  { 
                        alert("此操作被浏览器拒绝！");  
                    }
                    var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService
                    (Components.interfaces.nsIPrefBranch);
                    prefs.setCharPref('browser.startup.homepage',val);
                }
            }
        },
        
        /**
         * 加入到收藏夹 
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param {String} URL 需要收藏的链接
         * @param {String} title 网页标题
         * @return Object 当前的HHJsLib对象
         */
        addFavorite: function (URL, title) {
            try {
                window.external.addFavorite(URL, title);
            } catch (e) {
                try {
                    window.sidebar.addPanel(title, URL, "");
                } catch (e) {
                    alert("加入收藏失败，请使用Ctrl+D进行添加");
                }
            }

            return this;
        },
        setCart: function (target) {
            if (target.createTextRange) {
                target.caretPos = document.selection.createRange().duplicate();
            }
        },

        /**
         * 设置光标所在的位置
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param {String} target 目标对象
         * @param  int pos 位置
         */
        setCaretPosition: function (target, pos){
            target.focus();
            if(target.setSelectionRange) {
                target.setSelectionRange(pos,pos);
            } else if (target.createTextRange) {
                var range = target.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        },

        /**
         * 得到光标所在的位置
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param {Object} Object target 文档对象
         * @return {Number} 当前标签所在的位置
         */
        getCursortPosition: function  (target) {
            var CaretPos = 0;   /* IE Support*/
            if(document.selection) {
                target.focus ();
                var Sel = document.selection.createRange ();
                Sel.moveStart ('character', -target.value.length);
                CaretPos = Sel.text.length;
            } else if(target.selectionStart || target.selectionStart == '0') {
                CaretPos = target.selectionStart;
            }

            return CaretPos;
        },

        /**
         * 在元素区域当前光标位置插入内容
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param {Object} Object target 文档对象
         * @param {String} str 需要加入的内容
         */
        insertAtCaret: function (target, str) {
            if('undefined' === typeof(target)) { return; }
            target.focus();
            if (document.all) {
                if (target.createTextRange && target.caretPos) {
                    var caretPos    = target.caretPos;
                    caretPos.text   = caretPos.text.charAt(caretPos.text.length - 1) == '' ? str + '': str;
                } else {
                    target.value    = str;
                }
                return;
            }
            if (target.setSelectionRange) {
                var rangeStart  = target.selectionStart;
                var rangeEnd    = target.selectionEnd;
                var tempStr1    = target.value.substring(0, rangeStart);
                var tempStr2    = target.value.substring(rangeEnd);
                target.value    = tempStr1 + str + tempStr2;
                return;
            }
            HHJsLib.warn("您当前的浏览器版本不支持setSelectionRange，请升级到最新版本！");
        },

        /**
         * 解析DOM对象里的内容为URL
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param {Object} 对象 target 需要解析的DOM对象
         */
        parseUrlByDom: function(target) {
             var _root  = this;
             $(target).each(function() {
                 $(this).html(_root.parseUrl($(this).html())); 
             });
        },

        /**
         * 解析URL地址
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param {String} content 需要解析的内容
         */
        parseUrl: function(content) {
             return content.replace(
                 /(http:\/\/|https:\/\/)((\w|=|\?|\.|\/|&|-)+)/ig, 
                 '<a target="_blank" href="$1$2" rel="external nofollow">$1$2</a>'
             ); 
        },

        /**
         * 延时执行
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param  {Function} func 需要执行的方法
         * @param  {Number} delay 延时长度
         */
        delayExec: function(func, delay) {
            setTimeout(func, delay);
        },

        /**
         * 格式化格式文件
         * 
         * 跟当前时间对比，格式化后为：刚刚、多少秒前、多少分钟前...
         * 
         * @author xjiujiu <xjiujiu@foxmail.com>
         * @param datetime 当前时间，2014-05-32 22:23:23
         * @return {String} 格式化后的值
         */
        formatSendTime: function(datetime) {
            var date       = new Date();
            var curYear    = date.getFullYear();
            var curMonth   = date.getMonth() + 1;
            var curDay     = date.getDate();
            var curHour    = date.getHours();
            var curMin     = date.getMinutes();
            var dateTimeInfo   = datetime.split(' ');
            var dateInfo   = dateTimeInfo[0].split('-');
            var timeInfo   = dateTimeInfo[1].split(':');
            if(0 < curYear - parseInt(dateInfo[0])) {
                return datetime;
            }
            if(0 < curMonth - parseInt(dateInfo[1])) {
                return datetime;
            }
            var passTime   = 0 >= curDay - parseInt(dateInfo[2]) ? '' : curDay - parseInt(dateInfo[2]) + '天';
            passTime       += 0 >= curHour - parseInt(timeInfo[0]) ? '' : curHour - parseInt(timeInfo[0]) + '小时';
            passTime       += 0 >= curMin - parseInt(timeInfo[1]) ? '' : curMin - parseInt(timeInfo[1]) + '分';
            if('' === passTime) {
                var curSec     = date.getSeconds();
                return 20 > curSec - parseInt(timeInfo[2]) ? '刚刚' : curSec - parseInt(timeInfo[2]) + '秒';
            }

            return passTime + '以前';
         },

         /**
          * 格式化文件大小单位
          * 
          * 分为B, KB, MB, GB, TB 等5个级别
          * 
          * @author xjiujiu <xjiujiu@foxmail.com>
          * @param size 当前文件大小，B单位
          * @return 格式化后的值
          */
         formatFileSize: function(size) {
            if(1024 > size) {  /* Bytes级 */
                return 0 == size ? '未知' : size + 'Bytes';
            }
            if(1048756 > size) {   /* KB级 */
                return this.float(size / 1024, 2) + 'KB';
            }
            if(1073741824 > size) { /* M级 */
                return this.float(size / 1048756, 2) + 'M';
            }
            if(1099511627776 > size) {  /* G级 */
                return this.float(size / 1073741824, 2) + 'G';
            }
            if(1125899906842624 > $size) { /* T级 */
                return this.float(size / 1099511627776, 2) + 'T';
            }
         },
         float: function(val, number) {
             val        = val.toString();
             var loc    = val.indexOf('.');
             if(0 > loc || (loc + 3) > val.length) {
                 return val;
             }

             return val.substring(0, loc + 3);
         }
     };
     //注册到HHJsLib的扩展中
     HHJsLib.extend(Utils);
 })(jQuery);
