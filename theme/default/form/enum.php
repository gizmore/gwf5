<?php $field instanceof GDO_Enum; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
  <label><?php echo $field->displayLabel(); ?></label>
  <md-select
   ng-controller="GWFSelectCtrl"
   ng-model="selection"
   ng-init="selection = '<?php echo $field->displayFormValue(); ?>'"
   ng-change="valueSelected('#gwfsel_<?php echo $field->name; ?>')">
    <?php foreach ($field->enumValues as $enumValue) : ?>
      <md-option value="<?php echo $enumValue; ?>"><?php l('enum_'.$enumValue); ?></md-option>
    <?php endforeach; ?>
  </md-select>
  <input
   class="n"
   type="hidden"
   id="gwfsel_<?php echo $field->name; ?>"
   value="<?php echo $field->displayFormValue(); ?>"
   name="form[<?php echo $field->name?>]" />
  <div class="gwf-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
