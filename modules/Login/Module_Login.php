<?php
final class Module_Login extends GWF_Module
{
	##############
	### Module ###
	##############
	public $version = "5.00";

	public function getClasses() { return array('GWF_LoginAttempt'); }
	
	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('login_captcha')->initial('0'),
			GDO_Checkbox::make('login_history')->initial('1'),
		);
	}
	public function cfgCaptcha() { return $this->getConfigValue('login_captcha'); }
	public function cfgHistory() { return $this->getConfigValue('login_history'); }
	
	
}