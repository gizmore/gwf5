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
	
	public function getQuery()
	{
		$query = $this->getGDO()->select('perm_id, perm_name');
		$query->select('COUNT(perm_user_id) user_count')->join('LEFT JOIN gwf_userpermission ON perm_id = perm_perm_id')->uncached();
		return $query->group('perm_id,perm_name');
	}
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
}
