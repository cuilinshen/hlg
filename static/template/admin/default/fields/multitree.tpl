        <div class="control-group">
			<label class="control-label" for="<?php echo $field; ?>"><?php echo $popo->getFieldName($field); ?>： </label>
            <div class="controls">
                <select
                    name="<?php echo $field; ?>[]" 
                    id="<?php echo $field; ?>" 
                    data-placeholder="请选择（可多选）"
                    data-cur="<?php echo !empty($record[$field]) ? $record[$field] : $popo->getFieldAttribute($field, 'default'); ?>" 
                    class="auto-select span12 chosen-select"
                    multiple 
                    >
                    <option value="">请选择</option>
                    <?php 
                        HClass::import('hongjuzi.utils.HTree');
                        $hTree  = new HTree(
                            HResponse::getAttribute($field . '_list'),
                            'id',
                            'parent_id',
                            'name',
                            'id',
                            '<option value="{id}">' .
                            '{name}' .
                            '</option>'
                        );
                        echo $hTree->getTree();
                    ?>
                </select>
                <small class="help-info"><?php echo $popo->getFieldComment($field); ?></small>
            </div>
            <div class="clearfix"></div>
		</div>
        <script type="text/javascript">selectList.push("#<?php echo $field; ?>");</script>
