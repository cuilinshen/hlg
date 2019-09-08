<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
	</head>
	<body>
		<div class="container-fluid" id="main-container">
			<div class="clearfix">
                <?php 
                    $record         = HResponse::getAttribute('record');
                    $orderProductList= HResponse::getAttribute('orderProductList');
                    $companyMap     = HResponse::getAttribute('companyMap');
                    $orderAddress   = HResponse::getAttribute('orderAddress');
                    $orderYunFei    = HResponse::getAttribute('orderYunFei');
                ?>   
                <div id="page-content" class="clearfix">
                    <div class="page-header position-relative">
                        <h1 class="text-center">
                            <a href="javascript:void(0);" style="display:none;" class="btn btn-mini  btn-info" id="btn-print">
                                <i class="icon icon-print"></i> 开始打印
                            </a>
                            <small class="fs-14 pull-right">打印时间：<?php echo date('Y-m-d H:i:s');?></small>
                            <span class="pull-left">
                            <?php
                                echo '订单详情 - ' .$record['name'];
                            ?>
                            </span>
                        </h1>
                        <div class="clearfix"></div>
                    </div><!--/page-header-->             
                    <div class="row-fluid">
                    <!-- PAGE CONTENT BEGINS HERE -->
                        <div class="span12 content-box">
                            <!-- PAGE CONTENT BEGINS HERE -->
                            <div class="row-fluid">
                                <table width="100%" cellpadding="3" cellspacing="1" class="table table-hover table-bordered">
                                      <tbody>
                                      <tr>
                                        <th colspan="4" class=" text-left">
                                            <i class="icon icon-info-sign"></i> 基本信息
                                        </th>
                                      </tr>
                                      <tr>
                                        <td width="18%"><div align="right"><strong>订单号：</strong></div></td>
                                        <td width="34%"><?php echo $record['code'];?></td>
                                        <td width="15%"><div align="right"><strong>订单状态：</strong></div></td>
                                        <td>
                                            <?php echo OrderPopo::$_statusMap[$record['status']]['name'];?>，
                                        </td>
                                      </tr>
                                      <tr>
                                        <td><div align="right"><strong>购货人：</strong></div></td>
                                        <td><?php echo $record['name'];?> </td>
                                        <td><div align="right"><strong>下单时间：</strong></div></td>
                                        <td><?php echo $record['create_time'];?></td>
                                      </tr>
                                      <tr>
                                        <td><div align="right"><strong>支付方式：</strong></div></td>
                                        <td>微信</td>
                                        <td><div align="right"><strong>付款时间：</strong></div></td>
                                        <td><?php echo !$record['pay_time'] ? '未付款' : date('Y-m-d H:i:s', $record['pay_time']);?></td>
                                      </tr>
                                      <tr>
                                        <td><div align="right"><strong>预计到达时间：</strong></div></td>
                                        <td><?php echo $record['daoda_time']; ?></td>
                                        <td><div align="right"><strong>订单来源：</strong></div></td>
                                        <td> 小程序</td>
                                      </tr>
                                      <tr>
                                        <td><div align="right"><strong>是否客户确认：</strong></div></td>
                                        <td><?php echo  1 == $record['user_sure'] ? '否' : '是';?></td>
                                        <td><div align="right"><strong>是否评价：</strong></div></td>
                                        <td><?php echo 1 == $record['is_comment'] ? '否' : '是';?></td>
                                      </tr>
                                      <tr>
                                        <td><div align="right"><strong>是否合并：</strong></div></td>
                                        <td><?php echo 1 == $record['is_merge'] ? '否' : '是';?></td>
                                        <td><div align="right"><strong>完成时间：</strong></div></td>
                                        <td><?php echo !$record['done_time'] ? '无' : date('Y-m-d H:i:s', $record['done_time']);?></td>
                                      </tr>
                                      <tr>
                                        <td><div align="right"><strong>客户的留言：</strong></div></td>
                                        <td colspan="3"><?php echo $record['remark'];?></td>
                                      </tr>
                                      <tr>
                                        <th colspan="4">
                                            <i class="icon icon-user"></i> 收货人信息
                                            <?php $address  = json_decode($record['address'], true); ?>
                                        </th>
                                        </tr>
                                      <tr>
                                        <td><div align="right"><strong>收货人：</strong></div></td>
                                        <td><?php echo $orderAddress['name'];?></td>
                                        <td><div align="right"><strong>手机：</strong></div></td>
                                        <td><?php echo $orderAddress['phone'];?></td>
                                      </tr>
                                      <tr>
                                        <td><div align="right"><strong>地址：</strong></div></td>
                                        <td><?php echo $orderAddress['address'];?></td>
                                        <td><div align="right"><strong>邮编：</strong></div></td>
                                        <td><?php echo $orderAddress['menpai'];?></td>
                                      </tr>
                                    </tbody>
                                </table>
                                <hr class="clearfix">
                                <!--
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody><tr>
                                    <th colspan="6" class="bg-grey text-left">
                                        <?php $wuliu     = HResponse::getAttribute('wuliu'); ?>
                                        <i class="icon icon-truck"></i>
                                        物流信息
                                    </th>
                                  </tr>
                                  <tr>
                                    <td width="18%"><div align="right"><strong>物流公司：</strong></div></td>
                                    <td width="14%"><?php echo $wuliu['name'];?></td>
                                    <td width="15%"><div align="right"><strong>物流单号：</strong></div></td>
                                    <td width="15%"><?php echo $wuliu['code'];?></td>
                                    <td width="15%"><div align="right"><strong>物流进度：</strong></div></td>
                                    <td ><?php echo $wuliu['content'];?></td>
                                  </tr>
                                </tbody></table>
                                <hr class="clearfix">
                                -->
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody>
                                    <tr>
                                        <th colspan="6" scope="col" class="bg-grey text-left">
                                            <i class="icon icon-list"></i>
                                            商品信息
                                            
                                        </th>
                                    </tr>
                                  <tr>
                                    <td scope="col"><div align="center"><strong>序号</strong></div></td>
                                    <td scope="col"><div align="center"><strong>商品名称</strong></div></td>
                                    <td scope="col"><div align="center"><strong>价格</strong></div></td>
                                    <td scope="col"><div align="center"><strong>数量</strong></div></td>
                                    <td scope="col"><div align="center"><strong>小计</strong></div></td>
                                  </tr>
                                    <?php 
                                        $goodsList  = HResponse::getAttribute('goodsList');
                                        $sumGoods   = 0;
                                        foreach($orderProductList as $key => $item) { 
                                            $total      = $item['number'] * $item['price']; 
                                            $sumGoods   += $total;
                                            $companyInfo = $companyMap[$item['company_id']];
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $key + 1;?></td>
                                        <td class="text-center">
                                            <?php echo $item['name'] ;?>
                                        </td>
                                        <td class="text-center"><?php echo $item['price']; ?></td>
                                        <td class="text-center"><?php echo $item['number']; ?></td>
                                        <td><div align="right">￥<span class="item-price"><?php echo $item['number'] * $item['price'];?></span>元</div></td>
                                    </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
                                <?php 
                                    $zenpingList    = HResponse::getAttribute('zenpingList');
                                    if($zenpingList) { 
                                ?>
                                <hr class="clearfix">
                                <!--
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody><tr>
                                    <th colspan="7" class="bg-grey text-left">
                                        <i class="icon icon-tag"></i>
                                        赠品信息
                                    </th>
                                  </tr>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong>序号</strong></div></td>
                                    <td scope="col" width="15%"><div align="center"><strong>名称</strong></div></td>
                                    <td scope="col" width="15%"><div align="center"><strong>内容</strong></div></td>
                                    <td scope="col" width="10%"><div align="center"><strong>开始时间</strong></div></td>
                                    <td scope="col" width="10%"><div align="center"><strong>结束时间</strong></div></td>
                                    <td scope="col" width="10%"><div align="center"><strong>图片</strong></div></td>
                                    <td scope="col" width="10%"><div align="center" ><strong>原价</strong></div></td>
                                  </tr>
                                  <?php 
                                    foreach($zenpingList as $key => $item) { 
                                  ?>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong><?php echo $key + 1; ?></strong></div></td>
                                    <td scope="col" width="15%"><div align="center"><strong><?php echo $item['name'];?></strong></div></td>
                                    <td scope="col" width="15%"><div align="center"><strong><?php echo $item['content'];?></strong></div></td>
                                    <td scope="col" width="10%"><div align="center"><strong><?php echo date('Y-m-d H:i:s', $item['start_time']);?></strong></div></td>
                                    <td scope="col" width="10%"><div align="center"><strong><?php echo date('Y-m-d H:i:s', $item['end_time']);?></strong></div></td>
                                    <td scope="col" width="10%"><div align="center">
                                        <?php if($item['image_path']) { ?>
                                        <img src="<?php echo HResponse::touri($item['image_path']);?>" alt="" class="pic">
                                        <?php } ?>
                                    </div></td>
                                    <td scope="col" width="10%"><div align="center" ><strong><?php echo $item['price'];?></strong></div></td>
                                  </tr>
                                  <?php } ?>
                                </tbody></table>
                                <?php } ?>
                                <?php 
                                    $redpackage   = HResponse::getAttribute('redpackage');
                                    if($redpackage) { 
                                ?>
                                <hr class="clearfix">
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody><tr>
                                    <th colspan="4" class="bg-grey text-left">
                                        <i class="icon icon-gift"></i>
                                        红包信息
                                    </th>
                                  </tr>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong>序号</strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong>红包金额</strong></div></td>
                                    <td scope="col" width="35%"><div align="center"><strong>过期时间</strong></div></td>
                                    <td scope="col" width="35%"><div align="center"><strong>说明</strong></div></td>
                                  </tr>
                                  <?php if($redpackage) { ?>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong>1</strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong><?php echo $redpackage['money'];?></strong></div></td>
                                    <td scope="col" width="35%"><div align="center"><strong><?php echo date('Y-m-d H:i:s', $redpackage['end_time']);?></strong></div></td>
                                    <td scope="col" width="35%"><div align="center"><strong><?php echo $redpackage['description'];?></strong></div></td>
                                  </tr>
                                  <?php } ?>
                                </tbody></table>
                                  <?php } ?>
                                <?php 
                                    $activity     = HResponse::getAttribute('activity');
                                    if($activity) { 
                                ?>
                                <hr class="clearfix">
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody><tr>
                                    <th colspan="4" class="bg-grey text-left">
                                        <i class="icon icon-bookmark"></i>
                                        优惠信息
                                    </th>
                                  </tr>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong>序号</strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong>类别</strong></div></td>
                                    <td scope="col" width="35%"><div align="center"><strong>满</strong></div></td>
                                    <td scope="col" width="30%"><div align="center"><strong>减</strong></div></td>
                                  </tr>
                                  <?php if($activity) { ?>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong>1</strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong><?php echo OrderActivityPopo::$typeMap[$activity['type']]['name'];?></strong></div></td>
                                    <td scope="col" width="35%"><div align="center"><strong><?php echo $activity['min_money'];?></strong></div></td>
                                    <td scope="col" width="30%"><div align="center"><strong><?php echo $activity['sub_money'];?></strong></div></td>
                                  </tr>
                                  <?php } ?>
                                </tbody></table>
                                  <?php } ?>
                                <hr class="clearfix">
                                -->
                                <?php 
                                    $sumActivity    = floatval($activity['sub_money']);
                                    $sumRedpackage  = floatval($redpackage['money']);
                                    $sum            = $sumGoods + $record['yun_fee'] + $sumGoods * $rate;
                                    $rate           = HResponse::getAttribute('rate');
                                ?>
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody><tr>
                                    <th class="bg-grey text-left">
                                        <i class="icon icon-yen"></i>
                                        费用信息
                                    </th>
                                  </tr>
                                  <tr>
                                    <td><div align="right">商品总金额：<strong>￥<?php echo $sumGoods; ?>元</strong>
                                      + 配送费用：<strong>￥<?php echo $orderYunFei['money']; ?>元</strong>
                                      = 订单总金额：<strong>￥<?php echo $record['total_money'];?>元</strong>
                                      </div></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right">
                                      - 使用优惠： <strong>￥<?php echo $sumActivity;?>元</strong>
                                      - 使用红包： <strong>￥<?php echo $sumRedpackage;?>元</strong>
                                    </div></td>
                                  </tr><tr>
                                    <td class="text-right">
                                     = 应付款金额：<strong>￥<?php echo $record['total_money'];?>元</strong>
                                     <?php 
                                      $sum   = $sum - $sumActivity - $sumRedpackage; 
                                      $sum   = 0 > $sum ? 0 : $sum;
                                     ?>
                                  </tr>
                                </tbody></table>
                            </div>                                    
                            <!-- PAGE CONTENT ENDS HERE -->

                            </div>
                         </div><!--/row-->
                      </div>
                    <!-- PAGE CONTENT ENDS HERE -->
                     </div><!--/row-->
                </div><!--/#page-content-->
			</div><!-- #main-content -->
		</div><!--/.fluid-container#main-container-->
        <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
        <script type="text/javascript">
            $(function() {
                setTimeout(function() {
                    window.print();
                    $('#btn-print').show();
                }, 1000);
                $('#btn-print').click(function() { 
                    $(this).hide();
                    setTimeout(function() {
                        window.print();
                        $('#btn-print').show();
                    }, 500);
                });
            });
        </script>
	</body>
</html>
