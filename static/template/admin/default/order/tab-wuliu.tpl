
<?php $field = 'process'; require(HResponse::path('admin') . '/fields/select.tpl'); ?>
<?php $field = 'wuliu_code'; require(HResponse::path('admin') . '/fields/text.tpl'); ?>
<hr>
<?php $address  = json_decode($record['address'], true); ?>
                                                <p><strong class="fs-14">收货人信息：</strong></p>
                                                <div class="row-fluid">
                                                    <div class="span3">
                                                        <label for="">收货人：</label>
                                                        <input type="text" class="span12" id="accepter" value="<?php echo $address['name'];?>"/>
                                                    </div>
                                                    <div class="span3">
                                                        <label for="">联系电话：</label>
                                                        <input type="text" class="span12" id="accepter" value="<?php echo $address['phone'];?>"/>
                                                    </div>
                                                    <div class="span6">
                                                        <label for="">详细地址：</label>
                                                        <input type="text" class="span12" name="detail" id="detail" value="<?php echo $address["detail"];?>"/>
                                                    </div>
                                                </div>
                                                <div class="row-fluid">
                                                    <div class="span3">
                                                        <label for="">省：</label>
                                                        <input type="text" class="span12" name="province" id="province" value="<?php echo $address['province'];?>"/>
                                                    </div>
                                                    <div class="span3">
                                                        <label for="">市：</label>
                                                        <input type="text" class="span12" name='city' id='city' value="<?php echo $address['city'];?>"/>
                                                    </div>
                                                    <div class="span3">
                                                        <label for="">区：</label>
                                                        <input type="text" class="span12" name="district" id="district" value="<?php echo $address["district"];?>"/>
                                                    </div>
                                                    <div class="span3">
                                                        <label for="">街：</label>
                                                        <input type="text" class="span12" name="street" id="street" value="<?php echo $address["street"];?>"/>
                                                    </div>
                                                </div>