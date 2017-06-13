<?php
/**
 * The holy user object.
 * 
 * @author gizmore
 * @since 1.0
 * @version 5.0
 */
final class GWF_User extends GDO
{
	###########
	### GDO ###
	###########
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
			GDO_Enum::make('user_email_fmt')->enumValues('txt', 'html')->notNull()->initial('html'),
			GDO_Email::make('user_email'),
			GDO_Password::make('user_password'),
			GDO_Date::make('user_register_time'),
			GDO_IP::make('user_register_ip'),
			GDO_Gender::make('user_gender'),
			GDO_Country::make('user_country')->emptyChoice('no_country'),
// 			GDO_Object::make('user_language_id')->klass('GWF_Language'),
// 			GDO_Object::make('user_timezone_id')->klass('GWF_Timezone'),
			GDO_Time::make('user_last_activity'),
		);
	}

	##############
	### Getter ###
	##############
	public function getID() { return $this->getVar('user_id'); }
	public function getType() { return $this->getVar('user_type'); }
	public function getName() { return $this->getVar('user_name'); }
	public function getPersonName() { return $this->getVar('user_realname'); }
	public function getGuestName() { return $this->getVar('user_guest_name'); }
	public function isGhost() { return $this->getType() === 'ghost'; }
	public function isGuest() { return $this->getType() === 'guest'; }
	public function isMember() { return $this->getType() === 'member'; }
	public function getGender() { return $this->getVar('user_gender'); }
	public function wantsTextMail() { return $this->getVar('user_mail_fmt') === 'txt'; }
	public function hasMail() { return !!$this->getMail(); }
	public function getMail() { return $this->getVar('user_email'); }
	public function getRegisterIP() { return $this->getVar('user_register_ip'); }
	public function getCountryISO() { return $this->getVar('user_country'); }
	public function getCountry() { return $this->getValue('user_country'); }

	###############
	### Display ###
	###############
	public function displayName()
	{
		if ($personName = $this->getPersonName())
		{
			return GWF_HTML::escape($personName);
		}
		elseif ($guestName = $this->getGuestName())
		{
			return GWF_HTML::escape($guestName);
		}
		else
		{
			return $this->getName();
		}
	}
	public function hasPersonName()
	{
		return !!$this->getPersonName();
	}
	
	#############
	### HREFs ###
	#############
	public function href_edit_admin() { return href('Admin', 'UserEdit', "&id={$this->getID()}"); }
	
	#############
	### Perms ###
	#############
	private $permissions;
	public function loadPermissions() { if (!$this->permissions) { $this->permissions = GWF_UserPermission::load($this); } }
	public function hasPermission(string $permission) { $this->loadPermissions(); return isset($this->permissions[$permission]); }
	public function isAdmin() { return $this->hasPermission('admin'); }
	
	##############
	### Static ###
	##############
	/**
	 * Get guest ghost user.
	 * @return GWF_User
	 */
	public static function ghost() { return self::table()->blank(['user_type' => 'ghost']); }

	/**
	 * Get current user.
	 * Not necisarilly via session!
	 * @return GWF_User
	 */
	public static function current() { return GWF_Session::user(); }

	/**
	 * @param string $name
	 * @return GWF_User
	 */
	public static function getByName(string $name) { return self::getBy('user_name', $name); }
	
	/**
	 * @param string $login
	 * @return GWF_User
	 */
	public static function getByLogin(string $login)
	{
		return self::table()->select('*')->where(sprintf('user_name=%1$s OR user_email=%1$s', self::quoteS($login)))->first()->exec()->fetchObject();
	}
}
