<?php
class Admin_Users extends GWF_MethodTable
{
	public function getHeaders()
	{
		$headers = array(
			GDO_Button::make('admin_edit_user')->noLabel(),
		);
		$userHeaders = GWF_User::table()->getGDOColumns('user_id', 'user_type', 'user_level', 'user_name', 'user_credits', 'user_gender', 'user_email');
		return array_merge($headers, $userHeaders);
	}
	
	public function getResult()
	{
		return GWF_User::table()->select('*')->exec();
	}

	public function getResultCount()
	{
		return GWF_User::table()->countWhere("true");
	}
	
}
