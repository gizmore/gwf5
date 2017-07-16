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
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function getGDO()
	{
		return GWF_User::table();
	}
	
	public function getQuery()
	{
		return $this->getGDO()->select('*');
	}
	
	public function getHeaders()
	{
		$gdo = $this->getGDO();
		return array(
// 			GDO_RowNum::make(),
			GDO_IconButton::make('edit_admin')->icon('edit'),
			$gdo->gdoColumn('user_id'),
			$gdo->gdoColumn('user_country'),
			$gdo->gdoColumn('user_type'),
			$gdo->gdoColumn('user_level'),
			GDO_Username::make('user_name'),
			$gdo->gdoColumn('user_credits'),
			$gdo->gdoColumn('user_email'),
		);
	}
	
}
