<?php $field instanceof GDO_Bar; ?>
<section class="gwf-navbar" layout="<?php echo $field->direction; ?>" layout-fill flex>
  <?php foreach ($field->getFields() as $field) : ?>
    <?php echo $field->render(); ?>
  <?php endforeach; ?>
</section>
