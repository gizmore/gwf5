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
	public function render()
	{
		return GWF_Template::templateMain('form/button.php', ['field'=>$this]);
	}
}

