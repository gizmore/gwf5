<?php $field instanceof GDO_Checkbox; ?>
<label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
<input
 type="checkbox"
 name="form[<?php echo $field->name; ?>]"
 <?php echo $field->htmlChecked(); ?>
 <?php echo $field->htmlDisabled(); ?>></input>
