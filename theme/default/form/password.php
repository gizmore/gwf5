<?php $field instanceof GDO_Password; ?>
<md-input-container class="md-block md-float md-icon-left" flex>
  <label><?php echo $field->displayLabel(); ?></label>
  <?php echo GDO_Button::matIconS('lock'); ?>
  <input
   type="password"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>/>
</md-input-container>
