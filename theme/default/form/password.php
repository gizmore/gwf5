<?php $field instanceof GDO_Password; ?>
<label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
<input type="password"<?php echo $field->htmlDisabled(); ?> value="" name="form[<?php echo $field->name; ?>]" <?php echo $field->htmlRequired(); ?>></input>
<div class="form-error"><?php echo $field->displayError(); ?></div>
