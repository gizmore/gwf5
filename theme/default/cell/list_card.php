<?php $field instanceof GDO_List; ?>
<?php
$result = $field->getResult();
?>
<section
 class="gwf-list"
 layout="column"
 flex
 layout-fill
 ng-controller="GWFListCtrl"
 ng-init='init(<?php echo json_encode($field->initJSON()); ?>)'>

  <md-toolbar layout="row" class="md-hue-3">
    <div class="md-toolbar-tools">
      <span><?php echo $field->label; ?></span>
      <span flex></span>
      <a class="md-icon-button md-button" ng-click="showDialogId('#gwf-filter-dialog', $event)">
        <i class="material-icons">perm_data_setting</i>
      </a>
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

<!-- Filter Dialog -->
<div style="visibility: hidden">
  <div class="md-dialog-container" id="gwf-filter-dialog">
    <md-dialog aria-label="Mango (Fruit)">
      <md-dialog-content style="max-width:800px;max-height:810px; ">
        <md-tabs md-dynamic-height md-border-bottom>
          <md-tab label="Filters">
            <md-content class="md-padding">
              <form method="get" action="<?= $field->href ?>">
<?php foreach ($field->getFields() as $gdoType) : ?>
                <md-input-container>
                  <label><?= $gdoType->label; ?></label>
                  <?= $gdoType->renderFilter(); ?>
                </md-input-container>
<?php endforeach; ?>
                <input type="hidden" name="mo" value="<?= htmle(Common::getGetString('mo')); ?>">
                <input type="hidden" name="me" value="<?= htmle(Common::getGetString('me')); ?>">
                <input type="submit" class="n" />
              </form>
            </md-content>
          </md-tab>
          <md-tab label="Sorting">
            <md-content class="md-padding">
<?php foreach ($field->getFields() as $gdoType) : ?>
              <label><?= $gdoType->label; ?></label>
              <?= $gdoType->displayTableOrder($field)?>
<?php endforeach; ?>
            </md-content>
          </md-tab>
        </md-tabs>
      </md-dialog-content>
    </md-dialog>
  </div>
</div>
<!-- End Filter Dialog -->
