<?php
class GDO_Captcha extends GDOType
{
	public function blankData() {}
	public function addFormValue(GWF_Form $form, $value) {}
	
	public function __construct()
	{
		$this->name = 'captcha';
		$this->initial = GWF_Session::get('php_lock_captcha', '');
	}
	
	public function hrefCaptcha()
	{
		return "/index.php?mo=Captcha&me=Image&ajax=1";
	}
	
	public function hrefNewCaptcha()
	{
		return "/index.php?mo=Captcha&me=Image&ajax=1&new=1";
	}

	public function render()
	{
		return Module_Captcha::instance()->templatePHP('form/captcha.php', ['field' => $this]);
	}
	
	public function validate($value)
	{
		if (strtoupper($value) === GWF_Session::get('php_captcha', null))
		{
			GWF_Session::set('php_lock_captcha', $value);
			return true;
		}
		$this->onValidated();
		return $this->error('err_captcha');
	}
	
	public function onValidated()
	{
		GWF_Session::remove('php_lock_captcha');
		unset($_GET['form'][$this->name]);
		unset($_POST['form'][$this->name]);
		unset($_REQUEST['form'][$this->name]);
	}
	
}
