<?php $field instanceof GDO_Language; ?>
<?php $language = $field->getGDOValue(); ?>
<?php if ($language) : ?>
<img
 class="gwf-language"
 alt="<?php echo $language->displayName(); ?>"
 src="/theme/default/img/language/<?php echo $language->getID(); ?>.png" />
<?php else : ?>
<img
 class="gwf-language"
 alt="<?php l('unknown_language'); ?>"
 src="/theme/default/img/language/zz.png" />
<?php endif;?>
