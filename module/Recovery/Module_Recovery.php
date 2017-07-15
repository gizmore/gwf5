<?php
/**
 * Password recovery module.
 *
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
class Module_Recovery extends GWF_Module
{
	##############
	### Module ###
	##############
	public function isCoreModule() { return true; }
	public function getClasses() { return array('GWF_UserRecovery'); }
	public function onLoadLanguage() { $this->loadLanguage('lang/recovery'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('recovery_captcha')->initial('1'),
			GDO_Duration::make('recovery_timeout')->initial(3600),
		);
	}
	public function cfgCaptcha() { return $this->getConfigValue('recovery_captcha'); }
	public function cfgTimeout() { return $this->getConfigValue('recovery_timeout'); }
	
	/**
	 * Hook register form with link to recovery.
	 * @param GWF_Form $form
	 */
	public function hookRegisterForm(GWF_Form $form)
	{
		$form->addField(GDO_Button::make('btn_recovery')->href(href('Recovery', 'Form')));
	}
	
	################
	### Top Menu ###
	################
	public function onRenderFor(GWF_Navbar $navbar)
	{
		if ($navbar->isRight() && GWF_Session::user()->isGhost())
		{
			$navbar->addField(GDO_Link::make('btn_recovery')->href($this->getMethodHREF('Form')));
		}
	}
}
