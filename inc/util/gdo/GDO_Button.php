<?php
class GDO_Button extends GDO_Label
{
	use GDO_HREFTrait;
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
	
	public function gdoHREF()
	{
		return $this->href ? $this->href : call_user_func(array($this->gdo, 'href_'.$this->name));
	}
	
	public function gdoLabel()
	{
		return call_user_func(array($this->gdo, 'display_'.$this->name));
	}
	
	public function renderCell()
	{
		$href = $this->gdoHREF();
		return GWF_Template::mainPHP('cell/button.php', ['field'=>$this, 'href'=>$href])->getHTML();
	}

	public function displayHeaderLabel()
	{
		return '';
	}
}

