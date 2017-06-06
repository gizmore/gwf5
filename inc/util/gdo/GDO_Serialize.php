<?php
/**
 * Datatype that uses PHP serialize to store arbitrary data.
 * Used in GWF_Session.
 * 
 * @author gizmore
 * @see GWF_Session
 * @since 5.0
 */
class GDO_Serialize extends GDO_Text
{
	public function getGDOValue()
	{
		$value = $this->gdo->getVar($this->name);
		$value = $value ? unserialize($value) : [];
		return $value;
	}
	
	public function setGDOValue($value)
	{
		$this->value = $value ? serialize($value) : null;
		return $this->gdo->setVar($this->name, $this->value);
	}
	
}
