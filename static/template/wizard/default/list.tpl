<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
	</head>
	<body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
		<div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('wizard') . '/common/sidebar.tpl'); ?>
			<div id="main-content" class="clearfix">
                    <?php require_once(HResponse::path('wizard') . '/common/cur-location.tpl'); ?>
						<div class="row-fluid">
                            <!-- PAGE CONTENT BEGINS HERE -->
                            <div id="table_report_wrapper" class="dataTables_wrapper" role="grid">
                                <div class="row-fluid">
                                    <form id="search-form" action="<?php echo HResponse::url('' . $modelEnName . '/search'); ?>" method="get">
                                        <div class="span4">
                                            <div id="table_report_length" class="dataTables_length">
                                                    <label>每页显示:
                                                        <select size="1" name="perpage" id="perpage" aria-controls="table_report" cur="<?php echo HRequest::getParameter('perpage'); ?>">
                                                            <option value="10" selected="selected">10</option>
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                        </select>
                                                        条
                                                </label>
                                            </div>
                                        </div>
                                        <div class="span7 txt-right f-right">
                                            <div class="dataTables_filter" id="table_report_filter">
                                                <?php if(HResponse::getAttribute('parent_id_list')) { ?>
                                                <label><?php echo str_replace('分类', '', $modelZhName); ?>分类: 
                                                    <select name="type" id="category" class="input-medium" cur="<?php echo HRequest::getParameter('type'); ?>">
                                                        <option value="0">全部</option>
                                                        <?php foreach(HResponse::getAttribute('parent_id_list') as $type) { ?>
                                                        <option value="<?php echo $type['id'] . '">' . $type['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </label>
                                                <?php } ?>
                                                <label>关键字: 
                                                    <input type="text" class="input-medium search-query" name="keywords" id="keywords" value="<?php echo !HRequest::getParameter() ? '关键字...' : HRequest::getParameter('keywords'); ?>">
                                                    <button type="submit" class="btn btn-purple btn-small">搜索<i class="icon-search icon-on-right"></i></button>
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php require_once(HResponse::path('admin') . '/fields/data-grid.tpl'); ?>
                            <!-- PAGE CONTENT ENDS HERE -->
                             </div><!--/row-->
                            </div><!--/#page-content-->
                            <?php require_once(HResponse::path('admin') . '/common/setting-bar.tpl'); ?>  
                        </div><!-- #main-content -->
                    </div><!--/.fluid-container#main-container-->
         <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
		<script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/list.js"></script>       
	</body>
</html>
