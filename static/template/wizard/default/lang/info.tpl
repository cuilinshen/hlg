<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
        <link rel="stylesheet" href="<?php echo HResponse::uri('admin'); ?>/css/chosen.css" />
	</head>
	<body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
		<div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('wizard') . '/common/sidebar.tpl'); ?>
			<div id="main-content" class="clearfix">
                    <?php  $record         = HResponse::getAttribute('record');  ?>    
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
                                        <?php $field = 'mask_id'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                        <?php $field = 'name'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                        <?php $field = 'parent_id'; require(HResponse::path('admin') . '/fields/tree.tpl'); ?>
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
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="<?php echo HResponse::uri('vendor'); ?>/jquery/plugins/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/info.js"></script>
        <script type="text/javascript">
            $(function() {
                $.getJSON(
                    siteUrl + "/index.php/admin/langmask/aload",
                    function( data ) {
                        if(data.rs == false) {
                            alert(data.info);
                            return;
                        }
                        var optionHtml  = '<option value="">--请选择标识--</option>';
                        for(var ele in data.list) {
                            optionHtml  += '<option value="' + data.list[ele].id + '">' + data.list[ele].name + '</option>';
                        }
                        $("#mask_id").html(optionHtml);
                        HHJsLib.autoSelect('#mask_id');
                        $("select#mask_id").chosen(); 
                    }
                );
                /*$("div.chzn-search input").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            url: siteUrl + "/index.php/admin/langmask/asearch",
                            data: {mask_id: request.term},
                            beforeSend: function(){$('ul.chzn-results').empty();},
                            success: function( data ) {
                                if(data.rs == false) {
                                    alert(data.info);
                                    return;
                                }
                                var liHtml      = "";
                                var optionHtml  = '<option value="">--请选择标识--</option>';
                                for(var ele in data.list) {
                                    optionHtml  += '<option>' + data.list[ele].name + '</option>';
                                    liHtml      += '<li class="active-results active-result">' + data.list[ele].name + '</li>';
                                }
                                $("#mask_id").html(optionHtml);
                                $("select#mask_id").chosen(); 
                            }
                        });
                    }
                });*/
            }); 
        </script>
	</body>
</html>
