<?php
final class Module_Login extends GWF_Module
{
	##############
	### Module ###
	##############
	public $version = "5.00";

	public function getClasses() { return array('GWF_LoginAttempt'); }
	public function onLoadLanguage() { return $this->loadLanguage('lang/login'); }
	public function isCoreModule() { return true; }
	
	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('login_captcha')->initial('0'),
			GDO_Checkbox::make('login_history')->initial('1'),
			GDO_Duration::make('login_timeout')->initial('600')->min(10)->max(72600),
			GDO_Int::make('login_tries')->initial('3')->min(1)->max(100),
		);
	}
	public function cfgCaptcha() { return $this->getConfigValue('login_captcha'); }
	public function cfgHistory() { return $this->getConfigValue('login_history'); }
	public function cfgFailureTimeout() { return $this->getConfigValue('login_timeout'); }
	public function cfgFailureAttempts() { return $this->getConfigValue('login_tries'); }
	
	##############
	### Navbar ###
	##############
	public function onRenderFor(GWF_Navbar $navbar)
	{
		if ($navbar->isRight())
		{
			if (GWF_Session::user()->isGhost())
			{
				$navbar->addField(GDO_Button::make('signin')->label('btn_login')->href($this->getMethodHREF('Form')));
			}
			else
			{
				$navbar->addField(GDO_Button::make('signout')->label('btn_logout')->href($this->getMethodHREF('Logout')));
			}
		}
	}
}
