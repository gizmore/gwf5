<?php
class Register_Guest extends GWF_MethodForm
{
	public function isEnabled()
	{
		return Module_Register::instance()->cfgGuestSignup();
	}
	
	public function createForm(GWF_Form $form)
	{
		$form->addField(GDO_Username::make('user_guest_name')->required());
		if (Module_Register::instance()->cfgCaptcha())
		{
			$form->addField(GDO_Captcha::make());
		}
		$form->addField(GDO_Submit::make()->label('btn_signup_guest'));
		$form->addField(GDO_AntiCSRF::make());
	}
	
	public function formValidated(GWF_Form $form)
	{
		$user = GWF_User::table()->blank($form->values());
		$user->setVars(array(
			'user_type' => GWF_User::GUEST,
			'user_register_ip' => GDO_IP::current(),
			'user_register_time' => time(),
		));
		$user->insert();
		
		GWF_Hook::call('UserActivated', [$user]);
		
		$authResponse = GWF5::instance()->getMethod('Login', 'Form')->loginSuccess($user);
		
		return $this->message('msg_registered_as_guest', [$user->displayName()])->add($authResponse);
	}
}
