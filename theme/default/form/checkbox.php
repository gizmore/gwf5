<?php $field instanceof GDO_Checkbox; $id = 'cbxform_' . $field->name; ?>
<md-input-container class="md-block">
  <md-checkbox
   <?php echo $field->htmlDisabled(); ?>
   ng-controller="GWFCbxCtrl"
   ng-init="cbx=<?php echo $field->formValue() > 0 ? 'true':'false'; ?>"
   ng-change="cbxChanged('#<?php echo $id; ?>');"
   ng-model="cbx"><?php echo $field->displayLabel(); ?></md-checkbox>
  <div class="form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
<!-- HIDDEN CHECKBOX -->
<input
 class="n"
 type="checkbox"
 id="<?php echo $id; ?>"
 name="form[<?php echo $field->name; ?>]"
 <?php echo $field->htmlChecked(); ?>
 <?php echo $field->htmlDisabled(); ?>></input>
<!-- END HIDDEN CHECKBOX -->
 