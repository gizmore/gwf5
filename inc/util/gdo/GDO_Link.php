<?php
class GDO_Link extends GDO_Button
{
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/button.php', ['field'=>$this, 'href'=>$this->href])->getHTML();
	}
}
