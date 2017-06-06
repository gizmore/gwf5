<?php
/**
 * Registration module.
 *
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
class Module_Register extends GWF_Module
{
	##############
	### Module ###
	##############
	public function getClasses() { return array('GWF_UserActivation'); }
	public function onLoadLanguage() { $this->loadLanguage('lang/register'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('captcha')->initial('0'),
			GDO_Checkbox::make('guest_signup')->initial('1'),
			GDO_Checkbox::make('email_activation')->initial('0'),
			GDO_Checkbox::make('admin_activation')->initial('0'),
			GDO_Int::make('mail_signup_count')->initial('1')->min(0)->max(100),
			GDO_Int::make('ip_signup_count')->initial('1')->min(0)->max(100),
			GDO_Duration::make('ip_signup_duration')->initial('72600')->min(0)->max(31536000),
		);
	}
	public function cfgCaptcha() { return $this->getConfigValue('captcha'); }
	public function cfgGuestSignup() { return $this->getConfigValue('guest_signup'); }
	public function cfgEmailActivation() { return $this->getConfigValue('email_activation'); }
	public function cfgAdminActivation() { return $this->getConfigValue('admin_activation'); }
	public function cfgMaxUsersPerMail() { return $this->getConfigValue('mail_signup_count'); }
	public function cfgMaxUsersPerIP() { return $this->getConfigValue('ip_signup_count'); }
	public function cfgMaxUsersPerIPTimeout() { return $this->getConfigValue('ip_signup_duration'); }
}
