<?php if ($pagemenu) $pagemenu = $pagemenu->render(); ?>

<?php echo $pagemenu; ?>

<!-- GWF_Table -->
<div class="gwf-table table-responsive" layout="column" flex layout-fill><?php $table instanceof GWF_Table; ?>
  <form method="get" action="<?php echo $table->href; ?>">
  <input type="hidden" name="mo" value="<?php echo htmlspecialchars(Common::getRequestString('mo','')); ?>" />
  <input type="hidden" name="me" value="<?php echo htmlspecialchars(Common::getRequestString('me','')); ?>" />
  <table id="gwfdt-<?php echo $table->name; ?>" class="table">
    <thead>
      <tr>
      <?php foreach($headers as $gdoType) : $gdoType instanceof GDOType; ?>
        <th<?php echo $gdoType->htmlClass(); ?>>
          <label><?php echo $gdoType->displayHeaderLabel(); ?></label>
          
          <br/>
          <?php echo $gdoType->renderFilter(); ?>
        </th>
      <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
    <?php while ($gdo = $result->fetchObject()) : $gdo instanceof GDO; ?>
    <tr>
      <?php foreach($headers as $gdoType) : $gdoType instanceof GDOType; ?>
        <td<?php echo $gdoType->htmlClass(); ?>><?php echo $gdoType->gdo($gdo)->gdoRenderCell(); ?></td>
      <?php endforeach; ?>
    </tr>
    <?php endwhile; ?>
    </tbody>
    <tfoot></tfoot>
  </table>
  <input type="submit" class="n" />
  </form>
</div>
<!-- END of GWF_Table -->

<?php echo $pagemenu; ?>
