<?php $field instanceof GDO_Toolbar; ?>
<md-toolbar md-scroll-shrink>
  <div class="md-toolbar-tools">
<?php
foreach ($field->getFields() as $gdoType)
{
    echo $gdoType->renderCell();
}
?>
  </div>
</md-toolbar>
