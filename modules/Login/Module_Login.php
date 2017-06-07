<?php
final class Module_Login extends GWF_Module
{
	##############
	### Module ###
	##############
	public $version = "5.00";

	public function getClasses() { return array('GWF_LoginAttempt'); }
	public function onLoadLanguage() { return $this->loadLanguage('lang/login'); }
	
	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('login_captcha')->initial('0'),
			GDO_Checkbox::make('login_history')->initial('1'),
			GDO_Int::make('login_tries')->initial('3')->min(1)->max(100),
			GDO_Duration::make('login_timeout')->initial('600')->min(10)->max(72600),
		);
	}
	public function cfgCaptcha() { return $this->getConfigValue('login_captcha'); }
	public function cfgHistory() { return $this->getConfigValue('login_history'); }
	
	################
	### Top Menu ###
	################
	public function onLoadTopMenu(GWF_TopMenu $topMenu)
	{
		if (GWF_Session::user()->isGhost())
		{
			$topMenu->addField(GDO_Button::make('signup')->label('btn_register')->href($this->getMethodHREF('Form')));
			$topMenu->addField(GDO_Button::make('signin')->label('btn_login')->href($this->getMethodHREF('Form')));
		}
		else
		{
			$topMenu->addField(GDO_Button::make('signout')->label('btn_logout')->href($this->getMethodHREF('Logout')));
		}
	}
}
