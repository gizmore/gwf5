<?php
/**
 * Ugly query builder part of GDO database code
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 */
class GDOQuery
{
	private $write = false; # Is it a write query?
	
	/**
	 * The table to manipulate
	 * @var GDO
	 */
	private $table;
	
	# query parts
	private $columns;
	private $where;
	private $join;
	private $having;
	private $from;
	private $type;
	private $set;
	private $order;
	private $values;
	private $limit;
	
	public function __construct(GDO $table)
	{
		$this->table = $table;
	}
	
	public function update(string $tableName)
	{
		$this->type = "UPDATE";
		$this->write = true;
		return $this->from($tableName);
	}
	
	public function insert(string $tableName)
	{
		$this->type = "INSERT INTO";
		$this->write = true;
		return $this->from($tableName);
	}
	
	public function replace(string $tableName)
	{
		$this->type = "REPLACE INTO";
		$this->write = true;
		return $this->from($tableName);
	}
	
	public function where(string $condition, $op="AND")
	{
		if ($this->where)
		{
			$this->where.= " $op ($condition)";
		}
		else
		{
			$this->where= "($condition)";
		}
		return $this;
	}
	
	public function getWhere()
	{
		return $this->where ? " WHERE {$this->where}" : "";
	}
	
	public function from(string $tableName)
	{
		if ($this->from)
		{
			$this->from .= ',' . $tableName;
		}
		else
		{
			$this->from = $tableName;
		}
		return $this;
	}
	
	public function getFrom()
	{
		return $this->from ? " {$this->from}" : "";
	}
	
	public function select(string $columns)
	{
		$this->type = "SELECT";
		if ($this->columns)
		{
			$this->columns .= ', ' . $columns;
		}
		else
		{
			$this->columns = $columns;
		}
		return $this;
	}
	
	public function limit(int $count, int $start=0)
	{
		$this->limit = " LIMIT $start, $count";
		return $this;
	}
	
	public function first()
	{
		return $this->limit(1);
	}
		
	public function getLimit()
	{
		return $this->limit ? $this->limit : '';
	}
	
	public function getSelect()
	{
		return $this->columns ? " {$this->columns} FROM" : '';
	}
	
	public function delete(string $tableName)
	{
		$this->write = true;
		$this->type = "DELETE";
		return $this->from($tableName);
	}
	
	public function set(string $set)
	{
		if ($this->set)
		{
			$this->set .= ',' . $set;
		}
		else
		{
			$this->set = $set;
		}
		return $this;
	}
	
	public function getSet()
	{
		return $this->set ? " SET {$this->set}" : "";
	}
	
	public function order(string $column, bool $ascending)
	{
		$order = $column . ($ascending ? ' ASC' : ' DESC');
		if ($this->order)
		{
			$this->order .= ',' . $order;
		}
		else
		{
			$this->order = $order;
		}
		return $this;
	}
	
	public function join(string $join)
	{
		if ($this->join)
		{
			$this->join .= $join;
		}
		else
		{
			$this->join = " $join";
		}
		return $this;
	}
	
	public function values(array $values)
	{
		$this->values = $values;
		return $this;
	}
	
	public function getValues()
	{
		if (!$this->values)
		{
			return '';
		}
		$fields = array();
		$values = array();
		foreach ($this->values as $key => $value)
		{
			$fields[] = GDO::quoteIdentifierS($key);
			$values[] = GDO::quoteS($value);
		}
		$fields = implode(',', $fields);
		$values = implode(',', $values);
		return " ($fields) VALUES ($values)";
	}
	
	public function getJoin()
	{
		return $this->join ? " {$this->join}" : "";
	}
	
	public function getOrderBy()
	{
		return $this->order ? " ORDER BY {$this->order}" : "";
	}

	/**
	 * 
	 * @return GDOResult
	 */
	public function exec()
	{
		$db = GDODB::instance();
		$query = $this->buildQuery();
		if ($this->write)
		{
			return $db->queryWrite($query);
		}
		else
		{
			return new GDOResult($this->table, $db->queryRead($query));
		}
	}
	
	public function debug()
	{
		echo "{$this->buildQuery()}<br/>\n";
		return $this;
	}
	
	public function buildQuery()
	{
		return $this->type . $this->getSelect() . $this->getFrom() . 
			$this->getValues() . $this->getSet() .
			$this->getJoin() . $this->getWhere() .
			$this->getOrderBy() . $this->getLimit(); 
	}

}