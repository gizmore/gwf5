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
	
	public function createForm()
	{
		$form = new GWF_Form();
		foreach ($this->user->gdoColumnsCache() as $gdoType)
		{
			$form->addField($gdoType);
		}
		$form->addField(GDO_Submit::make());
		$form->addField(GDO_AntiCSRF::make());
		return $form->withGDOValuesFrom($this->user);
	}
}
