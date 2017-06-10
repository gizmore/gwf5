<?php $field instanceof GDO_Username; ?>
<md-input-container class="md-block md-float md-icon-left" flex>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo GDO_Button::matIconS('face'); ?>
  <input
   type="text"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>/>
</md-input-container>
