<?php $field instanceof GDO_String; ?>
<input
 name="f[<?php echo $field->name?>]"
 type="text"
 size="<?php echo min($field->max, 24); ?>"
 value="<?php echo $field->displayFilterValue(); ?>" />
