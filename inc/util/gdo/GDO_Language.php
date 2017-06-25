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
		$this->klass("GWF_Language");
		$this->min = $this->max = 2;
	}
	
	public function withCompletion()
	{
		return $this->completion(href('GWF', 'CompleteLanguage'));
	}
	
	public function defaultLabel() { return $this->label('language'); }
	
	public function validate($value)
	{
		$this->choices = $this->languageChoices();
		return parent::validate($value);
	}
	
	public function render()
	{
		if ($this->completionURL)
		{
			return GWF_Template::mainPHP('form/object_completion.php', ['field'=>$this]);
		}
		else
		{
			$this->choices = $this->languageChoices();
			return GWF_Template::mainPHP('form/language.php', ['field'=>$this]);
		}
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/language.php', ['language'=>$this->gdo]);
	}
	
	private function languageChoices()
	{
		return GWF_Language::all();
	}
	
}
