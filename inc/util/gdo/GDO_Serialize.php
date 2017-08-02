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
	public $editable = false;
	public $writable = false;
	
	public function getGDOValue()
	{
	    if ($this->gdo)
	    {
    		$value = $this->gdo->getVar($this->name);
    		return $value === null ? null : unserialize($value);
	    }
	}
	
	public function setGDOValue($value)
	{
		$this->value = $value === null ? null : serialize($value);
		return $this->gdo->setVar($this->name, $this->value);
	}
	
}
