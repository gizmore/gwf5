<?php $field instanceof GDO_Bar; ?>
<section
 class="gdo-bar gdo-bar<?php echo $field->name; ?>"
 layout="<?php echo $field->direction; ?>"
 layout-fill flex layout-wrap layout-align="space-around center">
  <?php foreach ($field->getFields() as $field) : ?>
    <div><?php echo $field->render(); ?></div>
  <?php endforeach; ?>
</section>
