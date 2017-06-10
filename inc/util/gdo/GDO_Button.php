<?php
class GDO_Button extends GDO_Label
{
	############
	### HREF ###
	############
	public $href = 'javascript:;';
	public function htmlHREF() { return sprintf(' href="%s"', GWF_HTML::escape($this->href)); }
	public function href(string $href)
	{
		$this->href = $href;
		return $this;
	}
	

	##############
	### Render ###
	##############
	public function htmlClass()
	{
		return sprintf(' class="gdo-button %s"', str_replace('_', '-', strtolower($this->gdoClassName())));
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/button.php', ['field'=>$this]);
	}
	
	public function renderCell()
	{
		$href = call_user_func(array($this->gdo, 'href_'.$this->name));
		return GWF_Template::mainPHP('cell/button.php', ['field'=>$this, 'href'=>$href])->getHTML();
	}

	public function displayHeaderLabel()
	{
		return '';
	}
}

