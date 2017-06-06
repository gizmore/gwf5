<?php
class GDO_Divider extends GDO_Blank
{
	public function render()
	{
		return GWF_Template::templateMain('form/divider.php', ['field'=>$this]);
	}
}