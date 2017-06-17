<?php $field instanceof GDO_Hidden; ?>
<input
 type="hidden"
 name="form[<?php echo $field->name; ?>]"
 value="<?php echo $field->displayFormValue(); ?>">
