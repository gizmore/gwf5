<?php
/**
 * Mimics a GDOResult from database.
 * Used in, e.g. Admin_Modules overview, as its loaded from FS.
 * 
 * @author gizmore
 * @since 5.0
 *
 */
class GDOArrayResult extends GDOResult
{
	/**
	 * @var GDO[]
	 */
	private $data;
	
	private $index;
	
	public function __construct(array $data, GDO $gdo)
	{
		$this->data = array_values($data);
		$this->table = $gdo;
		$this->reset();
	}
	
	public function reset()
	{
		$this->index = 0;
		return $this;
	}
	
	public function numRows()
	{
		return count($this->data);
	}

	public function fetchRow()
	{
		return array_values($this->fetchAssoc());
	}

	public function fetchAssoc()
	{
		return $this->fetchObject()->getGDOVars();
	}
	
	public function fetchAs(GDO $table)
	{
		return $this->fetchObject();
	}
	
	public function fetchObject()
	{
		return isset($this->data[$this->index]) ? $this->data[$this->index++] : null;
	}
}
