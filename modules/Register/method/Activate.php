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
		
	}
}