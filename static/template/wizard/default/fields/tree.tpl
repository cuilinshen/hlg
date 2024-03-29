        <div class="control-group">
			<label class="control-label" for="<?php echo $field; ?>"><?php echo $popo->getFieldName($field); ?>： </label>
            <div class="controls">
                <select  name="<?php echo $field; ?>" id="<?php echo $field; ?>" data-cur="<?php echo !empty($record[$field]) ? $record[$field] : $popo->getFieldAttribute($field, 'default'); ?>" class="auto-select span3">
                    <option value="0">--<?php HResponse::lang('SELECT_CATEGORY'); ?>--</option>
                    <option value="0"><?php HResponse::lang('NONE'); ?></option>
                    <?php 
                        HClass::import('hongjuzi.utils.HTree');
                        $hTree  = new HTree(
                            HResponse::getAttribute($field . '_list'),
                            'id',
                            $field,
                            'name',
                            'id',
                            '<option value="{id}">' .
                            '{name}' .
                            '</option>'
                        );
                        echo $hTree->getTree();
                    ?>
                </select>
				<span class="help-inline"><?php echo $popo->getFieldComment($field); ?></span>
            </div>
		</div>
