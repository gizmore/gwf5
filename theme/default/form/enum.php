<?php $field instanceof GDO_Enum; ?>
<md-input-container class="md-block md-float md-icon-left<?= $field->classError(); ?>" flex>
  <label><?= $field->displayLabel(); ?></label>
  <md-select
   ng-controller="GWFSelectCtrl"
   ng-model="selection"
   ng-init="selection = '<?= $field->displayFormValue(); ?>'"
   ng-change="valueSelected('#gwfsel_<?= $field->name; ?>')"
   <?= $field->htmlRequired(); ?>
   <?= $field->htmlDisabled(); ?>>
    <?php foreach ($field->enumValues as $enumValue) : ?>
      <md-option value="<?= $enumValue; ?>"><?= t('enum_'.$enumValue); ?></md-option>
    <?php endforeach; ?>
  </md-select>
  <input
   class="n"
   type="hidden"
   id="gwfsel_<?= $field->name; ?>"
   value="<?= $field->displayFormValue(); ?>"
   name="form[<?= $field->name?>]" />
  <div class="gwf-error"><?= $field->displayError(); ?></div>
</md-input-container>
