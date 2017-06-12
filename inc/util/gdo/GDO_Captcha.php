<?php
class GDO_Captcha extends GDOType
{
	public function blankData() {}
	public function addFormValue(GWF_Form $form, $value) {}
	
	public function __construct()
	{
		$this->name = 'captcha';
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/captcha.php', ['field' => $this]);
	}
	
	public function validate($value)
	{
		return true;
		
	}
	
}
