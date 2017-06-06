<?php
class GDO_Submit extends GDOType
{
	public function blankData() {}
	public function addFormValue(GWF_Form $form, $value) {}
	
	public function __construct()
	{
		$this->name = "submit";
	}
	
	public function render()
	{
		return GWF_Template::templateMain('form/submit.php', ['field'=>$this]);
	}
}
