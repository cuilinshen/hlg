<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
	</head>
    <?php
        $applyShops     = HResponse::getAttribute('applyShops');
        $allShops = HResponse::getAttribute('allShops');
        $users     = HResponse::getAttribute('users');
        $infoRecord  = HResponse::getAttribute('infoRecord');
    ?>
	<body>
        <?php $actorName    = HSession::getAttribute('actor', 'user'); ?>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
		<div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('admin') . '/common/sidebar.tpl'); ?>
			<div id="main-content" class="clearfix">
                <div id="breadcrumbs">

                    <ul class="breadcrumb">

                        <li>
                            <i class="icon-home"></i> <a href="<?php echo HResponse::url('', '', 'admin'); ?>">后台桌面</a>
                        </li>
                    </ul><!--.breadcrumb-->



                    <div id="nav-search">

                        <span id="time-info">正在加载时钟...</span>
                    </div><!--#nav-search-->

                </div><!--#breadcrumbs-->

                <div id="page-content" class="clearfix">
                    <div class="page-header position-relative">
                        <h1>后台桌面<small><i class="icon-double-angle-right"></i> 功能 & 概况</small></h1>
                    </div><!--/page-header-->
                    <div class="number-box text-center">
                        <div class="row-fluid">
                            <div class="span2">
                                <a href="<?php echo HResponse::url('order/waitpay'); ?>">
                                    <strong class="fs-22"><?php echo $applyShops; ?></strong>
                                    待审核商户
                                </a>
                            </div>
                            <div class="span2">
                                <a href="<?php echo HResponse::url('order/fahuo'); ?>">
                                    <strong class="fs-22"><?php echo $allShops; ?></strong>
                                    总商户量
                                </a>
                            </div>
                            <div class="span2">
                                <a href="<?php echo HResponse::url('activity/search', 'status=2&t=' . time()); ?>">
                                    <strong class="fs-22"><?php echo $users; ?></strong>
                                    总活动数
                                </a>
                            </div>
                            <div class="span2">
                                <a href="<?php echo HResponse::url('activity/search', 'status=2&t=' . time()); ?>">
                                    <strong class="fs-22"><?php echo $users; ?></strong>
                                    总会员数
                                </a>
                            </div>
                            <div class="span2">
                                <a href="<?php echo HResponse::url('goods/search', 'status=2'); ?>">
                                    <strong class="fs-22"><?php echo $infoRecord['total_visits']; ?></strong>
                                    总访问量
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="space-6"></div>
                        <div class="row-fluid">
                         <div class="span12">
                            <div class="widget-box transparent">
                                <div class="widget-header">
                                    <h4 class="lighter smaller"><i class="icon-th-large orange"></i>功能项目</h4>
                                    <div class="widget-toolbar no-border">
                                        <ul class="nav nav-tabs" id="model-tab">
                                            <?php 
                                                $catModelList   = HResponse::getAttribute('catModelList');
                                                $list           = HResponse::getAttribute('list');
                                                foreach($catModelList as $cat) { 
                                            ?>
                                            <li><a data-toggle="tab" href="#<?php echo $cat['identifier']; ?>-tab"><?php echo $cat['name']; ?></a></li>
                                            <?php }?>
                                            <!--
                                            <li><a data-toggle="tab" href="#system-tab">系统模块</a></li>
                                            -->
                                        </ul>
                                    </div>
                                </div>
                                <div class="widget-body">
                                 <div class="widget-main padding-5">
                                    <div class="tab-content padding-8">
                                        <?php foreach($catModelList as $cat) { ?>
                                        <div id="<?php echo $cat['identifier']; ?>-tab" class="tab-pane">
                                            <div class="row-fluid clearfix">
                                                <?php 
                                                    foreach($list as $model) { 
                                                        //如果是关闭状态则不显示出来
                                                        if($model['status'] != 1) {
                                                            continue;
                                                        }
                                                        if($model['type'] != $cat['id']) {
                                                            continue;
                                                        }
                                                        //管理员
                                                        if('root' != $actorName && in_array($model['identifier'], array('user', 'huizhimsg'))) {
                                                            continue;
                                                        }
                                                        //业务管理员
                                                        if(in_array($actorName, array('city_member', 'county_member', 'dot_member')) && !in_array($model['identifier'], array('order', 'bank', 'huizhimsg', 'article', 'message'))) {
                                                            continue;
                                                        }
                                                        if($model['identifier'] == 'message') {
                                                            if(!in_array($actorName, array('root', 'city_member'))) {
                                                                continue;
                                                            }
                                                        }
                                                ?>
                                                <div class="itemdiv memberdiv">
                                                    <div class="user">
                                                        <img alt="<?php echo $model['name']; ?>" src="<?php echo HResponse::url() . $model['image_path']; ?>" />
                                                    </div>
                                                    <div class="body">
                                                        <div class="name"><a href="<?php echo HResponse::url($model['identifier']); ?>"><?php echo $model['name']; ?></a></div>
                                                        <div class="time"><i class="icon-info-sign"></i> <span class="green"><?php echo $model['description'];?></span></div>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <?php }?>
                                        <div id="system-tab" class="tab-pane">
                                            <div class="clearfix">
                                                <div class="itemdiv memberdiv">
                                                    <div class="user">
                                                        <img alt="模块状态" src="<?php echo HResponse::uri('admin'); ?>/images/status.png" />
                                                    </div>
                                                    <div class="body">
                                                        <div class="name"><a href="<?php echo HResponse::url('modelstatus'); ?>">模块状态</a></div> <div class="time"><i class="icon-time"></i><span class="green">系统模块状态信息</span></div>
                                                    </div>
                                                </div>
                                                <div class="itemdiv memberdiv">
                                                    <div class="user">
                                                        <img alt="服务器状态" src="<?php echo HResponse::uri('admin'); ?>/images/server.png" />
                                                    </div>
                                                    <div class="body">
                                                        <div class="name"><a href="<?php echo HResponse::url('server'); ?>">服务器状态</a></div> <div class="time"><i class="icon-time"></i><span class="green">系统服务器信息</span></div>
                                                    </div>
                                                </div>
                                                <div class="itemdiv memberdiv">
                                                    <div class="user">
                                                        <img alt="技术支持" src="<?php echo HResponse::uri('admin'); ?>/images/help.png" />
                                                    </div>
                                                    <div class="body">
                                                        <div class="name"><a href="http://www.hongjuzi.net">技术支持</a></div> <div class="time"><i class="icon-time"></i><span class="green">系统技术问题支持</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                 </div><!--/widget-main-->
                                </div><!--/widget-body-->
                            </div><!--/widget-box-->
                         </div><!--/span-->
                         </div><!-- /row -->
                         <div class="vspace"></div>
                        </div><!--/row-->
                        <!-- PAGE CONTENT ENDS HERE -->
                     </div><!--/row-->
                     <div class="hr hr8"></div>
                </div><!--/#page-content-->
			</div><!-- #main-content -->
		</div><!--/.fluid-container#main-container-->
        <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
        <script type="text/javascript"> $(function() { $("#model-tab li:first a").click(); }); </script>
    </body>
</html>
