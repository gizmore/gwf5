<?php $field instanceof GDO_Object; $id = 'gwfac_'.$field->name; ?>
<md-input-container
 ng-controller="GWFAutoCompleteCtrl"
 ng-init='init(<?php echo $field->initCompletionJSON(); ?>, "#<?php echo $id; ?>")'
 class="md-block md-float md-icon-left<?php echo $field->classError(); ?>"
 flex>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php if ($field->tooltip) : ?>
  <md-tooltip md-direction="right"><?php echo htmlspecialchars($field->tooltip); ?></md-tooltip>
  <?php endif; ?>
  <?php echo $field->htmlIcon(); ?>
  <md-autocomplete
   md-search-text="searchText"
   md-items="item in query(searchText)"
   md-item-text="item.display"
   md-selected-item="selectedItem"
   md-selected-item-change="objectSelected(selectedItem)"
   md-min-length="1"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlDisabled(); ?>>
    <md-item-template>
      <span md-highlight-text="searchText" md-highlight-flags="^i">{{item.display}}</span>
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
