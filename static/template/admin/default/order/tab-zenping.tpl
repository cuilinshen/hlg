                <?php 
                    $usedZenpingList    = HResponse::getAttribute('usedZenpingList'); 
                    $zenpingList        = HResponse::getAttribute('zenpingList'); 
                ?>
                <div class="control-group">
                    <label class="control-label" for="">赠品列表：</label>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>名称</th>
                                <th>说明</th>
                                <th>图片</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($usedZenpingList) { ?>
                            <?php foreach($usedZenpingList as $key => $item) { ?>
                            <tr>
                                <td><?php echo $key + 1;?></td>
                                <td><?php echo $item['name'];?></td>
                                <td><?php echo $item['description'];?></td>
                                <td>
                                    <?php if($item['image_path']) { ?>
                                    <a href="<?php echo HResponse::touri($item['image_path']); ?>" class=" lightbox">
                                        <img class="pic" src="<?php echo HResponse::touri($item['image_path']); ?>" alt="">
                                    </a>
                                    <?php } else { ?>
                                    无图片
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="###">
                                        <i class="icon icon-remove"></i>
                                        移除
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td colspan="5">
                                    <p class="text-center">无赠品！</p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <hr class="clearfix">
                <div class="control-group">
                        <label class="control-label" for="">可用赠品（多选）：</label>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>名称</th>
                                    <th>说明</th>
                                    <th>图片</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($zenpingList) { ?>
                                <?php foreach($zenpingList as $key => $item) { ?>
                                <tr>
                                    <td><?php echo $key + 1;?></td>
                                    <td><?php echo $item['name'];?></td>
                                    <td><?php echo $item['description'];?></td>
                                    <td>
                                        <?php if($item['image_path']) { ?>
                                        <a href="<?php echo HResponse::touri($item['image_path']); ?>" class=" lightbox">
                                            <img class="pic" src="<?php echo HResponse::touri($item['image_path']); ?>" alt="">
                                        </a>
                                        <?php } else { ?>
                                        无图片
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="###">
                                            <i class="icon icon-arrow-right"></i>
                                            使用
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td colspan="4">
                                            <p class="text-center">无赠品！</p>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>