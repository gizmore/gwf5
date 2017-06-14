<?php
/**
 * GDO field for a GWF_Language.
 * @author gizmore
 * @see GWF_Language
 */
final class GDO_Language extends GDO_Select
{
	use GDO_ObjectTrait;
	
	public function __construct()
	{
		$this->klass = "GWF_Language";
		$this->label('language');
	}
	
	public function validate($value)
	{
		$this->choices = $this->languageChoices();
		return parent::validate($value);
	}
	
	public function render()
	{
		$this->choices = $this->languageChoices();
		return GWF_Template::mainPHP('form/language.php', ['field'=>$this]);
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/language.php', ['field'=>$this]);
	}
	
	private function languageChoices()
	{
		static $CHOICES;
		if (!isset($CHOICES))
		{
			$CHOICES = GWF_Language::table()->select('*')->exec()->fetchAllArray2dObject();
		}
		return $CHOICES;
	}
	
}
