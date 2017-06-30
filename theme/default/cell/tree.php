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
 layout="column" layout-fill layout-padding flex="100" layout-align="start start"
 ng-controller="GWFTreeCtrl"
 ng-init='init("#<?php echo $id; ?>" , <?php echo json_encode($json); ?>)'>
<?php
foreach ($roots as $root)
{
	_gwfTreeRecurse($root);
}
?>
<input type="hidden" id="<?php echo $id; ?>" />
</div>

<?php
function _gwfTreeRecurse(GWF_Tree $leaf)
{
	$r2 = str_repeat('&nbsp;', $leaf->getDepth()*6);
	if ($leaf->children)
	{
		$lid = $leaf->getID();
		$r = '<i ng-show="isCollapsed('.$lid.')" ng-click="expand('.$lid.')" class="material-icons">chevron_right</i>';
		$r .= '<i ng-hide="isCollapsed('.$lid.')" ng-click="shrink('.$lid.')" class="material-icons">expand_more</i>';
	}
	else
	{
		$r = '<i class="material-icons">remove</i>';
	}
	printf('<div ng-class="{\'n\': !isShown(%1$s)}"><b>'.$r2.$r.'<md-checkbox ng-model="all[%1$s].selected" ng-click="onToggled($event, %1$s);" md-indeterminate="all[%1$s].selected === null" >%2$s</md-checkbox></b></div>', $leaf->getID(), $leaf->displayName());
	foreach ($leaf->children as $child)
	{
		_gwfTreeRecurse($child);
	}
}
?>
