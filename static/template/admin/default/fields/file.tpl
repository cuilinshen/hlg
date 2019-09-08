                                <?php $fileType     = str_replace('.', '', implode('|', $popo->getFieldAttribute($field, 'type'))); ?> 
                                <div class="control-group">
                                    <label class="control-label" for="<?php echo $field; ?>"><?php echo $popo->getFieldName($field); ?></label>
                                    <div class="controls">
                                        <div class="span12">
                                            <input type="file" id="<?php echo $field; ?>"
                                                name="<?php echo $field; ?>"
                                                data-field="<?php echo $field; ?>"
                                                data-type="|<?php echo $fileType; ?>|" 
                                                class="file-field span12"
                                            />
                                            <?php
                                                if(!empty($record[$field])) {

                                                    if(preg_match('/jpg|png|gif|bmp/i', $fileType)) {
                                                        if(0 !== strpos($record[$field], 'http://')) {
                                                            $href    = HResponse::url() . $record[$field];
                                                            $detailImagePath    = HResponse::url() . HFile::getImageZoomTypePath($record[$field], 'small');
                                                        } else {
                                                            $href    = $record[$field];
                                                            $detailImagePath    = $record[$field];
                                                        }
                                                        echo '<div class="old-file-box"><a href="' . $href  . '" class="lightbox">'; 


                                                        HHtml::image($detailImagePath);
                                                    } else {
                                                        if(0 !== strpos($record[$field], 'http://')) {
                                                            $href    = HResponse::url() . $record[$field];
                                                        } else {
                                                            $href    = $record[$field];
                                                        }
                                                        echo '<a href="' . $href . '" title="下載' . HFile::getName($record[$field]) . '">';
                                                    }
                                                    echo '</a><input type="hidden" name="old_<?php echo $field; ?>" value="' . $record[$field] . '" /></div>';
                                                }
                                            ?>
                                            <small class="help-info"><?php echo $popo->getFieldComment($field); ?></small>
                                        </div>
                                    </div>
                                </div>
