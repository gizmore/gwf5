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
	
	public function isPaginated() { return false; }
	
	public function getPermission() { return 'staff'; }
	
	/**
	 * @var GWF_Module[]
	 */
	private $modules;
	
	public function execute()
	{
		$this->modules = GWF5::instance()->loadModules(false);
		$this->modules = GWF_Module::table()->sort($this->modules, 'module_sort');
		$response = parent::execute();
		$navbar = $this->renderNavBar();
		$info = $this->renderInfoBox();
		return $navbar->add($info)->add($response);
	}
	
	public function renderInfoBox()
	{
		return GDO_Box::make()->content(t('msg_there_are_updates'))->render();
	}
	
	public function getResult()
	{
		return new GDOArrayResult($this->modules, GWF_Module::table());
	}
	
	public function getResultCount()
	{
		return count($this->modules);
	}
	
	public function getHeaders()
	{
		$this->module->includeClass('GDO_ModuleVersionFS');
		return array(
// 			GDO_DeleteButton::make(),
			GDO_Id::make('module_id')->label('id'),
			GDO_Int::make('module_priority')->unsigned()->label('priority'),
			GDO_Checkbox::make('module_enabled')->label('enabled'),
			GDO_Name::make('module_name')->label('name'),
			GDO_Decimal::make('module_version')->label('version_db'),
			GDO_ModuleVersionFS::make('module_version_fs')->label('version_fs'),
// 			GDO_Button::make('install_module')->label('btn_install'),
			GDO_Button::make('configure_module')->label('btn_configure'),
			GDO_Button::make('administrate_module')->label('btn_admin'),
		);
	}
	
	public function createTable(GDO_Table $table)
	{
		$table->sortable(href('Admin', 'ModuleSort'));
	}
	
}
