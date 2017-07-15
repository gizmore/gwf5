<?php $field instanceof GDO_List; ?>
<?php
$result = $field->getResult();
// $filters = GDO_FilterButton::make()->addFields($field->getF)
?>
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
$template = $field->getItemTemplate();
while ($gdo = $result->fetchObject())
{
	echo $template->gdo($gdo)->renderCard()->getHTML();
}
?>
  
</section>
