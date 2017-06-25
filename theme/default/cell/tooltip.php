<?php $field instanceof GDO_Tooltip; ?>
<div class="gdo-tooltip">
  <?php echo GDO_Icon::iconS('help'); ?>
  <md-tooltip md-direction="right"><?php echo $field->displayTooltip(); ?></md-tooltip>
</div>
