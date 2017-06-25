<?php
final class GDO_Country extends GDO_Select
{
	use GDO_ObjectTrait;
	
	public function __construct()
	{
		$this->klass("GWF_Country");
		$this->min = $this->max = 2;
	}
	
	public function withCompletion()
	{
		return $this->completion(href('GWF', 'CompleteCountry'));
	}
	
	public function defaultLabel() { return $this->label('country'); }
	
	private function countryChoices()
	{
		return GWF_Country::all();
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/country.php', ['field'=>$this, 'country'=>$this->getGDOValue()])->getHTML();
	}
	
	public function render()
	{
		if ($this->completionURL)
		{
			return GWF_Template::mainPHP('form/object_completion.php', ['field' => $this]);
		}
		else
		{
			$this->choices = $this->countryChoices();
			return GWF_Template::mainPHP('form/country.php', ['field'=>$this]);
		}
	}

	public function validate($value)
	{
		$this->choices = $this->countryChoices();
		return parent::validate($value);
	}
}
