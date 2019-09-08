            <a href="#" id="menu-toggler"><span></span></a><!-- menu toggler -->
			<div id="sidebar" class="menu-min">
				
				<div id="sidebar-shortcuts">
					<div id="sidebar-shortcuts-large">
						<a class="btn btn-small btn-success" href="<?php echo
                        HResponse::url('category', '', 'admin'); ?>"><i class="icon-tag" title="<?php HTranslate::__('分类'); ?>"></i></a>
						<a class="btn btn-small btn-info" href="<?php echo HResponse::url('article', '', 'admin'); ?>" title="<?php HTranslate::_('文章'); ?>"><i class="icon-pencil"></i></a>
						<a class="btn btn-small btn-warning" href="<?php echo HResponse::url('user', '', 'admin'); ?>" title="<?php HTranslate::_('会员'); ?>"><i class="icon-group"></i></a>
						<a class="btn btn-small btn-danger" href="<?php echo HResponse::url('website', '', 'admin'); ?>" title="<?php HTranslate::_('网站管理'); ?>"><i class="icon-cogs"></i></a> 
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
					  <a target="_blank" href="<?php echo HResponse::url('', '', 'admin'); ?>" class="single">
						<i class="icon-dashboard"></i>
						<span><?php HTranslate::_('控制面板'); ?></span>
						
					  </a>
					</li>
					<li>
					  <a href="<?php echo HResponse::url('resource'); ?>" class="single" >
                        <i class="icon-folder-open-alt"></i>
						<span><?php HTranslate::_('文件管理'); ?></span>
					  </a>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
						<i class="icon-cog"></i>
						<span><?php HTranslate::_('系统管理'); ?></span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
                        <li><a href="<?php echo HResponse::url('themes'); ?>"><i class="icon-double-angle-right"></i> 模板配置</a></li>
					  </ul>
					</li>
					<li>
					  <a href="###" class="dropdown-toggle" >
                      <i class="icon-fighter-jet"></i>
						<span>国际化配置</span>
						
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('translate'); ?>"><i class="icon-double-angle-right"></i>语言翻译</a></li>
                        <li><a href="<?php echo HResponse::url('mark'); ?>"><i class="icon-double-angle-right"></i>语言标识</a></li>
                        <li><a href="<?php echo HResponse::url('langtool'); ?>"><i class="icon-double-angle-right"></i>生成语言配置</a></li>
					  </ul>
					</li>
					
					<li>
					  <a href="###" class="dropdown-toggle" >
                        <i class="icon-legal"></i>
						<span><?php HTranslate::_('生成工具'); ?></span>
						
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="<?php echo HResponse::url('modelmanager'); ?>"><i class="icon-double-angle-right"></i>模块生成</a></li>
						<li><a href="<?php echo HResponse::url('dbtool'); ?>"><i class="icon-double-angle-right"></i>数据库管理</a></li>
					  </ul>
					</li>
					
				</ul><!--/.nav-list-->
				<div id="sidebar-collapse"><i class="icon-double-angle-left"></i></div>
			</div><!--/#sidebar-->

