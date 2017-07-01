<?php
class Admin_PermissionRevoke extends GWF_MethodForm
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	/**
	 * @var GWF_User
	 */
	private $user;
	
	/**
	 * @var GWF_Permission
	 */
	private $permission;
	
	public function init()
	{
		$this->user = GWF_User::table()->find(Common::getRequestString('user'));
		$this->permission = GWF_Permission::table()->find(Common::getRequestString('perm'));
	}
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
	public function createForm(GWF_Form $form)
	{
		$form->addFields(array(
			GDO_User::make('perm_user_id')->notNull()->value($this->user ? $this->user->getID() : '0'),
			GDO_Permission::make('perm_perm_id')->notNull()->value($this->permission ? $this->permission->getID() : '0'),
			GDO_Submit::make(),
			GDO_AntiCSRF::make(),
		));
	}
	
	public function formValidated(GWF_Form $form)
	{
		$condition = sprintf('perm_user_id=%s AND perm_perm_id=%s', $form->getVar('perm_user_id'), $form->getVar('perm_perm_id'));
		GWF_UserPermission::table()->deleteWhere($condition)->exec();
		$affected = GDODB::instance()->affectedRows();
		$response = $affected > 0 ? $this->message('msg_perm_revoked') : $this->error('err_nothing_happened');
		return $response->add($this->renderForm());
	}
}
