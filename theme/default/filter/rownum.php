<?php $field instanceof GDO_Checkbox; ?>
<div ng-controller="GWFTableToggleCtrl">
  <md-checkbox
   md-indeterminate="cbxAll === undefined"
   ng-model="cbxAll"
   ng-click="cbxToggleAll($event)"></md-checkbox>
</div>
