<?php
class Register_Activate extends GWF_Method
{
	public function execute()
	{
		return $this->activate(Common::getRequestString('id'), Common::getRequestString('token'));
	}
	
	public function activate(string $id, string $token)
	{
		$id = GDO::quoteS($id);
		$token = GDO::quoteS($token);
		if (!($activation = GWF_UserActivation::table()->findWhere("ua_id={$id} AND ua_token={$token}")))
		{
			return $this->error('err_no_activation');
		}
		$activation->delete();
		
		$user = GWF_User::table()->blank($activation->getGDOVars());
		$user->setVars(array(
			'user_type' => 'member',
		));
		$user->insert();
		
		$response = $this->message('msg_activated', [$user->displayName()]);
		
		GWF_Hook::call('UserActivated', $user);
		
		if (Module_Register::instance()->cfgActivationLogin())
		{
			GWF5::instance()->getMethod('Login', 'Form')->loginSuccess($user);
			$response->add($this->message('msg_authenticated', [$user->displayName()]));
		}
		
		return $response;
	}
	
}
