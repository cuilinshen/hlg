            <a href="#" id="menu-toggler"><span></span></a><!-- menu toggler -->
            <?php $isMiniStyle = HSession::getAttribute('isMiniStyle');?>
            <div id="sidebar" <?php echo $isMiniStyle ? 'class="menu-min"' : ''; ?> data-spy="affix" data-offset-top="60">
				<div id="sidebar-shortcuts">
					<div id="sidebar-shortcuts-large">
						<a class="btn btn-small btn-success" href="<?php echo HResponse::url('product'); ?>"><i class="icon-shopping-cart" title="商品管理"></i></a>
						<a class="btn btn-small btn-info" href="<?php echo HResponse::url('company'); ?>" title="店铺管理"><i class="icon-list"></i></a>
						<a class="btn btn-small btn-warning" href="<?php echo
                        HResponse::url('user/addview'); ?>" title="<?php HTranslate::_('用户'); ?>"><i class="icon-group"></i></a>
						<a class="btn btn-small btn-danger" href="<?php echo HResponse::url('information'); ?>" title="网站信息"><i class="icon-info"></i></a> 
					</div>
					<div id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>
						<span class="btn btn-info"></span>
						<span class="btn btn-warning"></span>
						<span class="btn btn-danger"></span>
					</div>
				</div><!-- #sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li>
					  <a href="<?php echo HResponse::url('', '', 'admin'); ?>" class="single">
						<i class="icon-dashboard"></i>
						<span><?php HTranslate::_('后台桌面'); ?></span>
					  </a>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-list "></i>
						<span>活动方管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('company'); ?>"><i class="icon-double-angle-right"></i> 活动方列表</a></li>
						<li><a href="<?php echo HResponse::url('company/addview'); ?>"><i class="icon-double-angle-right"></i> 添加新活动方</a></li>
					  </ul>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-shopping-cart"></i>
						<span>活动管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
					  	<li><a href="<?php echo HResponse::url('product/caiji', 'status=1'); ?>"><i class="icon-double-angle-right"></i> 活动采集</a></li>
						<li><a href="<?php echo HResponse::url('product/search', 'status=1'); ?>"><i class="icon-double-angle-right"></i> 上架活动列表</a></li>
						<li><a href="<?php echo HResponse::url('product/search', 'status=2'); ?>"><i class="icon-double-angle-right"></i> 下架活动列表</a></li>
						<li><a href="<?php echo HResponse::url('product/addview'); ?>"><i class="icon-double-angle-right"></i> 添加新活动</a></li>
						<li><a href="<?php echo HResponse::url('comment'); ?>"><i class="icon-double-angle-right"></i> 活动评论</a></li>
                        <?php
                            foreach(HResponse::getAttribute('rootCatList') as $key => $cat) {
                                if($cat['identifier'] != 'goods-attribute-cat'){
                                    continue;
                                }
                        ?>
                        <li><a href="<?php echo HResponse::url('category/search', 'type=' . $cat['id']); ?>"><i class="icon-double-angle-right"></i> <?php echo $cat['name']; ?></a></li>
                        <?php } ?>
                        <!--<li><a href="<?php echo HResponse::url('attribute'); ?>"><i class="icon-double-angle-right"></i> 商品属性规格</a></li>-->
					  </ul>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-yen"></i>
						<span>订单管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('order'); ?>"><i class="icon-double-angle-right"></i> 订单列表</a></li>
						<li><a href="<?php echo HResponse::url('order/daizhifu'); ?>"><i class="icon-double-angle-right"></i> 待支付订单</a></li>
                        <li><a href="<?php echo HResponse::url('order/tuikuan'); ?>"><i class="icon-double-angle-right"></i> 退款退货</a></li>
					  </ul>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-bar-chart"></i>
						<span>报表统计</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('reportcount/ordercount'); ?>"><i class="icon-double-angle-right"></i> 订单统计</a></li>
						<li><a href="<?php echo HResponse::url('reportcount/usersort'); ?>"><i class="icon-double-angle-right"></i> 会员排行</a></li>
						<li><a href="<?php echo HResponse::url('reportcount/goodssale'); ?>"><i class="icon-double-angle-right"></i> 活动排行</a></li>
                        <li><a href="<?php echo HResponse::url('reportcount/shopsale'); ?>"><i class="icon-double-angle-right"></i> 商户排行</a></li>
					  </ul>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-adn"></i>
						<span>广告管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('adv'); ?>"><i class="icon-double-angle-right"></i> 广告列表</a></li>
						<li><a href="<?php echo HResponse::url('adv/addview'); ?>"><i class="icon-double-angle-right"></i> 添加新广告</a></li>
					  </ul>
					</li>
					<!--
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-gift"></i>
						<span>促销管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('activity'); ?>"><i class="icon-double-angle-right"></i> 活动管理</a></li>
						<li><a href="<?php echo HResponse::url('coupon'); ?>"><i class="icon-double-angle-right"></i> 优惠券管理</a></li>
					  </ul>
					</li>
					-->
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-group"></i>
						<span>用户管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('user'); ?>"><i class="icon-double-angle-right"></i><?php HTranslate::_('系统用户'); ?></a></li>
                        <li><a href="<?php echo HResponse::url('actor'); ?>"><i class="icon-double-angle-right"></i>系统角色</a></li>
					  </ul>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-file"></i>
						<span>文章管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('article'); ?>"><i class="icon-double-angle-right"></i> 文章列表</a></li>
						<li><a href="<?php echo HResponse::url('article/addview'); ?>"><i class="icon-double-angle-right"></i> 添加新文章</a></li>
						<li><a href="<?php echo HResponse::url('category/article'); ?>"><i class="icon-double-angle-right"></i> 文章类别</a></li>
					  </ul>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-folder-open"></i>
						<span>分类管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('category/addview'); ?>"><i class="icon-double-angle-right"></i> 添加分类</a></li>
                        <?php
                            foreach(HResponse::getAttribute('shangmengCatList') as $key => $cat) {
                            if($cat['parent_id'] != '479'){
                                continue;
                            }
                        ?>
						<li><a href="<?php echo HResponse::url('category/search', 'type=' . $cat['id']); ?>"><i class="icon-double-angle-right"></i> <?php echo $cat['name']; ?></a></li>
                        <?php if(6 < $key ) { ?>
						<li>
							<a href="<?php echo HResponse::url('category'); ?>">
								<i class="icon-double-angle-right"></i> 
								<i class="icon-ellipsis-horizontal"></i>
							</a>
						</li>
                        <?php 
	                        	break; 
	                        } 
                        ?>
                        <?php } ?>
					  </ul>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-cog"></i>
						<span>网站管理</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('information'); ?>"><i class="icon-double-angle-right"></i> 网站设置</a></li>
						<li><a href="<?php echo HResponse::url('contact'); ?>"><i class="icon-double-angle-right"></i> 联系方式</a></li>
                      </ul>
					</li>

				</ul><!--/.nav-list-->
				<div id="sidebar-collapse"><i class="icon-double-angle-left"></i></div>
			</div><!--/#sidebar-->

