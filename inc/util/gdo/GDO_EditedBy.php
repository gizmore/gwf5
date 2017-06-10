<?php
final class GDO_EditedBy extends GDO_User
{
	public function gdoBeforeUpdate(GDOQuery $query)
	{
		$userId = GWF_User::current()->getID();
		$query->set($this->identifier() . '=' . $userId);
		$this->gdo->setVar($this->name, $userId);
	}
}
