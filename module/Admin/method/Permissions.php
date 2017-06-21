<?php
/**
 * Overview of modules
 * 
 * @author gizmore
 * 
 */
class Admin_Permissions extends GWF_MethodQueryTable
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	public function getGDO() { return GWF_Permission::table(); }
	
	public function getHeaders()
	{
		return array(
			GDO_Count::make(),
			GDO_Button::make('edit'),
			GDO_Name::make('perm_name'),
			GDO_Int::make('user_count')->virtual(),
		);
	}
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function getResult()
	{
		return $this->filterQuery(GWF_Permission::table()->select('perm_id, perm_name')->select('COUNT(perm_user_id) user_count')->
		join('LEFT JOIN gwf_userpermission ON perm_id = perm_perm_id')->group('perm_id,perm_name'))->exec();
	}
	
	public function getResultCount()
	{
		return GWF_Permission::table()->countWhere('1');
	}
	
}
