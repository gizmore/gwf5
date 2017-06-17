<?php
class GDO_Box extends GDO_Blank
{
	public function defaultLabel() { return $this; }
	
	public function title(string $html)
	{
		$this->label = $html;
		return $this;
	}
	
	public $content;
	public function content(string $html)
	{
		$this->content = $html;
		return $this;
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/box.php', ['field'=>$this]);
	}
	
}
