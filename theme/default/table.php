<?php if ($pagemenu) $pagemenu = $pagemenu->render(); ?>

<?php echo $pagemenu; ?>

<!-- GWF_Table -->
<div class="gwf-table table-responsive" layout="column" flex layout-fill><?php $table instanceof GWF_Table; ?>
  <table id="gwfdt-<?php echo $table->name; ?>" class="table">
    <thead>
      <tr>
      <?php foreach($headers as $gdoType) : $gdoType instanceof GDOType; ?>
        <th<?php echo $gdoType->htmlClass(); ?>>
          <label><?php echo $gdoType->displayHeaderLabel(); ?></label>
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
</div>
<!-- END of GWF_Table -->

<?php echo $pagemenu; ?>
