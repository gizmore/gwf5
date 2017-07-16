<?php $field instanceof GDO_Button; ?>
<?php if ($href) : ?>
<a href="<?= $href; ?>" class="md-button md-primary md-raised">
  <?php echo $field->displayLabel(); ?>
  <?php echo $field->htmlIcon(); ?>
</a>
<?php endif; ?>
