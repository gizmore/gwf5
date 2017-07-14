<?php $field instanceof GDO_List; ?>
<md-list flex>
  <md-subheader class="md-no-sticky"><?= $field->displayHeaderLabel() ?></md-subheader>
<?php
$result = $field->getResult();
$template = $field->getItemTemplate();
while ($gdo = $result->fetchObject()) : ?>
<?php echo $template->gdo($gdo)->renderList(); ?>
<?php endwhile; ?>
</md-list>
