<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
        <link rel="stylesheet" href="<?php echo HResponse::uri('admin'); ?>/css/chosen.css" />
	</head>
	<body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
		<div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('wizard') . '/common/sidebar.tpl'); ?>
			<div id="main-content" class="clearfix">
                    <?php  $record         = HResponse::getAttribute('record');   ?>    
                    <?php require_once(HResponse::path('admin') . '/common/cur-location.tpl'); ?>
						<div class="row-fluid">
                        <!-- PAGE CONTENT BEGINS HERE -->
                            <form class="form-horizontal" action="<?php echo HResponse::url('admin/' . $modelEnName . '/' . HResponse::getAttribute('nextAction') ); ?>" method="post" enctype="multipart/form-data" id="info-form">
                                <div class="tabbable tabs-right tabs-shadow tabs-space">
                                    <?php require_once(HResponse::path('admin') . '/common/tabs-sidebar.tpl'); ?>
                                    <div class="tab-content">
                                      <div id="base-box" class="tab-pane in active">
                                        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
                                        <input type="hidden" name="id" value="<?php echo $record['id'];?>" />
                                        <?php $field = 'sort_num'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                        <?php
                                            $langList   = HResponse::getAttribute('langList');
                                            foreach(HResponse::getAttribute('langTypeList') as $type) { 
                                        ?>
                                        <div class="control-group">
                                            <label class="control-label" for="name"><?php echo $type['name']; ?></label>
                                            <div class="controls">
                                                <textarea class="span6" placeholder="翻译" name="content[]" id="<?php echo $type['en_name']?>"><?php echo $langList[$type['id']]['name']; ?></textarea>
                                                <span class="help-inline"><?php echo $type['name']; ?>翻译内容。</span>
                                                <input type="hidden" name="lid[]" value="<?php echo $langList[$type['id']]['id']; ?>"/>
                                                <input type="hidden" name="langtype[]" value="<?php echo $type['id']; ?>"/>
                                            </div>
                                        </div>
                                        <?php }?>
                                        <?php $field = 'tpl'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
                                        <?php $field = 'jump_url'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                        <?php $field = 'description'; require(HResponse::path('admin') . '/fields/textarea.tpl'); ?>
                                        <?php $field = 'image_path'; require(HResponse::path('admin') . '/fields/image.tpl'); ?>
                                      </div>
                                      <div id="manage-box" class="tab-pane">
                                        <?php $field = 'create_time'; require(HResponse::path('admin') . '/fields/date.tpl'); ?>
                                        <?php require_once(HResponse::path('admin') . '/fields/author.tpl'); ?>
                                      </div>
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
        <script type="text/javascript" src="<?php echo HResponse::uri('vendor'); ?>/jquery/plugins/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/info.js"></script>
        <script type="text/javascript" src="<?php echo HResponse::uri('vendor'); ?>/hhjslib/hhjslib.hhtranslate.js"></script>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/langmask.js"></script>
	</body>
</html>
