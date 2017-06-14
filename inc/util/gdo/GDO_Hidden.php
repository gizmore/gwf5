<?php
final class GDO_Hidden extends GDOType
{
	public function renderCell() { return ''; }
	public function render()
	{
		return GWF_Template::mainPHP('form/hidden.php', ['field' => $this]);
	}
}
