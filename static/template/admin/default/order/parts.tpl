        <script type="text/template" id="edit-order-address-template">
            <form class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="receiver">收货人</label>
                    <div class="controls">
                        <input type="text" id="receiver" value="<?php echo $address['name'];?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="phone">手机</label>
                    <div class="controls">
                        <input type="text" id="phone" value="<?php echo $address['phone'];?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="address">地址</label>
                    <div class="controls">
                        <textarea id="dialog-address"><?php echo $address['detail'];?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="zipcode">邮编</label>
                    <div class="controls">
                        <input type="text" id="zipcode" value="<?php echo $address['code'];?>">
                    </div>
                </div>
            </form>
        </script>
        <script type="text/template" id="edit-order-fapiao-template">
            <form class="form-horizontal">
                <div class="control-group">
                    <input type="hidden" id="fapiao-id" value="<?php echo $fapiao['id']; ?>" >
                    <label class="control-label" for="receiver">发票类型</label>
                    <div class="controls">
                        <select id="fapiao-type">
                            <option value="1" <?php echo $fapiao['type'] == 1 ? 'selected' : ''; ?>>个人</option>
                            <option value="2" <?php echo $fapiao['type'] == 2 ? 'selected' : ''; ?>>公司</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="phone">发票抬头</label>
                    <div class="controls">
                        <input type="text" id="fapiao-name" value="<?php echo $fapiao['name'];?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="address">发票内容</label>
                    <div class="controls">
                        <textarea id="fapiao-content"><?php echo $fapiao['content'];?></textarea>
                    </div>
                </div>
            </form>
        </script>
        <script type="text/template" id="edit-order-wuliu-template">
            <form class="form-horizontal">
                <div class="control-group">
                    <input type="hidden" id="wuliu-id" value="<?php echo $wuliu['id']; ?>" >
                    <label class="control-label" for="wuliu-name">物流公司</label>
                    <div class="controls">
                        <select id="wuliu-name">
                            <?php foreach(HResponse::getAttribute('wuliu_id_list') as $item){ ?>
                            <option value="<?php echo $item['name']; ?>" <?php echo $item['name'] == $wuliu['name'] ? 'selected' : ''; ?>><?php echo $item['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="wuliu-code">物流单号</label>
                    <div class="controls">
                        <input type="text" id="wuliu-code" value="<?php echo $wuliu['code'];?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="wuliu-date">发货时间</label>
                    <div class="controls">
                        <input type="text" id="wuliu-date" value="<?php echo $wuliu['fahuo_time'] ? $wuliu['fahuo_time'] : date('Y-m-d', time()); ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="wuliu-content">物流进度</label>
                    <div class="controls">
                        <textarea id="wuliu-content"><?php echo $wuliu['content'];?></textarea>
                    </div>
                </div>
            </form>
        </script>
        <script type="text/template" id="edit-order-goods-template">
            <table id="order-goods" class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                <tbody>
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
                        foreach($goodsList as $key => $item) {
                            $total      = $item['number'] * $item['price'];
                            $sumGoods   += $total;
                    ?>
                    <tr class="goods-item">
                        <input type="hidden" value="<?php echo $item['id']; ?>" class="goods-id">
                        <td class="text-center"><?php echo $key + 1;?></td>
                        <td class="text-center"><?php echo $item['name'] ;?></td>
                        <td class="text-center"><input type="text" class="input-mini goods-price" value="<?php echo $item['price']; ?>"></td>
                        <td class="text-center"><input type="text" class="input-mini goods-number" value="<?php echo $item['number']; ?>"></td>
                        <td><div align="right">￥<span class="item-price"><?php echo $item['number'] * $item['price'];?></span>元</div></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </script>
        <script type="text/template" id="edit-order-zengpin-template">
            <table id="order-zengpin" class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
                <thead>
                    <tr>
                        <td scope="col"><div align="center"><strong>序号</strong></div></td>
                        <td scope="col"><div align="center"><strong>名称</strong></div></td>
                        <td scope="col"><div align="center"><strong>内容</strong></div></td>
                        <td scope="col"><div align="center"><strong>总库存量</strong></div></td>
                        <td scope="col"><div align="center"><strong>剩余量</strong></div></td>
                        <td scope="col"><div align="center"><strong>图片</strong></div></td>
                        <td scope="col"><div align="center"><strong>原价</strong></div></td>
                        <td scope="col"><div align="center"><strong>操作</strong></div></td>
                    </tr>
                </thead>
                <tbody id="zenping-list-box"></tbody>
            </table>
        </script>
        <script type="text/template" id="zenping-item-template">
            <tr class="add-zenping-item" id="add-zenping-item-{id}">
                <td class="text-center">{key}</td>
                <td class="text-center">{name}</td>
                <td class="text-center">{content}</td>
                <td class="text-center">{number}</td>
                <td class="text-center">{last_number}</td>
                <td class="text-center">{img}</td>
                <td><div align="center">￥{price}元</div></td>
                <td class="text-center"><a class="btn-add" href="javascript:void(0);" data-id="{id}"><i class="icon icon-plus"></i>  添加</a></td>
            </tr>
        </script>
        <script type="text/template" id="edit-activity-template">
            <table class="table table-hover table-bordered" width="100%" cellpadding="3" cellspacing="1">
              <thead>
              <tr>
                <td scope="col" width="10%"><div align="center"><strong>序号</strong></div></td>
                <td scope="col" width="25%"><div align="center"><strong>类别</strong></div></td>
                <td scope="col" width="25%"><div align="center"><strong>满</strong></div></td>
                <td scope="col" width="20%"><div align="center"><strong>减</strong></div></td>
                <td scope="col" width="20%"><div align="center"><strong>操作</strong></div></td>
              </tr>
              </thead>
              <tbody id="edit-activity-box"></tbody>
            </table>
        </script>
        <script type="text/template" id="edit-activity-item-template">
          <tr id="new-activity-item-{id}">
            <td scope="col" width="10%"><div align="center"><strong>{key}</strong></div></td>
            <td scope="col" width="25%"><div align="center"><strong>{type}</strong></div></td>
            <td scope="col" width="25%"><div align="center"><strong>{min_money}</strong></div></td>
            <td scope="col" width="20%"><div align="center"><strong>{sub_money}</strong></div></td>
            <td scope="col"  width="20%" ><div align="center">
                <a href="javascript:void(0);" class="btn-use" data-id="{id}">
                    <strong>使用</strong>        
                </a>
            </div></td>
          </tr>
        </script>

