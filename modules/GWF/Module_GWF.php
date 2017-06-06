<?php
class Module_GWF extends GWF_Module
{
	public $module_version= "5.01";
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/gwf'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Int::make('ipp')->initial('50')->max(1000)->unsigned(),
		);
	}
	public function cfgItemsPerPage() { return $this->getConfigValue('ipp'); }

}
