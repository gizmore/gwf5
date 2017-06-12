<?php
class GDO_Color extends GDO_String
{
	public function __construct()
	{
		$this->min = 4;
		$this->max = 7;
		$this->pattern = "/^#(?:[a-z0-9]{3}){1,2}$/i";
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/color.php', ['field' => $this]);
	}
}
