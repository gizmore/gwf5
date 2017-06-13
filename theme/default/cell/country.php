<?php $field instanceof GDO_Country; ?>
<?php $country = $field->getGDOValue(); ?>
<?php if ($country) : ?>
<img class="gwf-country" src="/theme/default/img/country/<?php echo $field->getGDOValue()->getID(); ?>.png" />
<?php else : ?>
<img class="gwf-country" src="/theme/default/img/country/zz.png" />
<?php endif;?>
