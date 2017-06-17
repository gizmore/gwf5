<?php $field instanceof GDO_Gender; var_dump($field); ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
  <label><?php echo $field->displayLabel(); ?></label>
  <md-select
   ng-controller="GWFSelectCtrl"
   ng-model="selection"
   ng-init="selection = '<?php echo $field->displayFormValue(); ?>'"
   ng-change="valueSelected('#gwfsel_<?php echo $field->name; ?>')">
    <md-option value="0"><?php l('no_gender'); ?></md-option>
    <md-option value="m"><?php l('male'); ?></md-option>
    <md-option value="f"><?php l('female'); ?></md-option>
  </md-select>
  <input
   type="hidden"
   id="gwfsel_<?php echo $field->name; ?>"
   value="<?php echo $field->displayFormValue(); ?>"
   name="form[<?php echo $field->name?>]" />
  <div class="gwf-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
