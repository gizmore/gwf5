<?php
/**
 * Tree view.
 * The gdo handled should inherit from GWF_Tree.
 * 
 * @author gizmore
 * @since 5.0
 */
class GDO_Tree extends GDO_Select
{
	use GDO_ObjectTrait;
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/tree.php', ['field' => $this]);
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/tree.php', ['field' => $this]);
	}
	
}
