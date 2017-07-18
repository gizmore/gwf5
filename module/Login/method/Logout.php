<?php

/**
 * Logout method.
 * 
 * @author gizmore
 * @version 5.0
 */
final class Login_Logout extends GWF_Method
{

    public function isUserRequired()
    {
        return true;
    }

    public function execute()
    {
        $session = GWF_Session::instance();
        $user = GWF_User::current();
        $user->tempReset();
        $session->setValue('sess_user', null);
        $session->setValue('sess_data', null);
        $session->save();
        GWF_Hook::call('UserLoggedOut', $user);
        return $this->message('msg_logged_out');
    }
}
