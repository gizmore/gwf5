<?php $field instanceof GDO_Tab; ?>
<md-tab label="<?php echo $field->displayLabel(); ?>">
  <md-content class="md-padding">
<?php
foreach ($field->getFields() as $gdoType)
{
	echo $cell ? $gdoType->renderCell() : $gdoType->render()->getHTML();
}
?>
  </md-content>
</md-tab>
