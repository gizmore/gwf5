<?php
/**
 * Development aid for testing cronjobs.
 * 
 * @author gizmore
 * 
 */
class Admin_Cronjob extends GWF_MethodForm
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function createForm(GWF_Form $form)
	{
		$form->addField(GDO_Submit::make()->label('btn_run_cronjob'));
		$form->addField(GDO_AntiCSRF::make());
	}
	
	public function formValidated(GWF_Form $form)
	{
		echo "<pre>";
		GWF_Cronjob::run();
		echo "</pre>\n<br/>";
		$response = ob_get_clean(); ob_start();
		return GWF_Response::make($response)->add($this->renderPage());
	}
}
