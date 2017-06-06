<?php $field instanceof GDO_CSRF; ?>
<input
 type="hidden"
 name="form[<?php echo $field->name; ?>]"
 value="<?php echo $field->displayFormValue(); ?>"></input>
<div class="form-error"><?php echo $field->displayError(); ?></div>
