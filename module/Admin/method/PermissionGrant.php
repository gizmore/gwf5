<?php
class Admin_PermissionGrant extends GWF_MethodForm
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
	public function createForm(GWF_Form $form)
	{
		$gdo = GWF_UserPermission::table();
		$form->addFields(array(
			$gdo->gdoColumn('perm_user_id'),
			$gdo->gdoColumn('perm_perm_id'),
			GDO_Submit::make(),
			GDO_AntiCSRF::make(),
		));
	}
	
	public function formValidated(GWF_Form $form)
	{
		$user = GWF_User::current();
		$permission = GWF_UserPermission::blank($form->values())->replace();
		$user->changedPermissions();
		return $this->message('msg_perm_granted', [$permission->displayName(), $user->displayName()]);
	}
	
}
