<?php $field instanceof GDO_Object; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo $field->htmlIcon(); ?>
  <input
   type="number"
   step="1"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>/>
</md-input-container>
<div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
