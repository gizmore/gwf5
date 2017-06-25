<?php
class GDO_Tooltip extends GDO_Icon
{
	public function render()
	{
		return GWF_Template::mainPHP('cell/tooltip.php', ['field'=>$this]);
	}

	public function renderCell()
	{
		return $this->render();
	}
}
