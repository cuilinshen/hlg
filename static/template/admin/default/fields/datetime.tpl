                                 <div class="control-group">
                                    <label class="control-label" for="<?php echo $field; ?>"><?php echo $popo->getFieldName($field); ?></label>
                                    <div class="controls">
                                        <?php $time     = false === strpos($record[$field], '-') ? $record[$field] : strtotime($record[$field]);?>
                                        <div class="input-append date">
                                            <input class="span11 datetime-picker" id="<?php echo $field; ?>" type="text" 
                                            name="<?php echo $field; ?>" 
                                            value="<?php echo empty($time) ? date('Y-m-d H:m:s') : date('Y-m-d H:m:s', $time); ?>" 
                                            id="<?php echo $field; ?>"
                                            data-comment="<?php echo $popo->getFieldComment($field); ?>"
                                            placeholder="请添加<?php echo $popo->getFieldName($field); ?>" data-date-format="yyyy-mm-dd hh:mm:ss"/>
                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                            <small class="help-info"><?php echo $popo->getFieldComment($field); ?></small>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <script type="text/javascript">
                                    datetimeList.push("#<?php echo $field; ?>");
                                </script>                                
