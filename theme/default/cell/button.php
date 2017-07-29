<?php $field instanceof GDO_Button; ?>
<?php if ($href) : ?>
<a class="md-button md-primary md-raised" href="<?= $href; ?>" <?= $field->htmlDisabled(); ?>>
  <?php echo $field->displayLabel(); ?>
  <?php echo $field->htmlIcon(); ?>
</a>
<?php endif; ?>
