<?php
final class Admin_LoginAs extends GWF_MethodForm
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	public function createForm(GWF_Form $form)
	{
		$form->addField(GDO_User::make('user_name')->notNull());
		$form->addField(GDO_Submit::make()->label('btn_login_as'));
		$form->addField(GDO_AntiCSRF::make());
	}
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	/**
	 * @return Login_Form
	 */
	private function loginForm()
	{
		return Module_Login::instance()->getMethod('Form');
	}
	
	public function formValidated(GWF_Form $form)
	{
		$user = $form->getField('user_name')->getUser();
		return $this->loginForm()->loginSuccess($user);
	}
}
