<?php $field instanceof GDO_List; ?>
<section
 class="gwf-list"
 layout="column"
 flex
 layout-fill
 layout-padding
 ng-controller="GWFListCtrl"
 ng-init='init(<?php echo json_encode($field->initJSON()); ?>)'>

  <md-toolbar layout="row" class="md-hue-3">
    <div class="md-toolbar-tools">
      <span><?php echo $field->label; ?></span>
    </div>
  </md-toolbar>

  <md-virtual-repeat-container id="vertical-container" flex>
    <div md-virtual-repeat="item in infiniteItems" md-on-demand flex>
      {{item.id}}
    </div>
  </md-virtual-repeat-container>
  <?php
$result = $field->getResult();
while ($gdo = $result->fetchObject())
{
echo $gdo->renderCard()->getHTML();
}
?>
  
</section>
