<?php $navbar instanceof GWF_Navbar; ?>
<section class="gwf-navbar" layout="<?php echo $navbar->direction(); ?>" flex>
  <?php foreach ($fields as $field) : $field instanceof GDOType; ?>
    <?php echo $field->render(); ?>
  <?php endforeach; ?>
</section>
