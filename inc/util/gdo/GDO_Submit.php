<?php
class GDO_Submit extends GDOType
{
	public function blankData() {}
	public function addFormValue(GWF_Form $form, $value) {}
	
	public function __construct()
	{
		$this->name = "submit";
		$this->label('btn_save');
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/submit.php', ['field'=>$this]);
	}
	
	public function renderCell()
	{
		return $this->render();
	}

	public function onValidated() { unset($_REQUEST[$this->name]); unset($_POST[$this->name]); }
}
