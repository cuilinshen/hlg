                <?php 
                    $goodsList  = HResponse::getAttribute('goodsList'); 
                ?>
                <div class="control-group">
                    <label class="control-label" for="">商品列表：</label>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>名称</th>
                                <th>单价</th>
                                <th>数量</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($goodsList) { ?>
                            <?php foreach($goodsList as $key => $item) { ?>
                            <tr>
                                <td><?php echo $key + 1;?></td>
                                <td><?php echo $item['name'];?></td>
                                <td><?php echo $item['price'];?></td>
                                <td><?php echo $item['number'];?></td>
                                <td>
                                    <a href="<?php echo HResponse::url('goods', 'id=' . $item['goods_id'], HObject::GC('DEF_APP'));?>" target="_blank">
                                        <i class="icon icon-arrow-right"></i>
                                        前台查看
                                    </a>
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
                                    <p class="text-center">无商品！</p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>