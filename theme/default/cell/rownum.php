<?php $field instanceof GDO_RowNum; $field->num++; ?>
<?php $id = $num = $field->gdo ? $field->gdo->getID() : $field->num; ?>
<?php $id = $field->name.$id; ?>
<?php $name = "{$field->name}[$num]"; ?>
<div ng-controller="GWFCbxCtrl">
  <md-checkbox
   ng-init="<?php echo $id; ?>=false"
   ng-model="<?php echo $id; ?>"
   ng-change="cbxChangedDyn('<?php echo $id; ?>');"
   >
</md-checkbox>
  <input
   class="n"
   type="checkbox"
   id ="<?php echo $id; ?>"
   name="<?php echo $name ?>" />
</div>