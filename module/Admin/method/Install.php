<?php
class Admin_Install extends GWF_MethodForm
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	/**
	 * @var GWF_Module
	 */
	private $configModule;
	
	public function execute()
	{
		GWF5::instance()->loadModules(false);
		if ($this->configModule = GWF5::instance()->getModule(Common::getRequestString('module')))
		{
			$buttons = ['install', 'uninstall', 'wipe', 'enable', 'disable'];
			foreach ($buttons as $button)
			{
				if (isset($_POST[$button]))
				{
					return $this->executeButton($button)->add($this->renderPage());
				}
			}
			return $this->renderPage();
		}
	}
	
	public function createForm(GWF_Form $form)
	{
		$this->title('ft_admin_install', [$this->getSiteName(), $this->configModule->getName()]);
		$form->addField(GDO_Submit::make('install')->label('btn_install'));
		$form->addField(GDO_Submit::make('wipe')->label('btn_module_wipe'));
		$form->addField(GDO_Submit::make('uninstall')->label('btn_module_uninstall'));
		$form->addField(GDO_Submit::make('enable')->label('btn_enable'));
		$form->addField(GDO_Submit::make('disable')->label('btn_disable'));
		$form->addField(GDO_AntiCSRF::make());
	}
	
	public function executeButton(string $button)
	{
		$form = $this->getForm();
		if (!$form->validate())
		{
			return parent::formInvalid($form);
		}
		GDOCache::unset('gwf_modules');
		return call_user_func(array($this, "execute_$button"));
	}
	
	public function execute_install()
	{
		GWF_ModuleInstall::installModule($this->configModule);
		return GWF_Message::message('msg_module_installed', [$this->configModule->getName()]);
	}
	
	public function execute_uninstall()
	{
		GWF_ModuleInstall::dropModule($this->configModule);
		return GWF_Message::message('msg_module_uninstalled', [$this->configModule->getName()]);
	}
	
	public function execute_wipe()
	{
		GWF_ModuleInstall::dropModule($this->configModule);
		GWF_ModuleInstall::installModule($this->configModule);
		return GWF_Message::message('msg_module_wiped', [$this->configModule->getName()]);
	}
	
	public function execute_enable()
	{
		$this->configModule->saveVar('module_enabled', '1');
		return GWF_Message::message('msg_module_enabled', [$this->configModule->getName()]);
	}

	public function execute_disable()
	{
		$this->configModule->saveVar('module_enabled', '0');
		return GWF_Message::message('msg_module_disabled', [$this->configModule->getName()]);
	}
	
}
