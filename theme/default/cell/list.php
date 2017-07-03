<?php $field instanceof GDO_List; ?>
<section layout="column" layout-fill>
  <md-toolbar layout="row" class="md-hue-3">
    <div class="md-toolbar-tools">
      <span><?php echo $field->label; ?></span>
    </div>
  </md-toolbar>
  
<?php
$result = $field->getResult();
while ($gdo = $result->fetchObject())
{
	echo $gdo->renderCard()->getHTML();
}
?>
</section>
