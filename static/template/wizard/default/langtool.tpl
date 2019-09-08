<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
	</head>
	<body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
		<div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('wizard') . '/common/sidebar.tpl'); ?>
			<div id="main-content" class="clearfix">
                    <div id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
                                <i class="icon-home"></i> <a href="<?php echo HResponse::url('admin'); ?>">控制面板</a>
                                <span class="divider"><i class="icon-angle-right"></i></span>
                            </li>
							<li class="active"><?php echo $modelZhName; ?>生成语言配置文件</li>
						</ul><!--.breadcrumb-->
						<div id="nav-search">
							<form class="form-search" action="<?php echo HResponse::url('admin/user/search'); ?>" method="post">
                                <span class="input-icon">
                                    <input autocomplete="off" id="nav-search-input" type="text" class="input-small search-query" placeholder="搜索 ..." name="keywords"/>
                                    <i id="nav-search-icon" class="icon-search"></i>
                                </span>
							</form>
						</div><!-- #nav-search -->
					</div><!-- #breadcrumbs -->
                    <div id="page-content" class="clearfix">
						<div class="page-header position-relative">
							<h1>
                                生成工具
                                <small><i class="icon-double-angle-right"></i>生成语言配置文件</small>
                            </h1>
						</div><!--/page-header-->                   
						<div class="row-fluid">
                        <!-- PAGE CONTENT BEGINS HERE -->
                            <form class="form-horizontal" action="<?php echo HResponse::url('langtool/generate'); ?>" method="post" id="info-form">
                                <input type="hidden" value="<?php echo $record['id']; ?>" id="id" name="id"/>
                                <div class="control-group">
                                    <label class="control-label" for="name">语种</label>
                                    <div class="controls">
                                        <select name="langtype">
                                            <option value="">--请选择语言--</option>
                                            <option value="all">--全部--</option>
                                            <?php foreach(HResponse::getAttribute('lang_id_list') as $langType) { ?>
                                            <option value="<?php echo $langType['id']; ?>"><?php echo $langType['name']; ?></option>
                                            <?php }?>
                                        </select>
                                        <span class="help-inline">选择需要生成的语种。</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="name">模板文件</label>
                                    <div class="controls">
                                        <select name="tpl">
                                            <option value="">--请模板文件--</option>
                                            <option value="all">--全部--</option>
                                            <option value="app">--按应用生成--</option>
                                            <?php
                                            foreach(HResponse::getAttribute('tplList') as $tpl) { ?>
                                            <option value="<?php echo $tpl['id']; ?>"><?php echo $tpl['name']; ?></option>
                                            <?php }?>
                                        </select>
                                        <span class="help-inline">选择需要生成模板。</span>
                                    </div>
                                </div>
                                <?php require_once(HResponse::path('admin') . '/common/info-form-buttons.tpl'); ?>
                             </form>
                        <!-- PAGE CONTENT ENDS HERE -->
						 </div><!--/row-->
					</div><!--/#page-content-->
                <?php require_once(HResponse::path('admin') . '/common/setting-bar.tpl'); ?>  
			</div><!-- #main-content -->
		</div><!--/.fluid-container#main-container-->
        <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
	</body>
</html>
