<?php $field instanceof GDO_Country; ?>
<?php $country = $field->getGDOValue(); ?>
<?php if ($country instanceof GWF_Country) : ?>
<img
 class="gwf-country"
 alt="<?php echo $country->displayName(); ?>"
 src="/theme/default/img/country/<?php echo $country->getID(); ?>.png" />
<?php else : ?>
<img
 class="gwf-country"
 alt="<?php l('unknown_country'); ?>"
 src="/theme/default/img/country/zz.png" />
<?php endif;?>
