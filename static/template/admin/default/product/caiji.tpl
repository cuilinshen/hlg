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
                                <i class="icon-home"></i> <a href="<?php echo HResponse::url('index', '', 'admin'); ?>">后台桌面</a>
                                <span class="divider"><i class="icon-angle-right"></i></span>
                            </li>
                            <li><a href="<?php echo HResponse::url($modelEnName); ?>"><?php echo $modelZhName; ?></a> <span class="divider"><i class="icon-angle-right"></i></span></li>
                            <li class="active">活动采集</li>
                        </ul><!--.breadcrumb-->
                        <div id="nav-search">
                            <span id="time-info">正在加载时钟...</span>
                        </div><!-- #nav-search -->
                </div><!-- #breadcrumbs -->
                <div id="page-content" class="clearfix">
                <div class="page-header position-relative">
                    <h1>
                        活动采集
                        <small>
                        <i class="icon-double-angle-right"></i>
                       
                        </small>
                    </h1>
                </div><!--/page-header-->                   
    

                    <div class="row-fluid">
                    <!-- PAGE CONTENT BEGINS HERE -->
                        <form action="" method="post" enctype="multipart/form-data" id="info-form">
                            <div class="row-fluid">

                                <div class="span12 content-box">
                                <!-- PAGE CONTENT BEGINS HERE -->
                                     
                                    <div class="control-group">
                                        <label class="control-label" for="type">数据来源： </label>
                                        <div class="controls">
                                            <select name="type" id="type" data-cur="" class="auto-select span12">
                                               <option value="1">活动行小程序端</option>
                                               <option value="2">互动吧</option>
                                            </select>
                                            <small class="help-info">请选择数据来源</small>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                     <div class="control-group">
                                        <label class="control-label" for="type">选择城市： </label>
                                        <div class="controls">
                                            <select name="city_id" id="city_id" data-cur="" class="auto-select span12">
                                                <?php foreach(HResponse::getAttribute('city_id_list') as $key => $item) { ?>
                                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <small class="help-info">请选择活动城市</small>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <script type="text/javascript"> selectList.push("#city_id"); </script> 
                                    <div class="control-group">
                                        <label class="control-label" for="type">选择分类： </label>
                                        <div class="controls">
                                            <select name="cate_id" id="cate_id" data-cur="" class="auto-select span12">
                                                <?php foreach(HResponse::getAttribute('category_list') as $key => $item) { ?>
                                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <small class="help-info">请选择分类</small>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="type">采集结果显示： </label>
                                        <div class="controls" id="result-ul">
                                            <small class="help-info">正在执行第一条</small>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr/>
                                    <div class="control-group text-center btn-form-box" data-spy="affix" data-offset-top="160" >
                                        <button type="button" id="caiji-btn" class="btn btn-success btn-small">开始采集</button>
                                    </div>
                                    

                                </div>
                             
                             </div><!--/row-->

                          </div>
                         </form>
                    <!-- PAGE CONTENT ENDS HERE -->
                     </div><!--/row-->
                </div><!--/#page-content-->
			</div><!-- #main-content -->
		</div><!--/.fluid-container#main-container-->
        <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
        <script type="text/javascript"> var fromId = '<?php echo HRequest::getParameter('fid');?>';</script>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/info.js"></script>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/caiji.js"></script>
	</body>
</html>
