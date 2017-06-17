<?php
class GDO_User extends GDO_Object
{
	public $klass = 'GWF_User';

	public function defaultLabel() { return $this->label('user'); }

	private $ghost = false;
	public function ghost(bool $ghost=true)
	{
		$this->ghost = $ghost;
		return $this;
	}
	
	/**
	 * @return GWF_User
	 */
	public function getUser()
	{
		if (!($user = $this->getGDOValue()))
		{
			if ($this->ghost)
			{
				$user = GWF_User::ghost();
			}
		}
		return $user;
	}
	
	public function renderCell()
	{
		return $this->getUser()->displayName();
	}
}
