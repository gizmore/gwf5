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
	public function getType() { return $this->getVar('user_type'); }
	public function getName() { return $this->getVar('user_name'); }
	public function getPersonName() { return $this->getVar('user_realname'); }
	public function getGuestName() { return $this->getVar('user_guest_name'); }
	public function isGhost() { return $this->getType() === 'ghost'; }
	public function isGuest() { return $this->getType() === 'guest'; }
	public function isMember() { return $this->getType() === 'member'; }
	
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
	
// 	public function display_admin_edit_user() { return GDO_Button::make('admin_edit_user')->label('btn_edit')->href($this->hrefAdminEdit())->render(); }
	
	#############
	### HREFs ###
	#############
	public function href_edit_admin() { return href('Admin', 'UserEdit', "&id={$this->getID()}"); }
	
	#############
	### Perms ###
	#############
	private $permissions;
	public function loadPermissions() { if (!$this->permissions) { $this->permissions = GWF_UserPermission::load($this); } }
	public function hasPermission(string $perm) { $this->loadPermissions(); return isset($this->permissions[$perm]); }
	public function isAdmin() { return $this->hasPermission('admin'); }
	
	##############
	### Static ###
	##############
	public static function getById(string $id) { return self::getBy('user_id', $id); }
	public static function getByName(string $name) { return self::getBy('user_name', $name); }
	public static function current() { return GWF_Session::user(); }
	public static function ghost() { return self::table()->blank(['user_type' => 'ghost']); }
	
}
