<?php $field instanceof GDO_Divider; ?>
<md-divider></md-divider>
<?php if ($field->label) : ?>
<md-input-container layout="row" flex>
  <md-content flex layout-padding class="gwf-divider-label"><?php echo $field->label; ?></md-content>
</md-input-container>
<?php endif; ?>
