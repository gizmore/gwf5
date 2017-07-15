<!-- GDO_Table -->
<?php
$field instanceof GDO_Table;

$headers = $field->getFields();

if ($pagemenu = $field->getPageMenu())
{
	echo $pagemenu->renderCell();
}
$result = $field->getResult();


?>
<form method="get" action="<?= $field->href; ?>" flex class="b">
<div
 class="gwf-table table-responsive"
 layout="column" flex layout-fill
 ng-controller="GWFTableCtrl"
 ng-init='init(<?= $field->initJSON(); ?>)'>
  <input type="hidden" name="mo" value="<?= htmle(Common::getGetString('mo','')); ?>" />
  <input type="hidden" name="me" value="<?= htmle(Common::getGetString('me','')); ?>" />
  <h3><?= $field->displayLabel(); ?></h3>
  <table id="gwfdt-<?= $field->name; ?>" class="table">
    <thead>
      <tr>
      <?php foreach($headers as $gdoType) : ?>
        <th<?= $gdoType->htmlClass(); ?>>
          <label>
            <?= $gdoType->displayHeaderLabel(); ?>
            <?php if ($field->ordered) : ?>
            <?= $gdoType->displayTableOrder($field); ?>
            <?php endif; ?>
          </label>
          <?php if ($field->filtered) : ?>
          <br/><?= $gdoType->renderFilter(); ?>
          <?php endif; ?>
        </th>
      <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
    <?php while ($gdo = $result->fetchAs($field->fetchAs)) : ?>
    <tr gdo-id="<?= $gdo->getID()?>">
      <?php foreach($headers as $gdoType) : $gdoType instanceof GDOType; ?>
        <td<?= $gdoType->htmlClass(); ?>><?= $gdoType->gdo($gdo)->renderCell(); ?></td>
      <?php endforeach; ?>
    </tr>
    <?php endwhile; ?>
    </tbody>
    <tfoot></tfoot>
  </table>
  <input type="submit" class="n" />
</div>
<?= $field->actions()->render(); ?>
</form>
<!-- END of GWF_Table -->
