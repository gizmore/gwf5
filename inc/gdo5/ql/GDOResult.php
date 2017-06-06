<?php
/**
 * @author gizmore
 * @version 5.0
 * @since 5.0
 */
class GDOResult
{
	private $table;
	private $result;
	
	public function __construct(GDO $table, $result)
	{
		$this->table = $table;
		$this->result = $result;
	}
	
	public function __destruct()
	{
		if ($this->result)
		{
			mysqli_free_result($this->result);
		}
	}
	
	/**
	 * @return int
	 */
	public function numRows()
	{
		return mysqli_num_rows($this->result);
	}
	
	public function fetchRow()
	{
		return mysqli_fetch_row($this->result);
	}
	

	/**
	 * @return string[]
	 */
	public function fetchAssoc()
	{
		return mysqli_fetch_assoc($this->result);
	}
	
	
	/**
	 * @return GDO
	 */
	public function fetchObject()
	{
		if ($gdoData = $this->fetchAssoc())
		{
			return $this->table->initCached($gdoData);
		}
	}

	/**
	 * @return GDO[]
	 */
	public function fetchAllObjects()
	{
		$objects = array();
		while ($object = $this->fetchObject())
		{
			$objects[] = $object;
		}
		return $objects;
	}
	
	public function fetchAllAssoc()
	{
		$data = array();
		while ($row = $this->fetchAssoc())
		{
			$data[] = $row;
		}
		return $data;
	}
}
