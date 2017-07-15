<?php
final class GDO_FilterButton extends GDO_Button
{
	use GWF_Fields;
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/filterbutton.php', ['field'=>$this, 'href'=>$this->gdoHREF()])->getHTML();
	}
}
