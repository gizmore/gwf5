<?php
class Module_Admin extends GWF_Module
{
	##############
	### Module ###
	##############
	public function isCoreModule() { return true; }
	public function getClasses() { return ['GWF_MethodAdmin']; }
	public function onLoadLanguage() { return $this->loadLanguage('lang/admin'); }

	###############
	### Navbars ###
	###############
	public function onRenderFor(GWF_Navbar $navbar)
	{
		if ( ($navbar->isRight()) && ((GWF_User::current()->isAdmin())) )
		{
			$navbar->addField(GDO_Button::make()->label('menu_admin')->href($this->getMethodHREF('Modules')));
		}
		
	}
	
}
