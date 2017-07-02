<?php
/**
 * A navigation tab menu.
 * @author gizmore
 *
 */
final class GDO_Tabs extends GDOType
{
	/**
	 * @var GDO_Tab[]
	 */
	private $tabs = [];
	public function getTabs()
	{
		return $this->tabs;
	}

	public function tab(GDO_Tab $tab)
	{
		$this->tabs[] = $tab;
		return $this;
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		return GWF_Template::mainPHP('cell/tabs.php', ['field' => $this, 'cell' => false]);
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/tabs.php', ['field' => $this, 'cell' => true])->getHTML();
	}
}
