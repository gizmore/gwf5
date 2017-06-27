<?php $field instanceof GDO_Tree; ?>
<?php
$id = 'gwftreecbx_'.$field->name;
$gdo = $field->gdo;
$gdo instanceof GWF_Tree;
# Build  Tree JSON
$json = [];
list($tree, $roots) = $gdo->full();
foreach ($roots as $root)
{
	$json[] = $root->toJSON();
}
// foreach ($tree as $leaf)
// {
// 	$json[$leaf->getID()] = $leaf->toJSON();
// }
?>
<div class="gwf-tree"
 ng-controller="GWFTreeCtrl"
 ng-init='init("#<?php echo $id; ?>" , <?php echo json_encode($json); ?>)'>
<?php
foreach ($roots as $root)
{
	_gwfTreeRecurse($root);
}
?>
</div>

<?php
function _gwfTreeRecurse(GWF_Tree $leaf)
{
	$r = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $leaf->getDepth());
	printf($r.'<md-checkbox ng-model="all[%1$s].selected" ng-click="onToggled($event, %1$s);" md-indeterminate="all[%1$s].selected === null" >%2$s</md-checkbox><br/>', $leaf->getID(), $leaf->displayName());
	foreach ($leaf->children as $child)
	{
		_gwfTreeRecurse($child);
	}
}
?>
