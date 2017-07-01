<?php
class Admin_Permission extends GWF_MethodQueryTable
{
	private $permission;
	
	public function getPermission() { return 'staff'; }
	
	public function init()
	{
		$this->permission = GWF_Permission::table()->find(Common::getRequestString('permission'));
	}
	
	public function getHeaders()
	{
		$users = GWF_User::table();
		$perms = GWF_UserPermission::table();
		return array(
			GDO_Count::make('count'),
			GDO_User::make('perm_user_id'),
			GDO_CreatedAt::make('perm_created_at'),
			GDO_CreatedBy::make('perm_created_by'),
			GDO_Button::make('perm_revoke'),
		);
	}
	
	public function onDecorateTable(GDO_Table $table)
	{
		$table->fetchAs(GWF_User::table());
	}
	
	public function getQuery()
	{
		return GWF_UserPermission::table()->select('gwf_user.*, gwf_userpermission.*')->joinObject('perm_user_id')->where('perm_perm_id='.$this->permission->getID())->uncached();
	}
	
	
}
