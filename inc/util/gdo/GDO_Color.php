<?php
class GDO_Color extends GDO_String
{
	public $min = 4;
	public $max = 7;
	public $pattern = "/^#(?:[a-z0-9]{3}){1,2}$/i";
	
	public function defaultLabel() { return $this->label('color'); }
	
	public function render()
	{
		return GWF_Template::mainPHP('form/color.php', ['field' => $this]);
	}
}
