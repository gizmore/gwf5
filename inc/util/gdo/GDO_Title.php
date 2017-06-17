<?php
class GDO_Headline extends GDOType
{
	public function defaultLabel() { return $this->label('title'); }
	
	public function render()
	{
		return GWF_Template::mainPHP('form/headline.php', ['field'=>$this]);
	}
}
