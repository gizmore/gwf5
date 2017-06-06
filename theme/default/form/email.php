<?php $field instanceof GDO_Email; ?>
<label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
<input type="email"<?php echo $field->htmlDisabled(); ?><?php echo $field->htmlRequired(); ?> value="<?php echo $field->displayFormValue(); ?>" name="form[<?php echo $field->name; ?>]" placeholder="<?php echo $field->displayPlaceholder(); ?>"></input>
<div class="form-error"><?php echo $field->displayError(); ?></div>
