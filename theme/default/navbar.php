<?php $navbar instanceof GWF_Navbar; ?>
<section class="gwf-navbar" layout="<?php echo $navbar->direction(); ?>" flex layout-fill layout-align="start">
  <?php foreach ($fields as $field) : $field instanceof GDOType; ?>
    <?php echo $field->renderCell(); ?>
  <?php endforeach; ?>
</section>
