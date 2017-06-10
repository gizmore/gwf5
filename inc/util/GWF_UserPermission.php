<?php
final class GWF_UserPermission extends GDO
{
	public function gdoColumns()
	{
		return array(
			GDO_Object::make('perm_user_id')->klass('GWF_User')->primary(),
			GDO_Object::make('perm_perm_id')->klass('GWF_Permission')->primary(),
		);
	}
	
	public static function load(GWF_User $user)
	{
		if (!$user->isPersisted())
		{
			return [];
		}
		return self::table()->select('perm_name, 1')->join("JOIN gwf_permission on perm_perm_id = perm_id")->where("perm_user_id={$user->getID()}")->exec()->fetchAllArray2d();
	}
}