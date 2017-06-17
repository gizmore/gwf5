<?php
final class GDO_Country extends GDO_Select
{
	use GDO_ObjectTrait;
	
	public function __construct()
	{
		$this->klass = "GWF_Country";
	}
	
	public function defaultLabel() { return $this->label('country'); }
	
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
		$this->choices = $this->countryChoices();
		return GWF_Template::mainPHP('form/country.php', ['field'=>$this]);
	}

	public function validate($value)
	{
		$this->choices = $this->countryChoices();
		return parent::validate($value);
	}
}
