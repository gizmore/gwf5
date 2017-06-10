<?php $field instanceof GDO_AntiCSRF; ?>
<input
 type="hidden"
 name="form[<?php echo $field->name; ?>]"
 value="<?php echo $field->csrfToken(); ?>"></input>
<div class="form-error"><?php echo $field->displayError(); ?></div>
