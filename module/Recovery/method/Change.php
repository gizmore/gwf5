<?php
final class Recovery_Change extends GWF_MethodForm
{
	/**
	 * @var GWF_UserRecovery
	 */
	private $token;
	
	public function execute()
	{
		if (!($this->token = GWF_UserRecovery::getByUIDToken(Common::getRequestString('userid'), Common::getRequestString('token'))))
		{
			return $this->error('err_token');
		}
		return parent::execute();
	}
	
	public function createForm(GWF_Form $form)
	{
		$this->title('ft_recovery_change', [$this->getSiteName()]);
		$form->addField(GDO_Password::make('new_password')->tooltip('tt_password_according_to_security_level'));
		$form->addField(GDO_Password::make('password_retype')->tooltip('tt_password_retype'));
		$form->addField(GDO_Validator::make()->validator([$this, 'validatePasswordEqual']));
		$form->addField(GDO_Submit::make());
		$form->addField(GDO_AntiCSRF::make());
	}

	public function validatePasswordEqual(GDO_Validator $gdoType)
	{
		return $this->form->getVar('new_password') === $this->form->getVar('password_retype') ? true : $this->error('err_password_retype');
	}
	
	public function formValidated(GWF_Form $form)
	{
		$user = $this->token->getUser();
		$user->saveVar('user_password', GWF_Password::create($form->getVar('new_password'))->__toString());
		$this->token->delete();
		return $this->message('msg_pass_changed');
	}
}
