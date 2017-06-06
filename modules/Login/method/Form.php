<?php
/**
 * Login via GWFv5 credentials form and method.
 * @author gizmore
 * @since 1.0
 */
final class Login_Form extends GWF_MethodForm
{
	public function createForm()
	{
		$form = new GWF_Form();
		$form->addField(GDO_Username::make('login'));
		$form->addField(GDO_Password::make('user_password'));
		$form->addField(GDO_Checkbox::make('bind_ip'));
		if (Module_Login::instance()->cfgCaptcha())
		{
			$form->addField(GDO_Captcha::make('captcha'));
		}
		$form->addField(GDO_Submit::make('btn_login'));
		return $form;
	}
	
	public function formValidated(GWF_Form $form)
	{
		return $this->onLogin($form->getVar('login'), $form->getVar('password'), $form->getVar('bind_ip'));
	}
	
	public function formInvalid(GWF_Form $form)
	{
		return $this->error('err_login_failed')->add($form->render());
	}
	
	public function onLogin(string $login, string $password, $ip)
	{
		if ( (!($user = GWF_User::getByName($login))) ||
		     (!($user->getGDOVar('user_password')->validate($password))) )
		{
			return $this->error('err_login');
		}
		return $this->loginSuccess($user);
	}
	
	public function loginSuccess(GWF_User $user)
	{
		return new GWF_Message('msg_authenticated');
	}

}
