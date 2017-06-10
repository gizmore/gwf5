<?php
final class Admin_LoginAs extends GWF_MethodForm
{
	use GWF_MethodAdmin;
	
	public function createForm(GWF_Form $form)
	{
		$this->title('ft_login_as');
		$form->addField(GDO_Username::make('user_name')->completion()->exists());
		$form->addField(GDO_Submit::make());
		$form->addField(GDO_AntiCSRF::make());
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
		$user = $form->getField('user_name')->gdo;
		return $this->renderNavBar()->add($this->loginForm()->loginSuccess($user));
	}
}
