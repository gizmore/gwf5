<?php $field instanceof GDO_Enum; ?>
<md-select
 ng-controller="GWFSelectCtrl"
 ng-model="selection"
 multiple="true"
 placeholder="<?php echo $field->displayHeaderLabel(); ?>"
 ng-init="init(<?php echo $field->displayFilterValue(); ?>, true)"
 ng-change="multiValueSelected('#fsel_<?php echo $field->name; ?>')">
  <?php foreach ($field->enumValues as $enumValue) : ?>
    <md-option value="<?php echo $enumValue; ?>"><?php l($enumValue); ?></md-option>
    <?php endforeach; ?>
  </md-select>
  <input
   class="n"
   type="hidden"
   id="fsel_<?php echo $field->name; ?>"
   value="<?php echo $field->displayFormValue(); ?>"
   name="f[<?php echo $field->name?>]" />
  <div class="gwf-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
