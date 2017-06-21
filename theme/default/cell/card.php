<?php $field instanceof GDO_Card; ?>
<md-card class="gwf-card">
<!--   <img ng-src="{{imagePath}}" class="md-card-image" alt="Washed Out"> -->
  <md-card-title><md-card-title-text><span class="md-headline"><?php echo $field->label; ?></span></md-card-title-text></md-card-title>
  <md-card-content>
<?php echo $field->content; ?>
  </md-card-content>
  <md-card-actions layout="row" layout-align="end center">
<?php foreach ($field->getActions() as $action) : ?>
    <?php echo $action->renderCell(); ?>
<?php endforeach; ?>
  </md-card-actions>
</md-card>
