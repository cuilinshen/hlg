<?php require_once(HResponse::path('admin') . '/common/header.tpl'); ?>
    </head>
    <body>
        <?php require_once(HResponse::path('admin') . '/common/navmenu.tpl'); ?>
        <div class="container-fluid" id="main-container">
            <?php require_once(HResponse::path('admin') . '/common/sidebar.tpl'); ?>
            <div id="main-content" class="clearfix">
                <?php 
                    $record         = HResponse::getAttribute('record');
                    $orderProductList= HResponse::getAttribute('orderProductList');
                    $companyMap     = HResponse::getAttribute('companyMap');
                    $orderAddress   = HResponse::getAttribute('orderAddress');
                    $orderYunFei    = HResponse::getAttribute('orderYunFei');
                ?>   
                <div id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home"></i> <a href="<?php echo HResponse::url('', '', 'admin'); ?>">后台桌面</a>
                            <span class="divider"><i class="icon-angle-right"></i></span>
                        </li>
                        <li><a href="<?php echo HResponse::url($modelEnName); ?>"><?php echo $modelZhName; ?></a> <span class="divider"><i class="icon-angle-right"></i></span></li>
                        <li class="active"><?php echo $modelZhName; ?><?php HTranslate::_('内容'); ?></li>
                    </ul><!--.breadcrumb-->
                    <div id="nav-search">
                        <span id="time-info">正在加载时钟...</span>
                    </div><!-- #nav-search -->
                </div><!-- #breadcrumbs -->
                <div id="page-content" class="clearfix">
                    <div class="page-header position-relative">
                        <h1>
                            <?php
                                echo '订单详情 - ';
                                echo isset($record['name']) ? $record['name'] . ' 【支付过期时间：' . date('Y-m-d H:i:s', $record['end_time']) . '】': 'ID：' . $record['id'];
                                $preRecord    = HResponse::getAttribute('preRecord');
                                $nextRecord   = HResponse::getAttribute('nextRecord');
                            ?>
                        </h1>
                    </div><!--/page-header-->             
                    <div class="row-fluid">
                    <!-- PAGE CONTENT BEGINS HERE -->
                        <div class="span9 content-box">
                            <?php $field = 'id'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
                            <!-- PAGE CONTENT BEGINS HERE -->
                            <div class="row-fluid">
                                <table width="100%" cellpadding="3" cellspacing="1" class="table table-hover table-bordered">
                                      <tbody>
                                      <tr>
                                        <td colspan="4" class="bg-grey">
                                          <div class="text-center">
                                           <?php if(!$preRecord) { ?>
                                            <a href="javascript:void(0);" class="disabled btn-next-order btn btn-mini btn-grey">
                                                <i class="icon icon-arrow-left"></i> 上一个订单</a>
                                            <?php } else { ?>
                                            <a href="<?php echo HResponse::url('order/editview', 'id=' . $preRecord['id']);?>" class="btn-next-order btn btn-mini btn-info">
                                                <i class="icon icon-arrow-left"></i> 上一个订单</a>
                                            <?php } ?>
                                           <a target="_blank" class="btn-next-order btn btn-mini btn-warning" href="<?php echo HResponse::url($modelEnName .  '/printview', 'id=' . $record['id']); ?>">
                                            <i class="icon icon-print"></i> 打印订单
                                           </a>
                                           <?php if(!$nextRecord) { ?>
                                            <a href="javascript:void(0);" class="disabled btn-pre-order btn btn-mini btn-grey mr-5">
                                                <i class="icon icon-arrow-right"></i> 下一个订单</a>
                                           <?php } else { ?>
                                            <a href="<?php echo HResponse::url('order/editview', 'id=' . $nextRecord['id']);?>" class="btn-pre-order btn btn-mini btn-info mr-5">
                                                <i class="icon icon-arrow-right"></i> 下一个订单</a>
                                           <?php } ?>
                                          </div>
                                        </td>
                                      </tr>
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
                                        <td>微信支付</td>
                                        <td><div align="right"><strong>付款时间：</strong></div></td>
                                        <td><?php echo !$record['pay_time'] ? '未付款' : date('Y-m-d H:i:s', $record['pay_time']);?></td>
                                      </tr>
                                      <tr>
                                        <td><div align="right"><strong>预计到达时间：</strong></div></td>
                                        <td><?php echo $record['daoda_time'];?></td>
                                        <td><div align="right"><strong>订单来源：</strong></div></td>
                                        <td>小程序</td>
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
                                        <th colspan="4" class=" text-left">
                                            <i class="icon icon-user"></i> 收货人信息
                                            <?php if(1 == $record['status']){ ?>
                                            <a href="###" id="edit-order-address" class="pull-right special">编辑</a>
                                            <?php } ?>
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
                                        <td><div align="right"><strong>门牌号：</strong></div></td>
                                        <td><?php echo $orderAddress['menpai'];?></td>
                                      </tr>
                                    </tbody>
                                </table>
                                <hr class="clearfix">
                                <!--
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody><tr>
                                    <th colspan="4" class="bg-grey text-left">
                                        <?php $wuliu     = HResponse::getAttribute('wuliu'); ?>
                                        <i class="icon icon-truck"></i>
                                        物流信息
                                      <?php if($record['status'] == 10 && ($record['process'] == 1 || $record['process'] == 4)) { ?>
                                        <a href="###" id="edit-order-wuliu" class="pull-right special">编辑</a>
                                      <?php } ?>
                                    </th>
                                  </tr>
                                  <tr>
                                    <td width="18%"><div align="right"><strong>物流公司：</strong></div></td>
                                    <td width="34%"><?php echo $wuliu['name'];?></td>
                                    <td width="15%"><div align="right"><strong>物流单号：</strong></div></td>
                                    <td><?php echo $wuliu['code'];?></td>
                                  </tr>
                                  <tr>
                                    <td><div align="right"><strong>物流进度：</strong></div></td>
                                    <td colspan="3"><?php echo $wuliu['content'];?></td>
                                  </tr>
                                </tbody></table>
                                <hr class="clearfix">
                                -->
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody>
                                    <tr>
                                        <th colspan="7" scope="col" class="bg-grey text-left">
                                            <i class="icon icon-list"></i>
                                            商品信息
                                      <?php if(1 == $record['status']) { ?>
                                            <a href="###" id="edit-order-goods" class="pull-right special">编辑</a>
                                      <?php } ?>
                                        </th>
                                    </tr>
                                  <tr>
                                    <td scope="col"><div align="center"><strong>序号</strong></div></td>
                                    <td scope="col"><div align="center"><strong>商品名称</strong></div></td>
                                    <td scope="col"><div align="center"><strong>价格</strong></div></td>
                                    <td scope="col"><div align="center"><strong>数量</strong></div></td>
                                    <td scope="col"><div align="center"><strong>供货商</strong></div></td>
                                    <td scope="col"><div align="center"><strong>小计</strong></div></td>
                                  </tr>
                                    <?php 
                                        $goodsList  = HResponse::getAttribute('goodsList');
                                        $sumGoods   = 0;
                                        foreach($orderProductList as $key => $item) { 
                                            $goods      = $goodsMap[$item['goods_id']];
                                            $total      = $item['number'] * $item['price']; 
                                            $sumGoods   += $total;
                                            $companyInfo = $companyMap[$item['company_id']];
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $key + 1;?></td>
                                        <td class="text-center">
                                            <a target="_blank" href="<?php echo HResponse::url('product', 'id=' . $item['product_id'], HObject::GC('DEF_APP'));?>">
                                                <?php echo $item['name'] ;?>
                                            </a>
                                        </td>
                                        <td class="text-center"><?php echo $item['price']; ?></td>
                                        <td class="text-center"><?php echo $item['number']; ?></td>
                                        <td class="text-center"><?php echo $companyInfo['name']; ?></td>
                                        <td><div align="right">￥<span class="item-price"><?php echo $item['number'] * $item['price'];?></span>元</div></td>
                                    </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
                                <hr class="clearfix">
                                <!--
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody><tr>
                                    <th colspan="5" class="bg-grey text-left">
                                        <i class="icon icon-tag"></i>
                                        赠品信息
                                      <?php if(1 == $record['status']) { ?>
                                      <a href="###" id="btn-add-order-zengpin" class="pull-right special">添加</a>
                                      <?php } ?>
                                    </th>
                                  </tr>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong>序号</strong></div></td>
                                    <td scope="col" width="10%"><div align="center" ><strong>原价</strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong>赠送内容</strong></div></td>
                                    <td scope="col" width="20%"><div align="center"><strong>图片</strong></div></td>
                                    <td scope="col" width="10%"><div align="center" ><strong>操作</strong></div></td>
                                  </tr>
                                  <?php 
                                    $zenpingList    = HResponse::getAttribute('zenpingList');
                                    foreach($zenpingList as $key => $item) { 
                                  ?>
                                  <tr>
                                    <td scope="col"><div align="center"><strong><?php echo $key + 1; ?></strong></div></td>
                                    <td scope="col"><div align="center" ><strong><?php echo $item['price'];?></strong></div></td>
                                    <td scope="col" ><div align="center"><strong><?php echo $item['content'];?></strong></div></td>
                                    <td scope="col"><div align="center">
                                        <?php if($item['image_path']) { ?>
                                        <a href="<?php echo HResponse::touri($item['image_path']);?>" class="lightbox" target="_blank">
                                            <img src="<?php echo HResponse::touri($item['image_path']);?>" alt="" class="pic">
                                        </a>
                                        <?php } ?>
                                    </div></td>
                                    <td scope="col"><div align="center" >
                                      <?php if(1 == $record['status']) { ?>
                                      <a class="btn-del" href="<?php echo HResponse::url('orderzenping/delete', 'id=' . $item['id']);?>"><strong>删除</strong></a>
                                      <?php } else { ?>
                                      不能操作
                                      <?php } ?>
                                    </div></td>
                                  </tr>
                                  <?php } ?>
                                </tbody></table>
                                <hr class="clearfix">
                                <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                                  <tbody><tr>
                                    <th colspan="5" class="bg-grey text-left">
                                        <?php $activity     = HResponse::getAttribute('activity'); ?>
                                        <i class="icon icon-bookmark"></i>优惠信息
                                      <?php if(1 == $record['status']) { ?>
                                        <a href="###" id="btn-edit-activity" class="pull-right special">编辑</a>
                                      <?php } ?>
                                    </th>
                                  </tr>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong>序号</strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong>类别</strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong>满</strong></div></td>
                                    <td scope="col" width="20%"><div align="center"><strong>减</strong></div></td>
                                    <td scope="col" width="20%"><div align="center"><strong>操作</strong></div></td>
                                  </tr>
                                  <?php if($activity) { ?>
                                  <tr>
                                    <td scope="col" width="10%"><div align="center"><strong>1</strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong><?php echo OrderActivityPopo::$typeMap[$activity['type']]['name'];?></strong></div></td>
                                    <td scope="col" width="25%"><div align="center"><strong><?php echo $activity['min_money'];?></strong></div></td>
                                    <td scope="col" width="20%"><div align="center"><strong><?php echo $activity['sub_money'];?></strong></div></td>
                                    <td scope="col"><div align="center" >
                                      <?php if(1 == $record['status']) { ?>
                                      <a class="btn-del" href="<?php echo HResponse::url('orderactivity/delete', 'id=' . $activity['id']);?>"><strong>删除</strong></a>
                                      <?php } else { ?>
                                      不能操作
                                      <?php } ?>
                                    </div></td>
                                  </tr>
                                  <?php } ?>
                                </tbody></table>
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
                                      + 配送费用：<strong>￥<?php echo $orderYunFei['money']; ?>元</strong></div></td>
                                  </tr><tr>
                                    <td><div align="right"> = 订单总金额：<strong>￥<?php echo $sum;?>元</strong></div></td>
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
                            <div class="span3">
                                <form action="<?php echo HResponse::url($modelEnName . '/' . HResponse::getAttribute('nextAction') ); ?>" method="post" enctype="multipart/form-data" id="info-form">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4>发布</h4>
                                            <div class="widget-toolbar">
                                                <a href="#" data-action="collapse">
                                                    <i class="icon-chevron-up"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
    <?php $field = 'id'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'name'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'address'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'yun_fee'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'parent_id'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'fapiao_id'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'shop_sure'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'is_got_money'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'end_time'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php $field = 'create_time'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
                                                <?php $field = 'status'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
                                                <?php $field = 'pwd'; require(HResponse::path('admin') . '/fields/hidden.tpl'); ?>
    <?php require_once(HResponse::path('admin') . '/fields/author.tpl'); ?>
                                                <hr/>
                                                <div class="control-group text-right btn-form-box" data-spy="affix" data-offset-top="160" >
                                                    <?php if(in_array($record['status'], array(1, 11, 12, 13)) && $record['process'] == 1){ ?>
                                                    <a href="<?php echo HResponse::url('order/trash', 'id=' . $record['id']);?>" class="btn btn-mini btn-info">取消订单</a>
                                                    <?php } ?>
                                                    <?php if($record['status'] == 5 && $record['process'] == 1){ ?>
                                                    <a href="<?php echo HResponse::url('order/delete', 'id=' . $record['id']);?>" class="btn btn-mini btn-info">删除订单</a>
                                                    <?php } ?>
                                                    <?php if($record['status'] == 10 && $record['process'] == 1){ ?>
                                                    <a href="javascript:void()" class="btn-edit-order-wuliu btn btn-mini btn-info">去发货</a>
                                                    <a href="<?php echo HResponse::url('orderpayback/paybackapply', 'id=' . $record['id']);?>" class="btn btn-mini btn-info">申请退款</a>
                                                    <?php } ?>
                                                    <?php if($record['status'] == 10 && $record['process'] == 3){ ?>
                                                    <a href="<?php echo HResponse::url('orderpayback/paybackapply', 'id=' . $record['id']);?>" class="btn btn-mini btn-info">申请退款</a>
                                                    <a href="<?php echo HResponse::url('order/noreceived', 'id=' . $record['id']);?>" class="btn btn-mini btn-info">未收货</a>
                                                    <?php } ?>
                                                    <?php if($record['status'] == 10 && $record['process'] == 4){ ?>
                                                    <a href="<?php echo HResponse::url('orderpayback/paybackapply', 'id=' . $record['id']);?>" class="btn btn-mini btn-info">申请退款</a>
                                                    <a href="javascript:void()" class="btn-edit-order-wuliu btn btn-mini btn-info">添加补发</a>
                                                    <?php } ?>
                                                    <?php if($record['status'] == 7 && ($record['process'] == 1 || $record['process'] == 3 || $record['process'] == 4)){ ?>
                                                    <a href="<?php echo HResponse::url('orderpayback/paybackdeal', 'id=' . $record['id']);?>" class="btn btn-mini btn-info">处理退款</a>
                                                    <?php } ?>
                                                    <?php if($record['status'] == 8 && ($record['process'] == 1 || $record['process'] == 3)){ ?>
                                                    <a href="<?php echo HResponse::url('orderpayback/paybackdeal', 'id=' . $record['id']);?>" class="btn btn-mini btn-info">查看退款</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                         </div><!--/row-->
                      </div>
                    <!-- PAGE CONTENT ENDS HERE -->
                     </div><!--/row-->
                </div><!--/#page-content-->
            </div><!-- #main-content -->
        </div><!--/.fluid-container#main-container-->
        <!-- Modal -->
        <div class="modal modal-lg fade" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="order-modal-title"></h4>
                    </div>
                    <div class="modal-body" id="order-modal-content"> </div>
                    <div class="modal-footer">
                        <button id="btn-modal-submit" type="button" class="btn btn-primary btn-edit btn-sm">保存并刷新</button>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript"> 
            var oid = '<?php echo $record['id'];?>';
            var uid = '<?php echo $record['parent_id'];?>';
        </script>
        <?php require_once(HResponse::path('admin') . '/order/parts.tpl'); ?>
        <?php require_once(HResponse::path('admin') . '/common/footer.tpl'); ?>
        <script type="text/javascript"> var fromId = '<?php echo HRequest::getParameter('fid');?>';</script>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/info.js"></script>
        <script type="text/javascript" src="<?php echo HResponse::uri('admin'); ?>/js/order-detail.js"></script>
    </body>
</html>
