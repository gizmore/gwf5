<?php
/**
 * Overview of modules
 * 
 * @author gizmore
 * 
 */
class Admin_Modules extends GWF_MethodTable
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	/**
	 * @var GWF_Module[]
	 */
	private $modules;
	
	public function execute()
	{
		$this->modules = GWF5::instance()->loadModules(false);
		return $this->renderNavBar()->add($this->renderInfoBox()->add(parent::execute()));
	}
	
	public function renderInfoBox()
	{
		return new GWF_Message('msg_there_are_updates');
	}
	
// 	public function onDecorateTable(GWF_Table $gwfTable)
// 	{
// 		$gwfTable->title('table_title_admin_modules');
// 	}
	
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
// 			GDO_DeleteButton::make(),
			GDO_Id::make('module_id')->label('id'),
			GDO_Sort::make('module_priority')->label('sort'),
			GDO_Checkbox::make('module_enabled')->label('enabled'),
			GDO_Name::make('module_name')->label('name'),
			GDO_Decimal::make('module_version')->label('version_db'),
			GDO_Decimal::make('fs_version')->label('version_fs'),
			GDO_Button::make('install_module')->label('btn_install'),
			GDO_Button::make('configure_module')->label('btn_configure'),
			GDO_Button::make('administrate_module')->label('btn_admin'),
		);
	}
}
