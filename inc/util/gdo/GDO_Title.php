<?php
class GDO_Headline extends GDOType
{
	public function render()
	{
		return GWF_Template::templateMain('form/headline.php', ['field'=>$this]);
	}
}
