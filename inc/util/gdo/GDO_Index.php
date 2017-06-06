<?php
class GDO_Index extends GDOType
{
	private $indexColumns;
	
	public function indexColumns(string ...$indexColumns)
	{
		$this->indexColumns = implode(',', array_map(array('GDO', 'escapeIdentifierS'), $indexColumns));
		return $this;
	}
	
	###############
	### GDOType ###
	###############
	public function gdoColumnDefine()
	{
		return "INDEX({$this->indexColumns})";
	}
}
