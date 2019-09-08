<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
	</head>
	<body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
		<div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('wizard') . '/common/sidebar.tpl'); ?>
			<div id="main-content" class="clearfix">
                    <?php 
                        $record     = HResponse::getAttribute('record');  
                        $modelPopo  = HResponse::getAttribute('model_popo');
                        $modelTable = !$modelPopo ? '' : $modelPopo->get('table');
                    ?>
                    <?php require_once(HResponse::path('wizard') . '/common/cur-location.tpl'); ?>
						<div class="row-fluid">
                        <!-- PAGE CONTENT BEGINS HERE -->
                            <form class="form-horizontal" action="<?php echo HResponse::url($modelEnName . '/' . HResponse::getAttribute('nextAction') ); ?>" method="post" enctype="multipart/form-data" id="info-form">
                                <div class="tabbable tabs-right tabs-shadow tabs-space">
                                    <ul class="nav nav-tabs" id="myTab">
                                      <li class="active"><a data-toggle="tab" href="#base-box"><i class="pink icon-leaf bigger-110"></i> 基本信息</a></li>
                                      <li><a data-toggle="tab" href="#gen-files-box"><span
                                      class="badge badge-success badge-icon"><i class="icon-file"></i></span> 生成文件</a></li>
                                      <li><a data-toggle="tab" href="#manage-box"><span class="badge badge-success badge-icon"><i class="icon-cog"></i></span> 管理维护</a></li>
                                      <li><a data-toggle="tab" href="#create-table-box"><i class="danger icon-asterisk bigger-110"></i> 创建新表</a></li>
                                    </ul>
                                    <div class="tab-content">
                                      <div id="base-box" class="tab-pane in active">
                                        <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
                                        <input type="hidden" name="id" value="<?php echo $record['id'];?>" />
                                        <?php $field = 'sort_num'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                        <div class="control-group">
                                            <label class="control-label" for="table_name">选择数据表</label>
                                            <div class="controls">
                                                <select name="table_name" id="table-list"
                                                data-cur="<?php echo str_replace('#_', HObject::GCAttr('DATABASE', 'tablePrefix'), $modelTable); ?>" class="auto-select">
                                                    <option value="">--请选择数据表--</option>
                                                    <?php 
                                                        foreach(HResponse::getAttribute('tableList') as $table) { 
                                                            foreach($table as $tableName) {
                                                    ?>
                                                    <option value="<?php echo $tableName; ?>"><?php echo $tableName; ?></option>
                                                    <?php
                                                                break;
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                <a href="javascript:void(0);" id="reload-tables">刷新</a>
                                                <span class="help-inline">
                                                    当前表前缀为：<strong id="table-prefix"><?php echo $database['tablePrefix'];?></strong>。更新此项将删除原有所有模块相关文件！
                                                </span>
                                            </div>
                                        </div>
                                        <?php $field = 'name'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                        <?php $field = 'identifier'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
                                        <?php $field = 'description'; require(HResponse::path('admin') . '/fields/textarea.tpl'); ?>
                                        <div class="control-group">
                                            <label class="control-label" for="name">所属分类</label>
                                            <div class="controls">
                                                <select name="parent_id" id="parent-id" class="auto-select" data-cur="<?php echo $record['parent_id']; ?>">
                                                    <option value="-1">无</option>
                                                    <option value="0">自己</option>
                                                <?php 
                                                    HClass::import('hongjuzi.utils.HTree');
                                                    $hTree  = new HTree(
                                                        HResponse::getAttribute('parent_id_list'),
                                                        'id',
                                                        'parent_id',
                                                        'name',
                                                        'id',
                                                        '<option value="{id}">' .
                                                        '{name}' .
                                                        '</option>'
                                                    );
                                                    echo $hTree->getTree();
                                                ?>
                                                </select>
                                                <span class="help-inline">只能选择“自己”或者“下拉”中的项目。</span>
                                            </div>
                                        </div>
                                        <?php $field = 'type'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
                                        <?php $field = 'image_path'; require(HResponse::path('admin') . '/fields/file.tpl'); ?>
                                        <?php $field = 'has_multi_lang'; require(HResponse::path('admin') . '/fields/checkbox.tpl'); ?>
                                      </div>
                                      <div id="gen-files-box" class="tab-pane">
                                        <h3 class="header smaller lighter blue">
                                            <label class="fright">
                                                <input type="checkbox" value='1' def="true" checked="checked" class="select-all"><span class="lbl"> 全选</span>
                                            </label>
                                            配置、模块、管理模板文件列表
                                        </h3>
                                        <div class="row-fluid">
                                            <div class="span3">
                                                <label>
                                                    <input name="popo" type="checkbox" value='1' def="true" checked="checked"><span class="lbl"> POPO配置文件</span>
                                                </label>
                                            </div>
                                            <div class="span3">
                                                <label>
                                                    <input name="model" type="checkbox" value='1' /><span class="lbl"> Model文件</span>
                                                </label>
                                            </div>
                                        </div>
                                        <h3 class="header smaller lighter blue">
                                            <label class="fright">
                                                <input type="checkbox" value='1' class="select-all"><span class="lbl"> 全选</span>
                                            </label>
                                            应用控制层文件列表
                                        </h3>
                                        <div class="row-fluid">
                                            <?php 
                                                foreach(HResponse::getAttribute('apps') as $key => $app) { 
                                                    $app    = HDir::getDirName($app);
                                                    echo $key !== 0 && $key % 3 === 0 ? '</div><div class="row-fluid">' : '';
                                            ?>
                                            <div class="span4">
                                                <label>
                                                    <input name="action_files[]" type="checkbox" value='<?php echo $app; ?>'><span class="lbl"> <?php echo $app; ?>应用控制层文件</span>
                                                </label>
                                            </div>
                                            <?php }?>
                                        </div>
                                        <h3 class="header smaller lighter blue">
                                            <label class="fright">
                                                <input type="checkbox" value='1' class="select-all"><span class="lbl"> 全选</span>
                                            </label>
                                            生成应用模板列表
                                        </h3>
                                        <div class="row-fluid">
                                            <?php 
                                                foreach(HResponse::getAttribute('apps') as $key => $app) { 
                                                    $app    = HDir::getDirName($app);
                                                    echo $key !== 0 && $key % 3 === 0 ? '</div><div class="row-fluid">' : '';
                                            ?>
                                            <div class="span4">
                                                <label>
                                                    <input name="tpl_files[]" type="checkbox" value='<?php echo $app; ?>'><span class="lbl"> <?php echo $app; ?>应用模板文件</span>
                                                </label>
                                            </div>
                                            <?php }?>
                                        </div>
                                      </div>
                                      <div id="manage-box" class="tab-pane">
                                        <?php $field = 'create_time'; require(HResponse::path('admin') . '/fields/datetime.tpl'); ?>
                                        <?php require_once(HResponse::path('admin') . '/fields/author.tpl'); ?>
                                      </div>
                                      <div id="create-table-box" class="tab-pane">
                                      </div>
                                    </div>
                                  </div>
                                <?php require_once(HResponse::path('admin') .  '/common/info-form-buttons.tpl'); ?>
                             </form>
                        <!-- PAGE CONTENT ENDS HERE -->
						 </div><!--/row-->
					</div><!--/#page-content-->
                <?php require_once(HResponse::path('admin') . '/common/setting-bar.tpl'); ?>  
			</div><!-- #main-content -->
		</div><!--/.fluid-container#main-container-->
        <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/info.js"></script>
        <script type='text/javascript' src="<?php echo HResponse::uri('wizard'); ?>/js/modelmanager-info.js"></script> <!-- the "addmake them work" script -->
	</body>
</html>
