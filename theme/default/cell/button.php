<?php $field instanceof GDO_Button; ?>
<?php if ($href) : ?>
<a href="<?php echo $href; ?>" class="md-button"><?php echo $field->htmlIcon(); ?><?php echo $field->displayLabel(); ?></a>
<?php endif; ?>
