<?php
/**
 * GDO field for a GWF_Language.
 * @author gizmore
 * @see GWF_Language
 */
final class GDO_Language extends GDO_ObjectSelect
{
	public function defaultLabel() { return $this->label('language'); }
	
	public function __construct()
	{
		$this->klass("GWF_Language");
		$this->min = $this->max = 2;
	}
	
	private $all = false;
	public function all(bool $all=true)
	{
		$this->all = $all;
		return $this;
	}
	
	public function withCompletion()
	{
		return $this->completion(href('GWF', 'CompleteLanguage'));
	}
	
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
	
	public function initChoices()
	{
		return $this->choices ? $this : $this->choices($this->languageChoices());
	}
	
	private function languageChoices()
	{
		$languages = GWF_Language::table();
		return $this->all ? $languages->all() : $languages->allSupported();
	}
	
}
