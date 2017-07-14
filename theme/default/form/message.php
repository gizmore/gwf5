<?php $field instanceof GDO_Message; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
   <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo $field->htmlIcon(); ?>
  <textarea
   name="form[<?php echo $field->name; ?>]"
   rows="6"
   maxRows="6"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>><?php echo $field->displayFormValue(); ?></textarea>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
