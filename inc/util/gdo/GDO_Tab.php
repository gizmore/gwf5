<?php
/**
 * A tab panel.
 * @author gizmore
 */
final class GDO_Tab extends GDOType
{
	use GWF_Fields;

	##############
	### Render ###
	##############
	public function render()
	{
		return GWF_Template::mainPHP('cell/tab.php', ['field' => $this, 'cell' => false]);
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/tab.php', ['field' => $this, 'cell' => true]);
	}
}
