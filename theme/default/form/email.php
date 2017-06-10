<?php $field instanceof GDO_Email; ?>
<md-input-container class="md-block md-float md-icon-left" flex>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo GDO_Button::matIconS('email'); ?>
  <input
   type="email"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?> />
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
