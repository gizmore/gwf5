<div class="gwf-form" layout="col" layout-fill flex>
  <h2 class="gwf-form-title"><?php echo $title; ?></h2>
  <p><?php echo $info; ?></p>
  <form action="<?php echo $action; ?>" method="<?php echo $method; ?>" enctype="<?php echo $enctype; ?>">
    <?php foreach ($fields as $field): $field instanceof GDOType; ?>
      <section layout="row" layout-fill flex>
        <md-input-container<?php echo $field->htmlClass(); ?>><?php echo $field->render(); ?></md-input-container>
      </section>
    <?php endforeach; ?>
  </form>
</div>
