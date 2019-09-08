                                <div class="control-group">
                                    <label class="control-label" for="<?php echo $field; ?>"><?php echo $popo->getFieldName($field); ?></label>
                                    <div class="controls">
                                        <div class="row-fluid input-append date span3">
                                            <input class="span10 date-picker" id="<?php echo $field; ?>" type="text" name="<?php echo $field; ?>" value="<?php echo empty($record[$field]) ? date('Y-m-d') : date('Y-m-d', strtotime($record[$field])); ?>" placeholder="请添加<?php echo $popo->getFieldName($field); ?>" data-date-format="yyyy-mm-dd"/>
                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                            <small class="help-info"><?php echo $popo->getFieldComment($field); ?></small>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <script type="text/javascript">
                                    dateList.push("#<?php echo $field;?>");
                                </script>
