<?php
class GDO_Primary extends GDOType
{
	public function blankData() {}
	
	private $primaryKeys;
	
	public function keys(string ...$columnNames)
	{
		$this->primaryKeys = implode(',', array_map(array('GDO', 'escapeIdentifierS'), $columnNames));
		return $this;
	}
	
	###############
	### GDOType ###
	###############
	public function gdoColumnDefine()
	{
		return "PRIMARY KEY({$this->primaryKeys})";
	}
}