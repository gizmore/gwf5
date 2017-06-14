<div class="gwf-form" layout="column" flex layout-fill ng-controller="GWFFormCtrl">
  
  <div class="gwf-form-head">
    <h2 class="gwf-form-title"><?php echo $title; ?></h2>
    <p><?php echo $info; ?></p>
  </div>

  <div class="gwf-form-inner md-inline-form" layout="column" layout-fill flex layout-padding>
    <form action="<?php echo $action; ?>" method="<?php echo $method; ?>" enctype="<?php echo $enctype; ?>">
      <?php foreach ($fields as $field): $field instanceof GDOType; ?>
        <md-content layout="row" layout-fill flex>
          <?php echo $field->render(); ?>
        </md-content>
      <?php endforeach; ?>
    </form>
  
  </div>
</div>
