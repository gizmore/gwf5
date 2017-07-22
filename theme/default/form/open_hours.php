<?php $field instanceof GDO_OpenHours; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>

  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?= GDO_Icon::iconS('clock'); ?>
  <input type="text" ng-model="data.openHours.display" ng-click="openHoursDialog()"/>

  <input
   type="hidden"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlPattern(); ?>
   <?php echo $field->htmlDisabled(); ?>/>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
