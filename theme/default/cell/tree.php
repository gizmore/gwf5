<?php $field instanceof GDO_Tree; ?>
<?php
$gdo = $field->gdo; $gdo instanceof GWF_Tree;
$json = [];
$roots = $gdo->full();
foreach ($roots as $root)
{
	$json[] = $root->toJSON();
}


?>
<div
>
<div
 ng-init='tree=(<?php echo json_encode($json); ?>)'
 ivh-treeview="tree"
 ivh-treeview-id-attribute="'id'"
 ivh-treeview-label-attribute="'label'"
 ivh-treeview-children-attribute="'children'"
 ivh-treeview-selected-attribute="'selected'">
<script type="text/ng-template">
  <span ivh-treeview-toggle>
    <span ivh-treeview-twistie></span>
  </span>
  <md-box></md-box>
  <span class="ivh-treeview-node-label" ivh-treeview-toggle>{{trvw.label(node)}}</span>
  <div ivh-treeview-children></div>
</script>
</div>
</div>