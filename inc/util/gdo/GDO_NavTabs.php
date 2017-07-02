<?php
/**
 * A navigation tab menu.
 * @author gizmore
 *
 */
final class GDO_NavTabs extends GDOType
{
	use GWF_Fields;
	
	public function addTab(string $href, string $key, array $args=null)
	{
		$link = GDO_Link::make($key)->href($href)->label($key, $args);
		return $this->addField($link);
	}
	
	public function getTabIndex()
	{
		$i = 0;
		foreach ($this->getFields() as $gdoType)
		{
			if (strpos($_SERVER['REQUEST_URI'], $gdoType->href) !== false)
			{
				return $i;
			}
			$i++;
		}
		return false;
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		return GWF_Template::mainPHP('cell/navtabs.php', ['field' => $this]);
	}
	
	public function renderCell()
	{
		return $this->render()->getHTML();
	}
}
