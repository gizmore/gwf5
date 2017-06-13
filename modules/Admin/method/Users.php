<?php
/**
 * User table for admins
 * 
 * @author gizmore
 * @see GWF_User
 * @see GWF_Table
 */
class Admin_Users extends GWF_MethodTable
{
	use GWF_MethodAdmin;
	
	private $fields = array('user_id', 'user_country', 'user_type', 'user_level', 'user_name', 'user_credits', 'user_gender', 'user_email');
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function beforeRenderTable(GWF_Table $gwfTable)
	{
		$gwfTable->mode = GWF_Table::BOOTSTRAP;
	}
	
	public function getHeaders()
	{
		$headers = array(
			GDO_EditButton::make('edit_admin')->noLabel(),
		);
		$userHeaders = GWF_User::table()->getGDOColumns($this->fields);
		return array_merge($headers, $userHeaders);
	}
	
	public function getResult()
	{
		return GWF_User::table()->select(implode(', ', $this->fields))->exec();
	}

	public function getResultCount()
	{
		return GWF_User::table()->countWhere("true");
	}
	
}
