<?php
class Register_Guest extends GWF_MethodForm
{
	public function isEnabled()
	{
		return Module_Register::instance()->cfgGuestSignup();
	}
	
	public function createForm(GWF_Form $form)
	{
		$form->title('form_title_register_guest');
		$form->addField(GDO_Username::make('user_guest_name')->required());
		if (Module_Register::instance()->cfgCaptcha())
		{
			$form->addField(GDO_Captcha::make());
		}
		$form->addField(GDO_Submit::make());
		$form->addField(GDO_AntiCSRF::make());
	}
	
	public function formValidated(GWF_Form $form)
	{
		$user = GWF_User::table()->blank($form->values());
		$user->setVars(array(
			'user_register_ip' => GDO_IP::current(),
			'user_register_time' => time(),
		));
		$user->insert();
		return $this->message('msg_registered_as_guest', [$user->displayName()]);
	}
}