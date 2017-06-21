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
			GDO_User::make('uset_id')->primary(),
			GDO_Name::make('uset_name')->primary(),
			GDO_Object::make('uset_module')->klass('GWF_Module'),
			GDO_String::make('uset_value')->notNull(),
		);
	}
	
	public static function load(GWF_User $user)
	{
		return self::table()->select('uset_name, uset_value')->exec()->fetchAllArray2dPair();
	}
	
	public static function userGet(GWF_User $user, $key)
	{
		if (!($settings = $user->get('gwf_setting')))
		{
			$settings = self::load($user);
			$user->set('gwf_setting', $settings);
		}
		return self::$settings[$key]->value(@$settings[$key])->getGDOValue();
	}
	
	public static function get($key)
	{
		return self::userGet(GWF_User::current(), $key);
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
		self::blank(['uset_name'=>$key, 'uset_module'=>$moduleId, 'uset_value'=>$value]);
	}
	
}