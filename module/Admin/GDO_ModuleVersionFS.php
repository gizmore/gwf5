<?php
final class GDO_ModuleVersionFS extends GDO_Int
{
	/**
	 * @return GWF_Module
	 */
	public function getModule() { return $this->gdo; }
	
	public function renderCell()
	{
		$module = $this->getModule();
		$class = $module->canUpdate() ? ' class="can-update"' : '';
		return sprintf('<div%s>%.02f</div>', $class, $this->gdo->module_version);
	}

	public function gdoCompare(GDO $a, GDO $b)
	{
		$va = $a->module_version;
		$vb = $b->module_version;
		return $va == $vb ? true : ($va < $vb ? -1 : 1);
	}
}
