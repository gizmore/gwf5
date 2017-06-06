<?php
class Admin_Configure extends GWF_MethodForm
{
	/**
	 * @var GWF_Module
	 */
	private $configModule;
	
	public function execute()
	{
		GWF5::instance()->loadModules(false);
		if (!($this->configModule = GWF5::instance()->getModule(Common::getRequestString('module'))))
		{
			return $this->error('err_module')->add($this->execMethod('Modules'));
		}
		return parent::execute();
	}
	
	public function createForm()
	{
		$mod = $this->configModule;
		$form = new GWF_Form();
		$form->title('form_title_configure', [$this->configModule->display('module_name')]);
		$form->addField(GDO_Name::make('module_name')->writable(false));
		$form->addField(GDO_Path::make('module_path')->writable(false)->value($mod->filePath()));
		$form->addField(GDO_Version::make('module_version')->writable(false));
		$form->addField(GDO_Version::make('version_available')->writable(false)->value($mod->module_version));
		if ($config = $mod->getConfig())
		{
			$form->addField(GDO_Divider::make('div1')->label('form_div_config_vars'));
			foreach ($config as $gdoType)
			{
				$form->addField($gdoType->value($mod->getConfigValue($gdoType->name)));
			}
			$form->addField(GDO_Divider::make('div2'));
		}
		$form->addField(GDO_Submit::make()->label('btn_save'));
		$form->addField(GDO_AntiCSRF::make());
		# Prefill with module
		return $form->withGDOValuesFrom($this->configModule);
	}

	public function formValidated(GWF_Form $form)
	{
		$mod = $this->configModule;
		
		# Update config
		$info = [];
		foreach ($form->getFields() as $gdoType)
		{
			if ($gdoType->hasChanged())
			{
				GWF_ModuleVar::createModuleVar($mod, $gdoType);
				$info[] = GWF_Trans::t('msg_modulevar_changed', [$gdoType->displayLabel(), htmlspecialchars($gdoType->oldValue), htmlspecialchars($gdoType->value)]);
			}
		}
		
		# Announce
		return $this->message('msg_module_saved', [implode('<br/>', $info)])->add($form->render());
	}
	
}