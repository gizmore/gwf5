<?php $field instanceof GDO_IconButton; ?>
<?php if ($href) : ?>
<md-button  href="<?= $href; ?>" class="md-secondary md-icon-button" aria-label="<?= htmle($field->label); ?>" <?= $field->htmlDisabled(); ?>>
  <?php echo $field->htmlIcon(); ?>
</md-button >
<?php endif; ?>
