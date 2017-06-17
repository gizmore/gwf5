<?php $field instanceof GDO_Validator; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
