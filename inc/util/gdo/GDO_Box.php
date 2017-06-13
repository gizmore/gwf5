<?php
class GDO_Box extends GDO_Blank
{
	public function title(string $html)
	{
		return $this->label = $html;
		return $this;
	}
	
	public $content;
	public function content(string $html)
	{
		return $this->content = $html;
		return $this;
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/box.php', ['field'=>$this]);
	}
	
}
