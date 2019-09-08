                                <div class="row-fluid">
                                    <form id="search-form" action="<?php echo HResponse::url('' . $modelEnName . '/search'); ?>" method="get">
                                        <div class="span4">
                                            <div id="table_report_length" class="dataTables_length">
                                                    <label>每页显示:
                                                        <select size="1" name="perpage" id="perpage" aria-controls="table_report" data-cur="<?php echo HRequest::getParameter('perpage'); ?>">
                                                            <option value="10" selected="selected">10</option>
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                        </select>
                                                        条
                                                </label>
                                            </div>
                                        </div>
                                        <div class="span8 txt-right f-right">
                                            <?php if(2 == $modelCfg['has_multi_lang']) { ?>
                                            <label>语言分类: 
                                                <select name="lang_id" id="lang-id" class="auto-select input-medium" data-cur="<?php echo HRequest::getParameter('lang_id'); ?>">
                                                    <option value="">全部</option>
                                                    <?php foreach($langMap as $lang) { ?>
                                                    <option value="<?php echo $lang['id'];?>"><?php echo $lang['name']; ?></option>
                                                    <?php }?>
                                                </select>
                                            </label>
                                            <?php } ?>
                                            <label>统计时间段: 
                                                <select name="date" id="date" class="input-medium" data-cur="<?php echo HRequest::getParameter('date'); ?>">
                                                    <option value="1">日</option>
                                                    <option value="2">周</option>
                                                    <option value="3">月</option>
                                                    <option value="4">年</option>
                                                </select>
                                            </label>
                                            <label>供货商名称: 
                                                <select name="date" id="date" class="input-medium" data-cur="<?php echo HRequest::getParameter('date'); ?>">
                                                    <?php 
                                                        $shopList = HResponse::getAttribute('shopList'); 
                                                        foreach($shopList as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </label>
                                            <label>搜索<?php echo $modelZhName;?>: 
                                                <input type="text" class="input-medium search-query" name="keywords" id="keywords" data-def="<?php echo !HRequest::getParameter('keywords') ? '关键字...' : HRequest::getParameter('keywords'); ?>">
                                                <button type="submit" class="btn btn-purple btn-small">搜索<i class="icon-search icon-on-right"></i></button>
                                            </label>
                                        </div>
                                    </form>
                                </div>
