<?php $field instanceof GDO_Tabs; ?>
<md-tabs md-dynamic-height md-border-bottom>
<?php foreach ($field->getTabs() as $tab) : ?>
<?php if ($cell) : ?>
<?php echo $tab->renderCell(); ?>
<?php endif; ?>
<?php echo $tab->render()->getHTML(); ?>
<?php endforeach; ?>
</md-tabs>
