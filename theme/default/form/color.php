<?php $field instanceof GDO_Color; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo $field->htmlIcon(); ?>
  <input
   type="color"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>/>
  <div class="gwf-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
