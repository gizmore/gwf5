<?php
/**
 * Edit a user.
 * 
 * @author gizmore
 * @see GWF_User
 */
class Admin_UserEdit extends GWF_MethodForm
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	private $user;
	
	public function execute()
	{
		if (!($this->user = GWF_User::getById(Common::getRequestString('id'))))
		{
			return $this->error('err_user')->add($this->execMethod('Users'));
		}
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function createForm(GWF_Form $form)
	{
		$this->title('ft_admin_useredit', [$this->getSiteName(), $this->user->displayName()]);
		foreach ($this->user->gdoColumnsCache() as $gdoType)
		{
			$form->addField($gdoType);
		}
		$form->getField('user_id')->writable(false);
		$form->addField(GDO_Submit::make());
		$form->addField(GDO_AntiCSRF::make());
		$form->withGDOValuesFrom($this->user);
	}
	
	public function formValidated(GWF_Form $form)
	{
		$this->user->saveVars($form->values());
		$form->withGDOValuesFrom($this->user);
		return parent::formValidated($form);
	}
}
