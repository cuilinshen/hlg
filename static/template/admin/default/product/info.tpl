<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
	</head>
	<body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
		<div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('admin') . '/common/sidebar.tpl'); ?>
			<div id="main-content" class="clearfix">
                <?php 
                    $copyRecord     = HResponse::getAttribute('copyRecord'); 
                    $record         = HResponse::getAttribute('record'); 
                ?>   
                <?php require_once(HResponse::path('admin') . '/common/cur-location.tpl'); ?>
                    <div class="row-fluid">
                    <!-- PAGE CONTENT BEGINS HERE -->
                        <form action="<?php echo HResponse::url($modelEnName . '/' . HResponse::getAttribute('nextAction') ); ?>" method="post" enctype="multipart/form-data" id="info-form">
                            <div class="row-fluid">

                                <div class="span9 content-box">
                                <!-- PAGE CONTENT BEGINS HERE -->
                                    
                                    <div class="tabbable">  
                                         <ul class="nav nav-tabs" id="myTab">
                                            <li class="active">
                                                <a data-toggle="tab" href="#tab-baseinfo">
                                                    <i class="green icon-info-sign bigger-110"></i>
                                                    基本信息
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#tab-pic">
                                                    轮播图
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">  
                                            <div id="tab-baseinfo" class="tab-pane in active">
                                                <div id="tab-baseinfo" class="tab-pane in active">
                                                    <?php $field = 'id'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
                                                    <?php $record = !$record ? $copyRecord : $record; ?>
                                                    <?php $field = 'name'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                                    <?php $field = 'parent_id'; require(HResponse::path('admin') . '/fields/tree.tpl'); ?>
                                                    <?php $field = 'company_id'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
                                                    <?php $field = 'price'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                                    <?php $field = 'address'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                                    <?php $field = 'start_date'; require(HResponse::path('admin') . '/fields/datetime.tpl'); ?>
                                                    <?php $field = 'end_date'; require(HResponse::path('admin') . '/fields/datetime.tpl'); ?>
                                                    <?php $field = 'image_path'; require(HResponse::path('admin') . '/fields/file.tpl'); ?>
                                                    <?php $field = 'city_id'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
                                                    <?php $field = 'total_number'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                                    <?php $field = 'attrs'; require(HResponse::path('admin') . '/fields/textarea.tpl'); ?>
                                                    <?php $field = 'latitude'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                                    <?php $field = 'longitude'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                                    <?php $field = 'description'; require(HResponse::path('admin') . '/fields/textarea.tpl'); ?>
                                                    <?php $field = 'content'; require(HResponse::path('admin') . '/fields/editor.tpl'); ?>
                                                </div>
                                            </div>
                                            <div id="tab-pic" class="tab-pane">
                                                <?php $field = 'hash'; require(HResponse::path('admin') . '/fields/album.tpl'); ?>
                                            </div>
                                        </div>
                                </div>
                                  


                                    
                                    
                                <!-- PAGE CONTENT ENDS HERE -->

                                </div>
                                <div class="span3">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4>发布</h4>
                                            <div class="widget-toolbar">
                                                <a href="#" data-action="collapse">
                                                    <i class="icon-chevron-up"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <?php $field = 'status'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
                                                <?php $field = 'is_recommend'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
                                                <?php $field = 'is_renzheng'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
                                                <?php $field = 'create_time'; require(HResponse::path('admin') . '/fields/datetime.tpl'); ?>

                                                <?php require_once(HResponse::path('admin') . '/common/info-buttons.tpl'); 
 ?>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <?php 
                                        if($modelCfg && '2' == $modelCfg['has_multi_lang']) { 
                                            require_once(HResponse::path('admin') . '/common/lang-widget.tpl'); 
                                            echo '<hr/>';
                                        }
                                    ?>  

                                    <div class="widget-box collapsed">
                                        <div class="widget-header">
                                            <h4>维护</h4>
                                            <div class="widget-toolbar">
                                                <a href="#" data-action="collapse">
                                                    <i class="icon-chevron-up"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <?php $field = 'total_visits'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<?php require_once(HResponse::path('admin') . '/fields/author.tpl'); ?>

                                            </div>
                                        </div>
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
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/album.js"></script>
	</body>
</html>
