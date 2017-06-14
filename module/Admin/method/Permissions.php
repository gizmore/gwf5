<?php
/**
 * Overview of modules
 * 
 * @author gizmore
 * 
 */
class Admin_Permissions extends GWF_MethodTable
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	/**
	 * @var GWF_Module[]
	 */
	private $modules;
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function onDecorateTable(GWF_Table $gwfTable)
	{
		$gwfTable->title('table_title_admin_permissions');
	}
	
	public function getResult()
	{
		return GWF_Permission::table()->select('perm_id, perm_name')->select('COUNT(perm_user_id) user_count')->
		join('JOIN gwf_userpermission ON perm_id = perm_perm_id')->group('perm_id,perm_name')->exec();
	}
	
	public function getResultCount()
	{
		return GWF_Permission::table()->countWhere("1");
	}
	
	public function getHeaders()
	{
		return array(
			GDO_Id::make('perm_edit'),
			GDO_Name::make('perm_name'),
			GDO_Int::make('user_count'),
		);
	}
}
