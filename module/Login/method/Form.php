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
		$form->addField(GDO_Username::make('login')->tooltip('tt_login')->notNull());
		$form->addField(GDO_Password::make('password')->notNull());
		$form->addField(GDO_Checkbox::make('bind_ip'));
		if (Module_Login::instance()->cfgCaptcha())
		{
			$form->addField(GDO_Captcha::make());
		}
		$form->addField(GDO_Submit::make()->label('btn_login'));
		$form->addField(GDO_AntiCSRF::make());
		$form->addField(GDO_Button::make('btn_recovery')->href(href('Recovery', 'Form')));
	}
	
	public function formValidated(GWF_Form $form)
	{
		return $this->onLogin($form->getVar('login'), $form->getVar('password'), $form->getVar('bind_ip'));
	}
	
	public function onLogin(string $login, string $password, bool $bindIP=false)
	{
		if ($response = $this->banCheck())
		{
			return $response->add($this->renderPage());
		}
		
		if ( (!($user = GWF_User::getByLogin($login))) ||
		     (!($user->getValue('user_password')->validate($password))) )
		{
			return $this->loginFailed($user)->add($this->getForm()->render());
		}
		return $this->loginSuccess($user, $bindIP);
	}
	
	/**
	 * @param GWF_User $user
	 * @param bool $bindIP
	 * @return GWF_Message
	 */
	public function loginSuccess(GWF_User $user, bool $bindIP=false)
	{
		$session = GWF_Session::instance();
		$session->setValue('sess_user', $user);
		$session->setValue('sess_data', null);
		$ip = $bindIP ? GDO_IP::current() : null;
		$session->setValue('sess_ip', $ip);
		$session->save();
		$user->tempReset();
		GWF_Hook::call('UserAuthenticated', $user);
		return new GWF_Message('msg_authenticated', [$user->displayName()]);
	}

	################
	### Security ###
	################
	private function banCut() { return time() - $this->banTimeout(); }
	private function banTimeout() { return Module_Login::instance()->cfgFailureTimeout(); }
	private function maxAttempts() { return Module_Login::instance()->cfgFailureAttempts(); }
	
	public function loginFailed($user)
	{
		# Insert attempt
		$ip = GDO_IP::current();
		$userid = $user ? $user->getID() : null;
		$attempt = GWF_LoginAttempt::blank(["la_ip"=>$ip, 'la_user_id'=>$userid])->insert();
		
		# Count victim attack. If only 1, we got a new threat and mail it.
		if ($user)
		{
			$this->checkSecurityThreat($user);
		}
		
		# Count attacker attempts
		list($mintime, $attempts) = $this->banData();
		$bannedFor = $mintime - $this->banCut();
		$attemptsLeft = $this->maxAttempts() - $attempts;
		return $this->error('err_login_failed', [$attemptsLeft, GWF_Time::humanDuration($bannedFor)]);
	}
	
	private function banCheck()
	{
		list($mintime, $count) = $this->banData();
		if ($count >= $this->maxAttempts())
		{
			$bannedFor = $mintime - $this->banCut();
			return GWF_Error::error('err_login_ban', [GWF_Time::humanDuration($bannedFor)]);
		}
	}
	
	private function banData()
	{
		$table = GWF_LoginAttempt::table();
		$condition = sprintf('la_ip=%s AND la_time > FROM_UNIXTIME(%d)', GDO::quoteS(GDO_IP::current()), $this->banCut());
		return $table->select('UNIX_TIMESTAMP(MIN(la_time)), COUNT(*)')->where($condition)->exec()->fetchRow();
	}
	
	private function checkSecurityThreat(GWF_User $user)
	{
		$table = GWF_LoginAttempt::table();
		$condition = sprintf('la_user_id=%s AND la_time > FROM_UNIXTIME(%d)', $user->getID(), $this->banCut());
		if (1 === ($attempts = $table->countWhere($condition)))
		{
			$this->mailSecurityThreat($user);
		}
	}
	
	private function mailSecurityThreat(GWF_User $user)
	{
		$mail = new GWF_Mail();
		$mail->setSender(GWF_BOT_EMAIL);
		$mail->setSubject(t('mail_subj_login_threat', [$this->getSiteName()]));
		$args = [$user->displayName(), $this->getSiteName(), GDO_IP::current()];
		$mail->setBody(t('mail_body_login_threat', $args));
		$mail->sendToUser($user);
	}
}
