
                                                <?php 
                                                    $activity       = HResponse::getAttribute('activity');
                                                    $activityList   = HResponse::getAttribute('activityList');
                                                ?>
                                                <div class="control-group">
                                                    <label class="control-label" for="">使用优惠：</label>
                                                    <table class="table table-hover">
                                                      <thead>
                                                        <tr>
                                                          <th>#</th>
                                                          <th>类别</th>
                                                          <th>满</th>
                                                          <th>减</th>
                                                          <th>操作</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody>
                                                        <?php if($activity) { ?>
                                                        <tr>
                                                          <td>1</td>
                                                          <td><?php echo OrderactivityPopo::$typeMap[$activity['type']]['name'];?></td>
                                                          <td><?php echo $activity['min_money'];?></td>
                                                          <td><?php echo $activity['sub_money'];?></td>
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
                                                              <p class="text-center">无使用优惠！</p>
                                                          </td>
                                                        </tr>
                                                        <?php } ?>
                                                      </tbody>
                                                    </table>
                                                </div>
                                                <hr class="clearfix">
                                                <div class="control-group">
                                                    <label class="control-label" for="">可用优惠（单选）：</label>
                                                    <table class="table table-hover">
                                                      <thead>
                                                        <tr>
                                                          <th>#</th>
                                                          <th>类别</th>
                                                          <th>满</th>
                                                          <th>减</th>
                                                          <th>操作</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody>
                                                        <?php if($activityList) { ?>
                                                        <?php foreach($activityList as $key => $item) { ?>
                                                        <tr>
                                                          <td><?php echo $key + 1;?></td>
                                                          <td><?php echo ShopactivityPopo::$typeMap[$item['type']]['name'];?></td>
                                                          <td><?php echo $item['min_money'];?></td>
                                                          <td><?php echo $item['sub_money'];?></td>
                                                          <td>
                                                            <a href="javascript:void(0);" class="btn-use-activity" data-id="<?php echo $item['id'];?>">
                                                                <i class="icon icon-arrow-right"></i>
                                                                使用
                                                            </a>
                                                          </td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                        <tr>
                                                          <td colspan="5">
                                                              <p class="text-center">无可用优惠！</p>
                                                          </td>
                                                        </tr>
                                                        <?php } ?>
                                                      </tbody>
                                                    </table>
                                                </div>