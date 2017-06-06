<?php
/**
 * Overview of modules
 * 
 * @author gizmore
 * 
 */
class Admin_Modules extends GWF_MethodTable
{
	/**
	 * @var GWF_Module[]
	 */
	private $modules;
	
	public function execute()
	{
		$this->modules = GWF5::instance()->loadModules(false);
		return $this->renderInfoBox()->add(parent::execute());
	}
	
	public function renderInfoBox()
	{
		return new GWF_Message('msg_there_are_updates');
	}
	
	public function onDecorateTable(GWF_Table $gwfTable)
	{
		$gwfTable->title('table_title_admin_modules');
	}
	
	public function getResult()
	{
		return new GDOArrayResult($this->modules);
	}
	
	public function getResultCount()
	{
		return count($this->modules);
	}
	
	public function getHeaders()
	{
		return array(
			GDO_Id::make('module_id'),
			GDO_Checkbox::make('module_enabled'),
			GDO_Name::make('module_name'),
			GDO_Decimal::make('module_version'),
			GDO_Decimal::make('fs_version'),
			GDO_Blank::make('install_btn'),
			GDO_Blank::make('config_btn'),
			GDO_Blank::make('admin_btn'),
		);
	}
}
