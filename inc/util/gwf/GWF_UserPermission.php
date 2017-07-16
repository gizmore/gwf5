<?php
final class GWF_UserPermission extends GDO
{
	public function gdoCached() { return false; }
	
	public function gdoDependencies() { return ['GWF_User', 'GWF_Permission']; }
	
	public function gdoColumns()
	{
		return array(
			GDO_User::make('perm_user_id')->primary()->index(),
			GDO_Permission::make('perm_perm_id')->primary(),
			GDO_CreatedAt::make('perm_created_at'),
			GDO_CreatedBy::make('perm_created_by'),
		);
	}
	
	/**
	 * @return GWF_User
	 */
	public function getUser() { return $this->getValue('perm_user_id'); }
	public function getUserID() { return $this->getVar('perm_user_id'); }
	
	/**
	 * @return GWF_Permission
	 */
	public function getPermission() { return $this->getValue('perm_perm_id'); }
	public function getPermissionID() { return $this->getVar('perm_perm_id'); }

	##############
	### Static ###
	##############
	public static function load(GWF_User $user)
	{
		if (!$user->isPersisted())
		{
			return [];
		}
		return self::table()->select('perm_name, 1')->join("JOIN gwf_permission on perm_perm_id = perm_id")->where("perm_user_id={$user->getID()}")->exec()->fetchAllArray2dPair();
	}
	
	public static function grantPermission(GWF_User $user, GWF_Permission $permission)
	{
		return self::blank(array('perm_user_id' => $user->getID(), 'perm_perm_id' => $permission->getID()))->replace();
	}
	
	public static function grant(GWF_User $user, string $permission)
	{
		return self::grantPermission($user, GWF_Permission::getByName($permission));
	}
}