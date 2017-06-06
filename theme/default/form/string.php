<?php $field instanceof GDO_String; ?>
<label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
<input type="text"<?php echo $field->htmlDisabled(); ?> value="<?php echo $field->displayFormValue(); ?>" name="form[<?php echo $field->name; ?>]"<?php echo $field->htmlRequired(); ?> placeholder="<?php echo $field->displayPlaceholder(); ?>"<?php echo $field->htmlPattern(); ?>></input>
<div class="form-error"><?php echo $field->displayError(); ?></div>
