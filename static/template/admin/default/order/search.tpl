<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
    </head>
    <body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
        <div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('admin') . '/common/sidebar.tpl'); ?>
            <div id="main-content" class="clearfix">
                <div id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home"></i> <a href="<?php echo HResponse::url('', '', 'admin'); ?>">后台桌面</a>
                            <span class="divider"><i class="icon-angle-right"></i></span>
                        </li>
                        <li><a href="<?php echo HResponse::url($modelEnName); ?>"><?php echo $modelZhName; ?></a> <span class="divider"><i class="icon-angle-right"></i></span></li>
                        <li class="active">订单搜索</li>
                    </ul><!--.breadcrumb-->
                    <div id="nav-search">
                        <span id="time-info">正在加载时钟...</span>
                    </div><!-- #nav-search -->
                </div><!-- #breadcrumbs -->
                <div id="page-content" class="clearfix">
                    <div class="page-header position-relative">
                        <h1><i class="icon icon-search"></i> 订单搜索条件</h1>
                    </div><!--/page-header--> 
                    <div class="row-fluid">
                    <!-- PAGE CONTENT BEGINS HERE -->
                        <form action="<?php echo HResponse::url('order/search');?>" method="post"  id="info-form">
                            <div class="content-box">
                            <!-- PAGE CONTENT BEGINS HERE -->
                                <div class="row-fluid">
                                    <div class="span6">
                                        <div class="control-group" id="name-box">
                                            <label class="control-label" for="keywords">
                                                订单号：
                                            </label>
                                            <div class="controls">
                                                <input type="text" id="keywords" name="keywords" class="span12 input-field-keywords" value="" />
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="control-group" id="name-box">
                                            <label class="control-label" for="user">
                                                下单用户名：
                                            </label>
                                            <div class="controls">
                                                <input type="text" id="user" name="user" class="span12 input-field-user" value="" />
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="control-group" id="name-box">
                                            <label class="control-label" for="name">
                                                订单状态：
                                            </label>
                                            <div class="controls">
                                                <select name="status" id="status" class="span12">
                                                    <option value="">请选择</option>
                                                    <?php foreach(OrderPopo::$statusMap as $item) { ?>
                                                    <option value="<?php  echo $item['id'];?>"><?php echo $item['name'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group" id="name-box">
                                                    <label class="control-label" for="start_date">
                                                        下单时间从：
                                                    </label>
                                                    <div class="controls">
                                                        <div class="input-append date">
                                                            <input type="text" id="start_date" name="start_date" class="span12 datetime-picker input-field-start_date" value=""
                                                                   data-date-format="yyyy-mm-dd hh:ii:ss" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                        </div>
                                                        <script type="text/javascript">
                                                            datetimeList.push("#start_date");
                                                        </script>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group" id="name-box">
                                                    <label class="control-label" for="end_date">
                                                        到：
                                                    </label>
                                                    <div class="controls">
                                                        <div class="input-append date">
                                                            <input type="text" id="end_date" name="end_date" class="span12 datetime-picker input-field-end_date" value=""
                                                                   data-date-format="yyyy-mm-dd hh:ii:ss" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                        </div>
                                                        <script type="text/javascript">
                                                            datetimeList.push("#end_date");
                                                        </script>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group" id="name-box">
                                                    <label class="control-label" for="pay_start_date">
                                                        付款时间从：
                                                    </label>
                                                    <div class="controls">
                                                        <div class="input-append date">
                                                            <input type="text" id="pay_start_date" name="pay_start_date" class="span12 datetime-picker input-field-pay_start_date" value=""
                                                                   data-date-format="yyyy-mm-dd hh:ii:ss" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                        </div>
                                                        <script type="text/javascript">
                                                            datetimeList.push("#pay_start_date");
                                                        </script>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group" id="name-box">
                                                    <label class="control-label" for="pay_end_date">
                                                        到：
                                                    </label>
                                                    <div class="controls">
                                                        <div class="input-append date">
                                                            <input type="text" id="pay_end_date" name="pay_end_date" class="span12 datetime-picker input-field-pay_end_date" value=""
                                                                   data-date-format="yyyy-mm-dd hh:ii:ss" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                        </div>
                                                        <script type="text/javascript">
                                                            datetimeList.push("#pay_end_date");
                                                        </script>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group" id="name-box">
                                            <label class="control-label" for="name">
                                                发货状态：
                                            </label>
                                            <div class="controls">
                                                <select name="status" id="status" class="span12">
                                                    <option value="">请选择</option>
                                                    <?php foreach(OrderPopo::$processMap as $item) { ?>
                                                    <option value="<?php  echo $item['id'];?>"><?php echo $item['name'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="control-group text-left btn-form-box" data-spy="affix" data-offset-top="160" >
                                    <button type="submit" class="btn btn-success ">
                                        开始搜索
                                    </button>
                                </div>
                            <!-- PAGE CONTENT ENDS HERE -->
                            </div>
                         </form>
                    <!-- PAGE CONTENT ENDS HERE -->
                     </div><!--/row-->
                </div><!--/#page-content-->
            </div><!-- #main-content -->
        </div><!--/.fluid-container#main-container-->
        <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/order-search.js"></script>
    </body>
</html>
