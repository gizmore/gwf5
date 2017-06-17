<?php $field instanceof GDO_Box; ?>
<md-whiteframe class="md-whiteframe-4dp">
<md-content layout-padding flex layout="row" layout-fill>
  <?php if ($field->label) : ?>
  <h1><?php echo $field->label; ?></h1>
  <?php endif;?>
  <p><?php echo $field->content; ?></p>
</md-content>
</md-whiteframe>
