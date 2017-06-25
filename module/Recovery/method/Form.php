<?php
/**
 * Request Password Forgotten Token.
 * Disabled when DEBUG_MAIL is on :)
 * @author gizmore
 */
final class Recovery_Form extends GWF_MethodForm
{
	public function isEnabled() { return (!GWF_DEBUG_EMAIL); }
	
	public function createForm(GWF_Form $form)
	{
		$form->addField(GDO_Username::make('login')->tooltip('tt_recovery_login')->exists());
		if (Module_Recovery::instance()->cfgCaptcha())
		{
			$form->addField(GDO_Captcha::make());
		}
		$form->addField(GDO_AntiCSRF::make());
		$form->addField(GDO_Submit::make());
	}
	
	public function formValidated(GWF_Form $form)
	{
		$user = $form->getField('login')->gdo;
		if (!$user->hasMail())
		{
			return GWF_Error::error('err_recovery_needs_a_mail', [$user->displayName()]);
		}
		$this->sendMail($user);
		return GWF_Message::message('msg_recovery_mail_sent');
	}

	private function sendMail(GWF_User $user)
	{
		$token = GWF_UserRecovery::blank(['pw_user_id' => $user->getID()])->replace();
		$link = GWF_HTML::anchor(url('Recovery', 'Change', "&userid={$user->getID()}&token=".$token->getToken()));

		$mail = new GWF_Mail();
		$mail->setSender(GWF_BOT_EMAIL);
		$mail->setSenderName(GWF_BOT_NAME);
		$mail->setSubject(t('mail_subj_recovery', [$this->getSiteName()]));
		$body = [$user->displayName(), $this->getSiteName(), $link];
		$mail->setBody(t('mail_subj_body', $body));
		$mail->sendToUser($user);
	}
}
