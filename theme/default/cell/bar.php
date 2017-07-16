<?php $field instanceof GDO_Bar; ?>
<section
 class="gdo-bar gdo-bar-<?= $field->name; ?>"
 layout="<?= $field->direction; ?>"
 layout-fill flex layout-wrap layout-align="space-around center">
  <?php foreach ($field->getFields() as $field) : ?>
    <div><?= $field->render(); ?></div>
  <?php endforeach; ?>
</section>
