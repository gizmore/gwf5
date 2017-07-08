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
		try
		{
			ob_start();
			echo "<pre>"; GWF_Cronjob::run(); echo "</pre>\n<br/>";
			$response = ob_get_contents();
			return $this->renderPage()->add(GWF_Response::make($response));
		}
		catch (Exception $ex)
		{
			throw $ex;
		}
		finally
		{
			ob_end_clean();
		}
	}
}
