<?php $field instanceof GDO_Decimal; ?>
<label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
<input
 type="number"
 value="<?php echo $field->displayFormValue(); ?>"
 name="form[<?php echo $field->name; ?>]"
 <?php echo $field->htmlRequired(); ?>
 <?php echo $field->htmlDisabled(); ?>
 placeholder="<?php echo $field->displayPlaceholder(); ?>"></input>
<div class="form-error"><?php echo $field->displayError(); ?></div>
