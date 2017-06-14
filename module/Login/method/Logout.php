<?php
/**
 * Logout method.
 * 
 * @author gizmore
 * @version 5.0
 */
final class Login_Logout extends GWF_Method
{
	public function isUserRequired() { return true; }
	
	public function execute()
	{
		$sesion = GWF_Session::instance();
		$sesion->setValue('sess_user', null);
		$sesion->setValue('sess_data', null);
		$sesion->save();
		GWF_Hook::call('UserLoggedOut', [GWF_User::current()]);
		return $this->message('msg_logged_out');
	}
}
