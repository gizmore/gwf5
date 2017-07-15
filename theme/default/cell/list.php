<?php $field instanceof GDO_List; ?>
<form>

<md-list flex>
  <md-subheader class="md-no-sticky"><?= $field->displayHeaderLabel() ?></md-subheader>
<?php
$result = $field->getResult();
$template = $field->getItemTemplate();
while ($gdo = $result->fetchObject()) : ?>
<?php echo $template->gdo($gdo)->renderList(); ?>
<?php endwhile; ?>
</md-list>


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
