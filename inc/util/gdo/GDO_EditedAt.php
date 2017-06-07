<?php
final class GDO_EditedAt extends GDO_Time
{
	public function gdoBeforeUpdate(GDOQuery $query)
	{
		$now = time();
		$query->set($this->identifier() . '=' . $now);
		$this->gdo->setVar($this->name, $now, false);
	}
}
