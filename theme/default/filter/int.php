<?php $field instanceof GDO_Int; ?>
<input
 name="f[<?php echo $field->name; ?>]"
 type="text"
 pattern="^[-0-9]*$"
 value="<?php echo $field->displayFilterValue(); ?>"
 size="2" />
