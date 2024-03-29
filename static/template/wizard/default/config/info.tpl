﻿<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
	</head>
	<body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
		<div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('wizard') . '/common/sidebar.tpl'); ?>
			<div id="main-content" class="clearfix">
                    <?php $record         = HResponse::getAttribute('record'); ?>    
                    <?php require_once(HResponse::path('admin') . '/common/cur-location.tpl'); ?>
						<div class="row-fluid">
                        <!-- PAGE CONTENT BEGINS HERE -->
                            <form class="form-horizontal" action="<?php echo HResponse::url('wizard/' . $modelEnName . '/' . HResponse::getAttribute('nextAction') ); ?>" method="post" enctype="multipart/form-data" id="info-form">
                                <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
                               <div class="tabbable tabs-right tabs-shadow tabs-space">
                                <?php require_once(HResponse::path('admin') . '/common/tabs-sidebar.tpl'); ?>
                                <div class="tab-content">
                                  <div id="base-box" class="tab-pane in active">
                                    <?php $field = 'id'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
<?php $field = 'site_name'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php $field = 'image_path'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php $field = 'administrator'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php $field = 'qq'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php $field = 'email'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php $field = 'phone'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php $field = 'weibo'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php $field = 'address'; require(HResponse::path('admin') . '/fields/textarea.tpl'); ?>
<?php $field = 'copyright'; require(HResponse::path('admin') . '/fields/textarea.tpl'); ?>
<?php require_once(HResponse::path('admin') . '/fields/lang_type.tpl'); ?>

                                  </div>
                                  <div id="seo-box" class="tab-pane">
                                    <?php $field = 'seo_keywords'; require(HResponse::path('admin') . '/fields/textarea.tpl'); ?>
<?php $field = 'seo_desc'; require(HResponse::path('admin') . '/fields/textarea.tpl'); ?>

                                  </div>
                                  <div id="manage-box" class="tab-pane">
                                    <?php $field = 'create_time'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php require_once(HResponse::path('admin') . '/fields/author.tpl'); ?>

                                  </div>
                                <?php require_once(HResponse::path('admin') . '/common/info-form-buttons.tpl'); ?>
                              </div>
                             </form>
                        <!-- PAGE CONTENT ENDS HERE -->
						 </div><!--/row-->
					</div><!--/#page-content-->
                <?php require_once(HResponse::path('admin') . '/common/setting-bar.tpl'); ?>  
			</div><!-- #main-content -->
		</div><!--/.fluid-container#main-container-->
        <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/info.js"></script>
	</body>
</html>
