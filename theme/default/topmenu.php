<section class="gwf-topmenu" layout="row" layout-fill flex>
  <ul>
    <?php foreach ($fields as $field) : $field instanceof GDOType; ?>
      <li><?php echo $field->render(); ?></li>
    <?php endforeach; ?>
  </ul>
</section>
