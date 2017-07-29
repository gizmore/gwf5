<?php $field instanceof GDO_Button; ?>
<a class="md-button md-secondary gwf-button"
 <?= $field->htmlDisabled(); ?>
 <?= $field->htmlHREF(); ?>>
  <?php echo $field->htmlIcon(); ?>
  <?php echo $field->displayLabel(); ?>
</a>
