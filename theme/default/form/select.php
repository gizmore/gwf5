<?php $field instanceof GDO_Select; ?>
<md-input-container class="md-block md-float md-icon-left" flex>
  <label><?php echo $field->displayLabel(); ?></label>
  <md-select
   <?php if ($field->multiple) : ?>
   multiple
   <?php endif; ?>
   ng-controller="GWFSelectCtrl"
   ng-model="selection"
   ng-init='init(<?php echo $field->formValue(); ?>)'
   ng-change="multiValueSelected('#gwfsel_<?php echo $field->name; ?>')">
    <?php foreach ($field->choices as $value => $choice) : ?>
      <md-option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($choice); ?></md-option>
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
