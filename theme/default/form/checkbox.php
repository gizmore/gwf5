<?php $field instanceof GDO_Checkbox; $id = 'cbxform_' . $field->name; ?>
<md-input-container class="md-block<?php echo $field->classError(); ?>">
  <md-checkbox
   <?php echo $field->htmlDisabled(); ?>
   ng-controller="GWFCbxCtrl"
   ng-init="cbx=<?php echo $field->displayFormValue() > 0 ? 'true':'false'; ?>"
   ng-change="cbxChanged('#<?php echo $id; ?>');"
   ng-model="cbx"><?php echo $field->displayLabel(); ?></md-checkbox>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
<input
 class="n"
 type="checkbox"
 id="<?php echo $id; ?>"
 name="form[<?php echo $field->name; ?>]"
 <?php echo $field->htmlChecked(); ?>
 <?php echo $field->htmlDisabled(); ?>></input>
 