<?php $field instanceof GDO_Bar; ?>
<section class="gwf-navbar" layout="<?php echo $field->direction; ?>" layout-fill flex layout-align="space-around center">
  <?php foreach ($field->getFields() as $field) : ?>
    <div><?php echo $field->render(); ?></div>
  <?php endforeach; ?>
</section>
