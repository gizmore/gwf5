<?php
/**
 * This column type is a dynamic gdo value
 * @author gizmore
 */
class GDO_Value extends GDOType
{
	public $nameValue;
	
	public function names(string $nameValue)
	{
		$this->nameValue = $nameValue;
		return $this;
	}
	
	public function gdoColumnDefine()
	{
		$nameName = GDO::quoteIdentifierS($this->name);
		$nameValue= GDO::quoteIdentifierS($this->nameValue);
		return
				"$nameName VARCHAR(32) CHARSET ascii COLLATE ascii_bin NOT NULL".
				",$nameValue VARCHAR(512) CHARSET utf8 COLLATE utf8_general_ci";
	}
}