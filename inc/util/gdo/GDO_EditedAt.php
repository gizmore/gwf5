<?php
/**
 * 
 * @author gizmore
 *
 */
final class GDO_EditedAt extends GDO_Timestamp
{
	public function defaultLabel() { return $this->label('edited_at'); }
	
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} DATETIME{$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
	
	/**
	 * Also set timestamp on updates.
	 * {@inheritDoc}
	 * @see GDOType::gdoBeforeUpdate()
	 */
	public function gdoBeforeUpdate(GDOQuery $query)
	{
		$now = GWF_Time::getDate();
		$query->set($this->identifier() . "=" . quote($now));
		$this->gdo->setVar($this->name, $now);
	}
}
