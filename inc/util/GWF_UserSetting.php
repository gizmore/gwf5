<?php
final class GWF_UserSetting extends GDO
{
	/**
	 * @var GDOType[]
	 */
	private static $settings;
	public static function register(GDOType $gdoType)
	{
		self::$settings[$gdoType->name] = $gdoType;
	}
	
	###########
	### GDO ###
	###########
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_User::make('uset_user')->primary(),
			GDO_Name::make('uset_name')->primary(),
			GDO_Object::make('uset_module')->klass('GWF_Module'),
			GDO_String::make('uset_value')->notNull(),
		);
	}
	
	public static function load(GWF_User $user)
	{
		return self::table()->select('uset_name, uset_value')->where("uset_user={$user->getID()}")->exec()->fetchAllArray2dPair();
	}
	
	public static function get($key)
	{
		return self::userGet(GWF_User::current(), $key);
	}

	public static function userGet(GWF_User $user, $key)
	{
		if (!($settings = $user->tempGet('gwf_setting')))
		{
			$settings = self::load($user);
			$user->tempSet('gwf_setting', $settings);
		}
		return self::$settings[$key]->value(@$settings[$key]);
	}

	public static function set(string $key, $value)
	{
		return self::userSet(GWF_User::current(), $key, $value);
	}

	public static function userSet(GWF_User $user=null, string $key, $value)
	{
		return self::moduleUserSet(null, $user, $key, $value);
	}
	
	public static function moduleUserSet(string $moduleId=null, GWF_User $user=null, string $key, $value)
	{
		$userid = $user ? $user->getID() : null;
		if ($value === null)
		{
			self::table()->deleteWhere("uset_user=$userid AND uset_name=".quote($key))->exec();
		}
		else 
		{
			self::blank(array(
				'uset_user' => $userid,
				'uset_name' => $key,
				'uset_module' => $moduleId,
				'uset_value' => $value
			))->replace();
		}
		$user->unset('gwf_setting');
	}
	
}