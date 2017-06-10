<?php
class Admin_UserEdit extends GWF_MethodForm
{
	private $user;
	
	public function execute()
	{
		if (!($this->user = GWF_User::getById(Common::getRequestString('id'))))
		{
			return $this->error('err_user')->add($this->execMethod('Users'));
		}
		return parent::execute();
	}
	
	public function createForm(GWF_Form $form)
	{
		foreach ($this->user->gdoColumnsCache() as $gdoType)
		{
			$form->addField($gdoType);
		}
		$form->addField(GDO_Submit::make());
		$form->addField(GDO_AntiCSRF::make());
		$form->withGDOValuesFrom($this->user);
	}
}
