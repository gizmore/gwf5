<?php $field instanceof GDO_Object; $id = 'gwfac_'.$field->name; ?>
<md-input-container
 ng-controller="GWFAutoCompleteCtrl"
 ng-init='init(<?php echo $field->initCompletionJSON(); ?>, "#<?php echo $id; ?>")'
 class="md-block md-float md-icon-left<?php echo $field->classError(); ?>"
 flex>
  <?php if ($field->tooltip) : ?>
  <md-tooltip md-direction="right"><?php echo htmlspecialchars($field->tooltip); ?></md-tooltip>
  <?php endif; ?>
  <?php echo $field->htmlIcon(); ?>
  <md-autocomplete
   md-clear-button="true"
   md-floating-label="<?php echo $field->displayLabel(); ?>"
   md-search-text="data.searchText"
   md-items="item in query(data.searchText)"
   md-item-text="item.text"
   md-selected-item="selectedItem"
   md-selected-item-change="objectSelected(selectedItem)"
   md-min-length="0"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>>
    <div ng-bind-html="selectedItem"></div>
    <md-item-template>
      <div ng-bind-html="item.display"></div>
    </md-item-template>
    <md-not-found><?php l('no_match'); ?></md-not-found>
  </md-autocomplete>
  <input
   type="hidden"
   id="<?php echo $id; ?>"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>" />
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
