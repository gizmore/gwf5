<?php $field instanceof GDO_Timestamp; $id = 'date_'.$field->name; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex ng-controller="GWFDatepickerCtrl">
  <?php echo $field->htmlIcon(); ?>
  <md-datepicker
   autocomplete="off"
   ng-init="pickDate='<?php echo $field->displayFormValue(); ?>'"
   md-placeholder="<?php echo $field->displayLabel(); ?>"
   ng-model="pickDate"
   ng-change="datePicked('#<?php echo $id ?>')"
   md-current-view="<?php echo $field->dateStartView; ?>"></md-datepicker>
 <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
  <input
   id="<?php echo $id; ?>"
   type="hidden"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>" />
</md-input-container>
 