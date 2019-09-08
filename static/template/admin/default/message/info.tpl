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
                    $replyList      = HResponse::getAttribute('replyList'); 
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
                                                <a data-toggle="tab" href="#tab-chengjiu">
                                                    咨询回复
                                                </a>
                                            </li>
                                        </ul>
                                    <div class="tab-content">  
                                        <div id="tab-baseinfo" class="tab-pane in active">
                                            <?php $field = 'id'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
                                            <?php $record = !$record ? $copyRecord : $record; ?>
                                            <?php $field = 'name'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                            <?php $field = 'parent_id'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
                                            <?php $field = 'title'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                        </div>
                                        <div id="tab-chengjiu" class="tab-pane">
                                                <div class="control-group">
                                                    <label class="control-label" for="reply-content">回复内容： </label>
                                                    <div class="controls">
                                                        <textarea class="autosize-transition span12 h-100" placeholder="请添加回复内容" id="reply-content"></textarea>
                                                        <small class="help-info">请输入回复内容</small>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="control-group text-right">
                                                    <button type="button" id="reply-add-btn" class="btn btn-success btn-small">添加回复</button>
                                                </div> 
                                            
                                                <div>
                                                      <div class="row-fluid"> 
                                                           <!-- PAGE CONTENT BEGINS HERE --> 
                                                           <div id="table_report_wrapper" class="dataTables_wrapper" role="grid"> 
                                                            <form action="http://localhost/yaqin-jingrong/index.php/admin/sku/quick" method="post" id="list-form"> 
                                                             <table id="data-grid-box" class="table table-striped table-bordered table-hover"> 
                                                              <thead> 
                                                               <tr> 
                                                                <th class="field-id header" title="只能是数字">ID</th>
                                                                <th class="field-name header" title="回复内容">回复内容</th>
                                                                <th class="field-time header" title="回复时间">回复时间</th>
                                                                <th class="header">操作</th> 
                                                               </tr> 
                                                              </thead> 
                                                              <tbody id="sku-tbody"> 
                                                               <?php foreach($replyList as $key => $item) {
                                                                ?>
                                                               <tr class="odd" id="sku-<?php echo $item['id']; ?>">
                                                                <td class="field field-id"><?php echo $item['id']; ?></td>
                                                                <td class="field field-name"><?php echo $item['content']; ?></td>
                                                                <td class="field field-name"><?php echo $item['create_time']; ?></td>
                                                                <td> 
                                                                <div class="btn-group"> 
                                                                  <a href="###" title="删除信息" data-id="<?php echo $item['id']; ?>" class="btn btn-mini btn-danger delete sku-del"><i class="icon-trash"></i></a> 
                                                                 </div></td> 
                                                               </tr> 
                                                              <?php } ?>
                                                              </tbody> 
                                                             </table> 
                                                           
                                                            </form> 
                                                            <!-- PAGE CONTENT ENDS HERE --> 
                                                           </div>
                                                           <!--/row--> 
                                                          </div>

                                                </div>

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
                                                <?php $field = 'create_time'; require(HResponse::path('admin') . '/fields/datetime.tpl'); ?>
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
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/message.js"></script>
	</body>
</html>
