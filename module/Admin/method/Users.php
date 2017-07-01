<?php
/**
 * User table for admins
 * 
 * @author gizmore
 * @see GWF_User
 * @see GWF_Table
 */
class Admin_Users extends GWF_MethodQueryTable
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	private $fields = array('user_id', 'user_country', 'user_type', 'user_level', 'user_name', 'user_credits', 'user_gender', 'user_email');
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function getGDO()
	{
		return GWF_User::table();
	}
	
	public function getHeaders()
	{
		$headers = array(
			GDO_RowNum::make(),
			GDO_EditButton::make('edit_admin')->noLabel(),
		);
		$userHeaders = GWF_User::table()->getGDOColumns($this->fields);
		return array_merge($headers, $userHeaders);
	}
	
	
	public function getQuery()
	{
		return $this->getGDO()->select(implode(', ', $this->fields));
	}
	
}
