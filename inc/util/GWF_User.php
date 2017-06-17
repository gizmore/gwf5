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
	const GHOST = 'ghost';
	const GUEST = 'guest';
	const MEMBER = 'member';
	
	########################
	### Custom temp vars ###
	########################
	/**
	 * @var mixed[string]
	 */
	private $temp;
	public function get(string $key) { return @$this->temp[$key]; }
	public function set(string $key, $value) { if (!$this->temp) $this->temp = []; $this->temp[$key] = $value; }
	public function unset(string $key) { unset($this->temp[$key]); }
	
	###########
	### GDO ###
	###########
	public function gdoTableName() { return 'gwf_user'; }
	public function gdoDependencies() { return array('GWF_Country', 'GWF_Language'); }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('user_id'),
			GDO_Username::make('user_name')->unique(),
			GDO_Username::make('user_guest_name'),
			GDO_Int::make('user_level')->unsigned()->notNull()->initial('0')->label('level'),
			GDO_Int::make('user_credits')->unsigned()->notNull()->initial('0')->label('credits'),
			GDO_PersonName::make('user_real_name'),
			GDO_Enum::make('user_type')->enumValues(self::GHOST, self::GUEST, self::MEMBER)->label('type')->notNull()->initial(self::GUEST),
			GDO_EmailFormat::make('user_email_fmt'),
			GDO_Email::make('user_email'),
			GDO_Password::make('user_password'),
			GDO_DateTime::make('user_register_time'),
			GDO_IP::make('user_register_ip'),
			GDO_Date::make('user_birthdate')->label('birthdate'),
			GDO_Gender::make('user_gender'),
			GDO_Country::make('user_country')->emptyChoice('no_country'),
			GDO_Language::make('user_language')->initial('en')->notNull(),
			GDO_Checkbox::make('user_hide_online')->initial('0'),
			GDO_Checkbox::make('user_want_adult')->initial('0'),
			GDO_Checkbox::make('user_allow_email')->initial('0'),
			GDO_Checkbox::make('user_show_birthdays')->initial('0'),
			GDO_DeletedAt::make('user_deleted_at'),
			GDO_UpdatedAt::make('user_last_activity'),
		);
	}

	##############
	### Getter ###
	##############
	public function getID() { return $this->getVar('user_id'); }
	public function getType() { return $this->getVar('user_type'); }
	public function getName() { return $this->getVar('user_name'); }
	public function getGuestName() { return $this->getVar('user_guest_name'); }
	public function getRealName() { return $this->getVar('user_real_name'); }
	public function isGhost() { return $this->getType() === 'ghost'; }
	public function isGuest() { return $this->getType() === 'guest'; }
	public function isMember() { return $this->getType() === 'member'; }
	public function getGender() { return $this->getVar('user_gender'); }
	public function wantsTextMail() { return $this->getVar('user_mail_fmt') === 'txt'; }
	public function hasMail() { return !!$this->getMail(); }
	public function getMail() { return $this->getVar('user_email'); }
	public function getRegisterIP() { return $this->getVar('user_register_ip'); }
	public function getLangISO() { return $this->getVar('user_language'); }
	public function getLanguage() { return $this->getValue('user_language'); }
	public function getCountryISO() { return $this->getVar('user_country'); }
	public function getCountry() { return $this->getValue('user_country'); }
	public function hideOnline() { return $this->getVar('user_hide_online') === '1'; }
	public function isDeleted() { return $this->getVar('user_deleted_at') !== null; }
	public function getBirthdate() { return $this->getVar('user_birthdate'); }
	public function getAge() { return GWF_Time::getAge($this->getBirthdate()); }

	###############
	### Display ###
	###############
	public function displayName()
	{
		if ($realName = $this->getRealName())
		{
			return GWF_HTML::escape($realName);
		}
		elseif ($guestName = $this->getGuestName())
		{
			return $guestName;
		}
		else
		{
			return $this->getName();
		}
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
	
	/**
	 * Get all admins
	 * @return GWF_User[]
	 */
	public static function admins()
	{
		return self::withPermission('admin');
	}
	
	/**
	 * Get all users with a permission.
	 * @param string $permission
	 * @return GWF_User[]
	 */
	public static function withPermission(string $permission)
	{
		return GWF_UserPermission::table()->select('gwf_user.*')->
			joinObject('perm_user_id')->joinObject('perm_perm_id')->
			where("perm_name=".quote($permission))->
			debug()->exec()->
			fetchAllObjectsAs(self::table());
	}
}
