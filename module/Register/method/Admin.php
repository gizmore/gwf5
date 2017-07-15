<?php
final class Register_Admin extends GWF_MethodQueryTable
{
	use GWF_MethodAdmin;
	public function execute()
	{
		$response = parent::execute();
		$tabs = $this->renderNavBar('Register');
		return $tabs->add($response);
	}

	public function getQuery()
	{
		return GWF_UserActivation::table()->select('*');
	}
	
	public function getHeaders()
	{
		$gdo = GWF_UserActivation::table();
		return array(
			GDO_Button::make('btn_activate'),
			$gdo->gdoColumn('ua_time'),
			$gdo->gdoColumn('user_name'),
			$gdo->gdoColumn('user_register_ip'),
			$gdo->gdoColumn('user_email'),
		);
	}
}
