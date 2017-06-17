<?php
class GDO_User extends GDO_Object
{
	public $klass = 'GWF_User';
	
	public function defaultLabel() { return $this->label('user'); }
	
	/**
	 * @return GWF_User
	 */
	public function getUser()
	{
		if (!($user = $this->getGDOValue()))
		{
			$user = GWF_User::ghost();
		}
		return $user;
	}
	
	public function renderCell()
	{
		return $this->getUser()->displayName();
	}
}
