<?php $field instanceof GDO_Language; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>
  <label><?php echo $field->displayLabel(); ?></label>
  <md-select
   ng-controller="GWFSelectCtrl"
   ng-model="selection"
   <?php if ($field->multiple) { ?>
   multiple
   ng-init='init(<?php echo $field->formValue(); ?>)'
   ng-change="multiValueSelected('#gwfsel_<?php echo $field->name; ?>')">
   <?php } else { ?>
   ng-init="selection='<?php echo $field->formValue(); ?>'"
   ng-change="valueSelected('#gwfsel_<?php echo $field->name; ?>')">
   <?php } ?>
    <?php if ($field->emptyChoice) : ?>
      <md-option value="<?php echo GWF_HTML::escape($field->emptyValue); ?>">
        <img
         class="gwf-language"
         alt="<?php l('no_language'); ?>"
         src="/theme/default/img/language/zz.png" />
        <?php echo $field->emptyChoice; ?>
      </md-option>
    <?php endif; ?>
    <?php foreach ($field->choices as $iso => $language) : $language instanceof GWF_Language; ?>
      <md-option value="<?php echo $iso; ?>">
        <img
         class="gwf-language n"
         alt="<?php echo $language->displayName(); ?>"
         src="/theme/default/img/language/<?php echo $language->getID(); ?>.png" />
        <?php echo $language->displayName(); ?>
      </md-option>
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
