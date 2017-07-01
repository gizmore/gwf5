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
	const BOT = 'bot';
	const GHOST = 'ghost';
	const GUEST = 'guest';
	const MEMBER = 'member';
	
	###########
	### GDO ###
	###########
	public function gdoTableName() { return 'gwf_user'; }
	public function gdoDependencies() { return array('GWF_Country', 'GWF_Language'); }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('user_id'),
			GDO_Enum::make('user_type')->enumValues(self::GHOST, self::BOT, self::GUEST, self::MEMBER)->label('type')->notNull()->initial(self::GUEST),
			GDO_Username::make('user_name')->unique(),
			GDO_Username::make('user_guest_name')->label('guestname'),
			GDO_PersonName::make('user_real_name'),
			GDO_Email::make('user_email'),
			GDO_Int::make('user_level')->unsigned()->notNull()->initial('0')->label('level'),
			GDO_Int::make('user_credits')->unsigned()->notNull()->initial('0')->label('credits'),
			GDO_EmailFormat::make('user_email_fmt'),
			GDO_Gender::make('user_gender'),
			GDO_Date::make('user_birthdate')->label('birtdate'),
			GDO_Country::make('user_country')->emptyChoice('no_country'),
			GDO_Language::make('user_language')->initial('en')->notNull(),
			GDO_Password::make('user_password'),
			GDO_DeletedAt::make('user_deleted_at'),
			GDO_UpdatedAt::make('user_last_activity'),
			GDO_CreatedAt::make('user_register_time')->label('registered_at'),
			GDO_IP::make('user_register_ip'),
		);
	}

	##############
	### Getter ###
	##############
	public function getID() { return $this->getVar('user_id'); }
	public function getType() { return $this->getVar('user_type'); }
	
	
	public function getName() { return $this->getVar('user_name'); }
	public function getUserName() { return ($name = $this->getGuestName()) ? $name : $this->getName(); }
	public function getRealName() { return $this->getVar('user_real_name'); }
	public function getGuestName() { return $this->getVar('user_guest_name'); }
	
	public function isBot() { return $this->getType() === self::BOT; }
	public function isGhost() { return $this->getType() === self::GHOST; }
	public function isGuest() { return $this->getType() === self::GUEST; }
	public function isMember() { return $this->getType() === self::MEMBER; }
	
	public function getLevel() { return $this->getVar('user_level'); }
	public function isAuthenticated() { return $this->isGuest() || $this->isMember(); }
	
	public function hasMail() { return !!$this->getMail(); }
	public function getMail() { return $this->getVar('user_email'); }
	public function wantsTextMail() { return $this->getVar('user_mail_fmt') === GDO_EmailFormat::TEXT; }

	public function getGender() { return $this->getVar('user_gender'); }
	public function getLangISO() { return $this->getVar('user_language'); }
	public function getLanguage() { return $this->getValue('user_language'); }
	public function getCountryISO() { return $this->getVar('user_country'); }
	public function getCountry() { return $this->getValue('user_country'); }
	public function getBirthdate() { return $this->getVar('user_birthdate'); }
	public function getAge() { return GWF_Time::getAge($this->getBirthdate()); }

	public function getRegisterDate() { return $this->getVar('user_register_time'); }
	public function getRegisterIP() { return $this->getVar('user_register_ip'); }
	public function isDeleted() { return $this->getVar('user_deleted_at') !== null; }
	
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
	public function href_perm_revoke() { return href('Admin', 'PermissionRevoke', "&user={$this->getID()}&perm=".$this->getVar('perm_perm_id')); }
	
	#############
	### Perms ###
	#############
	public function loadPermissions()
	{
		if (null === ($cache = $this->tempGet('gwf_permission')))
		{
			$cache = GWF_UserPermission::load($this);
			$this->tempSet('gwf_permission', $cache);
			$this->recache();
		}
		return $cache;
	}
	public function hasPermission(string $permission) { return array_key_exists($permission, $this->loadPermissions()); }
	public function isAdmin() { return $this->hasPermission('admin'); }
	public function changedPermissions() { $this->tempUnset('gwf_permission'); return $this->recache(); }
	
	##############
	### Static ###
	##############
	/**
	 * Get guest ghost user.
	 * @return GWF_User
	 */
	public static function ghost() { return self::table()->blank(['user_id' => '0', 'user_type' => 'ghost']); }

	/**
	 * Get current user.
	 * Not necisarilly via session!
	 * @return GWF_User
	 */
	public static function current() { return isset(self::$CURRENT) ? self::$CURRENT : GWF_Session::user(); }
	public static $CURRENT;

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
			exec()->
			fetchAllObjectsAs(self::table());
	}
}
