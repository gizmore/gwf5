<?php $field instanceof GDO_Button; ?>
<?php if ($href) : ?>
<a href="<?php echo $href ? $href : 'javascript:;'; ?>" class="md-button">
  <?php echo $field->displayLabel(); ?>
  <?php echo $field->htmlIcon(); ?>
</a>
<?php endif; ?>
