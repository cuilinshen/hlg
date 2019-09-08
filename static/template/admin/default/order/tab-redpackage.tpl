                <?php 
                    $redpackage     = HResponse::getAttribute('redpackage'); 
                    $redpackageList = HResponse::getAttribute('redpackageList'); 
                ?>
                <div class="control-group">
                    <label class="control-label" for="">使用红包：</label>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>优惠码</th>
                                <th>红包金额</th>
                                <th>截止时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($redpackage) { ?>
                            <tr>
                                <td>1</td>
                                <td><?php echo $redpackage['name'];?></td>
                                <td><?php echo $redpackage['money'];?></td>
                                <td><?php echo date('Y-m-d H:i:s', $redpackage['end_time']);?></td>
                                <td>
                                    <a href="###">
                                        <i class="icon icon-remove"></i>
                                        移除
                                    </a>
                                </td>
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <td colspan="5">
                                    <p class="text-center">无红包！</p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <hr class="clearfix">
                <div class="control-group">
                        <label class="control-label" for="">可用红包（单选）：</label>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>优惠码</th>
                                    <th>红包金额</th>
                                    <th>截止时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($redpackageList) { ?>
                                <?php foreach($redpackageList as $key => $item) { ?>
                                <tr>
                                    <td><?php echo $key + 1;?></td>
                                    <td><?php echo $item['name'];?></td>
                                    <td><?php echo $item['money'];?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $item['end_time']);?></td>
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
                                    <td colspan="5">
                                        <p class="text-center">无可用红包！</p>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>