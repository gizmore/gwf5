<?php $field instanceof GDO_Select; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
  <label><?php echo $field->displayLabel(); ?></label>
  <md-select
   <?php if ($field->multiple) : ?>
   multiple
   <?php endif; ?>
   ng-controller="GWFSelectCtrl"
   ng-model="selection"
   ng-init='init(<?php echo $field->formValue(); ?>, <?php echo $field->multiple?'true':'false'; ?>)'
   ng-change="multiValueSelected('#gwfsel_<?php echo $field->name; ?>')">
   <md-option value="<?php echo htmlspecialchars($field->emptyChoiceValue()); ?>"><?php echo $field->emptyChoiceLabel(); ?></md-option>
    <?php foreach ($field->choices as $value => $choice) : ?>
      <md-option value="<?php echo htmlspecialchars($value); ?>"><?php echo $choice instanceof GDO ? $choice->renderChoice() : $choice; ?></md-option>
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
