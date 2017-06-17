<?php $field instanceof GDO_String; ?>
<input
 name="f[<?php echo $field->name?>]"
 type="text"
 value="<?php echo $field->displayFilterValue(); ?>" />
