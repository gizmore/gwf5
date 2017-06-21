<!-- GWF_Table -->
<?php if ($pagemenu) $pagemenu = $pagemenu->render(); ?>
<?php if ($navbar) $navbar = $navbar->render(); ?>
<form method="post" action="<?php echo $table->href; ?>" flex class="b">
<?php echo $pagemenu; ?>
<div class="gwf-table table-responsive" layout="column" flex layout-fill><?php $table instanceof GWF_Table; ?>
  <input type="hidden" name="mo" value="<?php echo htmlspecialchars(Common::getRequestString('mo','')); ?>" />
  <input type="hidden" name="me" value="<?php echo htmlspecialchars(Common::getRequestString('me','')); ?>" />
  <h3><?php echo $title; ?></h3>
  <table id="gwfdt-<?php echo $table->name; ?>" class="table">
    <thead>
      <tr>
      <?php foreach($headers as $gdoType) : $gdoType instanceof GDOType; ?>
        <th<?php echo $gdoType->htmlClass(); ?>>
          <label><?php echo $gdoType->displayHeaderLabel(); ?></label>
          <?php if ($table->filtered) : ?>
          <br/><?php echo $gdoType->renderFilter(); ?>
          <?php endif; ?>
        </th>
      <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
    <?php while ($gdo = $result->fetchObject()) : $gdo instanceof GDO; ?>
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
<?php echo $navbar; ?>
</form>
<!-- END of GWF_Table -->
