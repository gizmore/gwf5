<?php
/**
 * Login via GWFv5 credentials form and method.
 * @author gizmore
 * @since 1.0
 */
final class Login_Form extends GWF_MethodForm
{
	public function getUserType() { return 'ghost'; }
	
	public function createForm(GWF_Form $form)
	{
		$form->addField(GDO_Username::make('login')->placeholder('plch_usename'));
		$form->addField(GDO_Password::make('user_password'));
		$form->addField(GDO_Checkbox::make('bind_ip'));
		if (Module_Login::instance()->cfgCaptcha())
		{
			$form->addField(GDO_Captcha::make());
		}
		$form->addField(GDO_Submit::make()->label('btn_login'));
		$form->addField(GDO_AntiCSRF::make());
	}
	
	public function renderPage()
	{
		switch ($this->getFormat())
		{
			case 'json': return $this->form->render();
			case 'html': default:
				$tVars = array(
					'form' => $this->form,
				);
				return $this->template('form.php', $tVars);
		}
	}
	
	public function formValidated(GWF_Form $form)
	{
		return $this->onLogin($form);
	}
	
	public function formInvalid(GWF_Form $form)
	{
		return $this->error('err_login_failed')->add($form->render());
	}
	
	public function onLogin(GWF_Form $form)
	{
		if ( (!($user = GWF_User::getByName($form->getVar('login')))) ||
		     (!($user->getValue('user_password')->validate($form->getVar('user_password')))) )
		{
			return $this->loginFailed()->add($form->render());
		}
		return $this->loginSuccess($user);
	}
	
	public function loginSuccess(GWF_User $user)
	{
		$session = GWF_Session::instance();
		$session->setValue('sess_user', $user);
		$session->setValue('sess_data', null);
		$session->save();
		return new GWF_Message('msg_authenticated', [$user->displayName()]);
	}
	
	public function loginFailed()
	{
		$ip = GDO_IP::current();
		
		$attempt = GWF_LoginAttempt::table()->blank(["la_ip"=>$ip])->insert();
		$attemptsLeft = 1;
		$bannedFor = 120;
		return $this->error('err_login_failed', [$attemptsLeft, $bannedFor]);
	}
}
