<?php $field instanceof GDO_Box; ?>
<md-content flex layout="row" layout-fill layout-padding class="gwf-box">
  <div flex layout-padding class="md-whiteframe-4dp">
    <?php if ($field->label) : ?>
    <h1><?php echo $field->label; ?></h1>
    <?php endif;?>
    <p><?php echo nl2br($field->content); ?></p>
  </div>
</md-content>
