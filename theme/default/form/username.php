<?php $field instanceof GDO_Username; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php if ($field->tooltip) : ?>
  <md-tooltip md-direction="right"><?php echo htmlspecialchars($field->tooltip); ?></md-tooltip>
  <?php endif; ?>
  <?php echo GDO_Button::matIconS('face'); ?>
  <input
   type="text"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>/>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
