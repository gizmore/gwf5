<?php
final class GWF_User extends GDO
{
	public function gdoTableName() { return 'gwf_user'; }
	public function gdoDependencies() { return array('GWF_Country', 'GWF_Language', 'GWF_Timezone'); }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('user_id'),
			GDO_Username::make('user_name')->unique(),
			GDO_Username::make('user_guest_name'),
			GDO_Int::make('user_level')->unsigned()->notNull()->initial('0'),
			GDO_Int::make('user_credits')->unsigned()->notNull()->initial('0'),
			GDO_PersonName::make('user_realname'),
			GDO_Enum::make('user_type')->enumValues('ghost', 'guest', 'member')->notNull()->initial('guest'),
			GDO_Email::make('user_email'),
			GDO_Password::make('user_password'),
			GDO_Date::make('user_register_time'),
			GDO_IP::make('user_register_ip'),
			GDO_Gender::make('user_gender'),
			GDO_Object::make('user_country_id')->klass('GWF_Country'),
			GDO_Object::make('user_language_id')->klass('GWF_Language'),
			GDO_Object::make('user_timezone_id')->klass('GWF_Timezone'),
// 			GDO_EditedAt::make('user_edited_at'),
// 			GDO_EditedBy::make('user_edited_by'),
// 			GDO_DeletedAt::make('user_deleted_at'),
// 			GDO_DeletedBy::make('user_deleted_by'),
			GDO_Time::make('user_last_activity'),
		);
	}
	##############
	### Getter ###
	##############
	public function getID() { return $this->getVar('user_id'); }
	
	### ###
	public function displayName() { return $this->display('user_name'); }
	
	public static function getByName(string $name)
	{
		return self::getBy('user_name', $name);
	}
	
	##############
	### Static ###
	##############
	public static function current()
	{
		if ( (!($session = GWF_Session::instance())) ||
		     (!($user = $session->getUser())) )
		{
			$user = self::ghost();
		}
		return $user;
	}
	
	public static function ghost()
	{
		return self::table()->blank(['user_type' => 'ghost']);
	}
	
}
