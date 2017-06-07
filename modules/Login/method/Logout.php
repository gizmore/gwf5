<?php
/**
 * Logout method.
 * 
 * @author gizmore
 * @version 5.0
 */
final class Login_Logout extends GWF_Method
{
	public function execute()
	{
		$sesion = GWF_Session::instance();
		$sesion->setValue('sess_user', null);
		$sesion->setValue('sess_data', null);
		$sesion->save();
		return $this->message('msg_logged_out');
	}
}
