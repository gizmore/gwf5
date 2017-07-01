<?php $field instanceof GDO_Country; ?>
<?php if ($country instanceof GWF_Country) : ?>
<img
 class="gwf-country"
 alt="<?php echo $country->displayName(); ?>"
 src="/theme/default/img/country/<?php echo $country->getID(); ?>.png" />
<?php echo $country->displayName(); ?>
<?php else : ?>
<img
 class="gwf-country"
 alt="<?php l('unknown_country'); ?>"
 src="/theme/default/img/country/zz.png" />
<?php if ($choice) { ?>
<?php l('unknown_country'); ?>
<?php }?>
<?php endif;?>
