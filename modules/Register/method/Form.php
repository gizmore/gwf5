<?php
class Register_Form extends GWF_MethodForm
{
	public function title()
	{
		return GWF_Trans::t('page_title_register');
	}

	public function createForm()
	{
		$module = Module_Register::instance();
		$form = new GWF_Form();
		$form->title('form_title_register', [GWF_SITENAME]);
		$form->addField(GDO_Validator::make('validator1')->validator(array($this, 'validateUniqueIP')));
		$form->addField(GDO_Username::make('user_name')->required()->validator(array($this, 'validateUniqueUsername')));
		$form->addField(GDO_Password::make('user_password')->required());
		if ($module->cfgEmailActivation())
		{
			$form->addField(GDO_Email::make('user_email')->required()->validator(array($this, 'validateUniqueEmail')));
		}
		if ($module->cfgCaptcha())
		{
			$form->addField(GDO_Captcha::make('captcha'));
		}
		$form->addField(GDO_Submit::make()->label('btn_register'));
		$form->addField(GDO_AntiCSRF::make());
		return $form;
	}
	
	function validateUniqueIP(GDO_Validator $field)
	{
		$ip = GDO::quoteS(GDO_IP::current());
		$cut = time() - Module_Register::instance()->cfgMaxUsersPerIPTimeout();
		$count = GWF_User::table()->countWhere("user_register_ip={$ip} AND user_register_time>{$cut}");
		$max = Module_Register::instance()->cfgMaxUsersPerIP();
		return $count < $max ? true :  $field->error('err_email_signup_max_reached', [$max]);
	}
	
	public function validateUniqueUsername(GDO_Username $username)
	{
		$existing = GWF_User::table()->getByName($username->formValue());
		return $existing ? $username->error('err_username_taken') : true;
	}

	public function validateUniqueEmail(GDO_Email $email)
	{
		$count = GWF_User::table()->count()->where("user_email={$email->quotedValue()}");
		$max = Module_Register::instance()->cfgMaxUsersPerMail();
		return $count < $max ? true :  $email->error('err_email_signup_max_reached', [$max]);
	}
	
	public function formInvalid(GWF_Form $form)
	{
		return $this->error('err_register')->add($form->render());
	}
	
	public function formValidated(GWF_Form $form)
	{
		return $this->onRegister($form);
	}
	
	################
	### Register ###
	################
	public function onRegister(GWF_Form $form)
	{
		$module = Module_Register::instance();
		
		$activation = GWF_UserActivation::table()->blank($form->values());
		$activation->setVar('user_register_ip', GDO_IP::current());
		$activation->save();
		
		if ($module->cfgEmailActivation())
		{
			return $this->onEmailActivation($activation);
		}
		else
		{
			$url = $module->getMethod('Activate')->href("&id={$activation->getID()}&token={$activation->getToken()}");
			return new GWF_Message('msg_activating', [$url]);
		}
	}
	
	public function onEmailActivation(GWF_UserActivation $activation)
	{
		
	}
	
}