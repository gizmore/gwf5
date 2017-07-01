<?php
class Admin_PermissionAdd extends GWF_MethodForm
{
	use GWF_MethodAdmin;
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
	public function createForm(GWF_Form $form)
	{
		$gdo = GWF_Permission::table();
		$form->addFields(array(
			$gdo->gdoColumn('perm_name'),
			GDO_Submit::make(),
			GDO_AntiCSRF::make(),
		));
	}

	public function formValidated(GWF_Form $form)
	{
		$perm = GWF_Permission::blank($form->values())->insert();
		return $this->message('msg_perm_added', [$perm->displayName()]);
	}
}
