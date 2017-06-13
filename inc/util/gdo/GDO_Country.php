<?php
final class GDO_Country extends GDO_Select
{
	use GDO_ObjectTrait;
	
	public function __construct()
	{
		$this->klass = "GWF_Country";
		$this->choices = $this->countryChoices();
	}
	
	private function countryChoices()
	{
		static $CHOICES;
		if (!isset($CHOICES))
		{
			$CHOICES = GWF_Country::table()->select('*')->exec()->fetchAllArray2dObject();
		}
		return $CHOICES;
	}
	
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/country.php', ['field'=>$this]);
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/country.php', ['field'=>$this]);
	}
}
