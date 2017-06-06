<?php $field instanceof GDO_Username; ?>
<label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
<input type="text"<?php echo $field->htmlDisabled(); ?> value="<?php echo $field->displayFormValue(); ?>" name="form[<?php echo $field->name; ?>]" pattern="[a-zA-Z][-_0-9a-zA-Z]{1,23}" <?php echo $field->htmlRequired();''?> placeholder="<?php echo $field->displayPlaceholder(); ?>"></input>
<div class="form-error"><?php echo $field->displayError(); ?></div>