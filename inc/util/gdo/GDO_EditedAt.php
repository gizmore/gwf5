<?php
final class GDO_EditedAt extends GDO_Timestamp
{
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} TIMESTAMP{$this->gdoNullDefine()}{$this->gdoInitialDefine()} ON UPDATE CURRENT_TIMESTAMP";
	}
	
// 	public function gdoBeforeUpdate(GDOQuery $query)
// 	{
// 		$now = time();
// 		$query->set($this->identifier() . '=' . $now);
// 		$this->gdo->setVar($this->name, $now, false);
// 	}
}
