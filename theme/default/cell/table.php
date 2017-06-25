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
<form method="post" action="<?php echo $field->href; ?>" flex class="b">
<div class="gwf-table table-responsive" layout="column" flex layout-fill>
  <input type="hidden" name="mo" value="<?php echo htmlspecialchars(Common::getRequestString('mo','')); ?>" />
  <input type="hidden" name="me" value="<?php echo htmlspecialchars(Common::getRequestString('me','')); ?>" />
  <h3><?php echo $field->displayLabel(); ?></h3>
  <table id="gwfdt-<?php echo $field->name; ?>" class="table">
    <thead>
      <tr>
      <?php foreach($headers as $gdoType) : ?>
        <th<?php echo $gdoType->htmlClass(); ?>>
          <label><?php echo $gdoType->displayHeaderLabel(); ?></label>
          <?php if ($field->filtered) : ?>
          <br/><?php echo $gdoType->renderFilter(); ?>
          <?php endif; ?>
        </th>
      <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
    <?php while ($gdo = $result->fetchObject()) : ?>
    <tr>
      <?php foreach($headers as $gdoType) : $gdoType instanceof GDOType; ?>
        <td<?php echo $gdoType->htmlClass(); ?>><?php echo $gdoType->gdo($gdo)->renderCell(); ?></td>
      <?php endforeach; ?>
    </tr>
    <?php endwhile; ?>
    </tbody>
    <tfoot></tfoot>
  </table>
  <input type="submit" class="n" />
</div>
<?php echo $field->actions()->render(); ?>
</form>
<!-- END of GWF_Table -->
