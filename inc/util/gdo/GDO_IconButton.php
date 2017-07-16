<?php
class GDO_IconButton extends GDO_Button
{
	public function defaultLabel() { return $this; }
	
	public function render() { return GWF_Template::mainPHP('cell/iconbutton.php', ['field'=>$this, 'href'=>$this->gdoHREF()]); }
	public function renderCell() { return $this->render()->getHTML(); }
}
