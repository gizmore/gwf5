<?php $navbar instanceof GWF_Navbar; ?>
<section class="gwf-navbar" layout="<?php echo $navbar->direction(); ?>" flex>
  <ul>
    <?php foreach ($fields as $field) : $field instanceof GDOType; ?>
      <li><?php echo $field->render(); ?></li>
    <?php endforeach; ?>
  </ul>
</section>
