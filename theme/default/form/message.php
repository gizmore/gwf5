<?php $field instanceof GDO_Message; ?>
<md-input-container class="md-block md-float md-icon-left" flex>
   <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo $field->htmlIcon(); ?>
  <textarea
   name="form[<?php echo $field->name; ?>]"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>><?php echo $field->displayFormValue(); ?></textarea>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
