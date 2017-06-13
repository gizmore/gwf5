<?php
final class GDO_Country extends GDO_Object
{
	public function __construct()
	{
		$this->klass = "GWF_Country";
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/country.php', ['field'=>$this]);
	}
}
