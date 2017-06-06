<div class="gwf-table"><?php $table instanceof GWF_Table; ?>
  <table>
    <thead>
      <tr>
      <?php foreach($headers as $gdoType) : $gdoType instanceof GDOType; ?>
        <th><?php echo $gdoType->displayLabel(); ?></th>
      <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
    <?php while ($gdo = $result->fetchObject()) : $gdo instanceof GDO; ?>
    <tr>
      <?php foreach($headers as $gdoType) : $gdoType instanceof GDOType; ?>
        <td<?php echo $gdoType->htmlClass(); ?>>
          <?php echo $gdo->display($gdoType->name); ?>
        </td>
      <?php endforeach; ?>
    </tr>
    <?php endwhile; ?>
    </tbody>
    <tfoot><p>hi</p></tfoot>
  </table>
</div>
<?php if ($pagemenu) echo $pagemenu->render(); ?>