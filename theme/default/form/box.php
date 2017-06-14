<?php $field instanceof GDO_Box; ?>
<md-content layout-padding flex>
<?php if ($field->label) : ?>
  <h1><?php echo $field->label; ?></h1>
<?php endif;?>
  <p><?php echo $field->content; ?></p>
</md-content>
