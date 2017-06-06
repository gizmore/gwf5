<?php
class Register_Guest extends GWF_MethodForm
{
	public function isEnabled()
	{
		return Module_Register::instance()->cfgGuestSignup();
	}
	
	public function createForm()
	{
		$form = new GWF_Form();
		$form->title('form_title_register_guest');
		$form->addField(GDO_Username::make('user_guest_name')->required());
		if (Module_Register::instance()->cfgCaptcha())
		{
			$form->addField(GDO_Captcha::make());
		}
		return $form;
	}
}