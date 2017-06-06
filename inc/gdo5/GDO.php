<?php
require "ql/GDODB.php";
require "ql/GDOQuery.php";
require "ql/GDOResult.php";
require "ql/GDOArrayResult.php";
require "ql/GDOCache.php";
require "ql/GDOType.php";

abstract class GDO
{
	const MYISAM = 'myisam';
	const INNODB = 'innodb';
	const MEMORY = 'memory';
	
	public static $ENGINE = self::INNODB;
	
	public abstract function gdoColumns();
	public function gdoTableName() { return strtolower(get_called_class()); }
	public function gdoDependencies() { return null; }
	
	public function gdoEngine() { return get_called_class()::$ENGINE; }
	public function gdoTableIdentifier() { return self::quoteIdentifierS($this->gdoTableName()); }
	
	public function gdoClassName() { return get_class($this); }
	public static function gdoClassNameS() { return get_called_class(); }

	################
	### Escaping ###
	################
	
	public static function escapeIdentifierS(string $identifier) { return str_replace("`", "\`", $identifier); }
	public static function quoteIdentifierS(string $identifier) { return "`" . self::escapeIdentifierS($identifier) . "`"; }

	public static function escapeS(string $value) { return str_replace(array("'", '"'), array("\\'", '\\"'), $value); }
	public static function quoteS($value)
	{
		if (is_string($value))
		{
			return "'" . self::escapeS($value) . "'";
		}
		if ($value === null)
		{
			return "NULL";
		}
		if (is_numeric($value))
		{
			return $value;
		}
		throw new GWF_Exception('err_param', array('name' => 'value', 'value' => $value));
	}
	
	#################
	### Persisted ###
	#################
	private $persisted = false;
	public function isPersisted() { return $this->persisted; }
	public function setPersisted(bool $persisted = true) { $this->persisted = $persisted; return $this; }
	
	############
	### Vars ###
	############
	private $gdoVars;
	private $dirty = null;
	
	/**
	 * Get the GDOType for a key.
	 * @param string $key
	 * @return GDOType
	 */
	public function gdoColumn(string $key)
	{
		foreach ($this->gdoColumnsCache() as $column)
		{
			$column instanceof GDOType;
			if ($column->name === $key)
			{
				return $column;
			}
		}
	}
	
	/**
	 * @return string[string]
	 */
	public function getGDOVars()
	{
		return $this->gdoVars;
	}
	
	public function getVar(string $key)
	{
		return @$this->gdoVars[$key];
	}
	
	public function display(string $key)
	{
		if ($column = $this->gdoColumn($key))
		{
			return $column->gdoDisplay($this, $this->getVar($key));
		}
		$method_name = sprintf('display_%s', $key);
		if (method_exists($this, $method_name))
		{
			return call_user_func(array($this, $method_name));
		}
		else
		{
			return GWF_Error::error('err_missing_display_function', [$this->gdoClassName(), $method_name]);
		}
	}

	public function setVar(string $key, string $value, $markDirty=true)
	{
		$this->gdoVars[$key] = $value;
		return $markDirty ? $this->markDirty($key) : $this;
	}
	
	public function setValue(string $key, $value, $markDirty=true)
	{
		$type = $this->gdoColumn($key);
		$type->setGDOValue($value);
		return $this->setVar($key, $type->getValue());
	}
	
	public function markDirty(string $key)
	{
		if ($this->dirty === false)
		{
			$this->dirty = [];
		}
		if ($this->dirty !== true)
		{
			$this->dirty[$key] = true;
		}
		return $this;
	}
	
	public function isDirty()
	{
		return $this->dirty === false ? false : count($this->dirty) > 0;
	}
	
	public function setGDOVars(array $vars, $dirty=null)
	{
		$this->gdoVars = $vars;
		$this->dirty = $dirty;
		return $this;
	}
	
	/**
	 * @param string ...$keys
	 * @return string[]
	 */
	public function getVars(...$keys)
	{
		$back = array();
		foreach ($keys as $key)
		{
			$back[$key] = $this->getVar($key);
		}
		return $back;
	}
	
	/**
	 * Get the gdo value of a column.
	 * @param string $key
	 * @return mixed
	 */
	public function getValue(string $key)
	{
		return $this->gdoColumn($key)->gdo($this)->getGDOValue();
	}
	
	public function getDirtyVars()
	{
		if ($this->dirty === true)
		{
			return $this->gdoVars;
		}
		elseif ($this->dirty === false)
		{
			return [];
		}
		else
		{
			return $this->getVars(array_keys($this->dirty));
		}
	}
	
	##########
	### DB ###
	##########
	/**
	 * Create a new query for this GDO table.
	 * @return GDOQuery
	 */
	public function query()
	{
		return new GDOQuery($this);
	}
	
	/**
	 * @param string $id
	 * @return self
	 */
	public function find(string $id)
	{
		return $this->findWhere($this->gdoAutoIncColumn()->identifier() . ' = ' . GDO::quoteS($id));
	}
	
	/**
	 * @param string $where
	 * @return int
	 */
	public function countWhere(string $where)
	{
		$result = $this->query()->select("COUNT(*)")->from($this->gdoTableIdentifier())->where($where)->exec()->fetchRow();
		return $result[0];
	}
	
	/**
	 * @param string $where
	 * @return self
	 */
	public function findWhere(string $where)
	{
		return $this->query()->select('*')->from($this->gdoTableIdentifier())->where($where)->first()->exec()->fetchObject();
	}
	
	public function select(string $columns)
	{
		return $this->query()->select($columns)->from($this->gdoTableIdentifier());
	}
	
	public function delete()
	{
		return $this->query()->delete()->from($this->gdoTableIdentifier())->where($this->getPKWhere())->exec();
	}
	
	public function replace()
	{
		$query = $this->query()->replace($this->gdoTableIdentifier())->values($this->getDirtyVars());
		if ($query->exec())
		{
			$this->afterInsert();
		}
	}
	
	public function insert()
	{
		$query = $this->query()->insert($this->gdoTableIdentifier())->values($this->getDirtyVars());
		if ($query->exec())
		{
			$this->gdoAfterInsert();
		}
	}
	
	public function gdoAfterInsert()
	{
		if ($column = $this->gdoAutoIncColumn())
		{
			$db = GDODB::$INSTANCE;
			if ($id = $db->insertId())
			{
				$this->setVar($column->name, (string)$id);
			}
		}
		$this->persisted = true;
		$this->dirty = false;
	}
	
	public function updateQuery()
	{
		return $this->entityQuery()->update($this->gdoTableIdentifier());
	}
		
	public function save()
	{
		if (!$this->persisted)
		{
			return $this->insert();
		}
		if ($this->isDirty())
		{
			$this->updateQuery()->set($this->getSetClause())->exec();
			$this->dirty = false;
		}
		return $this;
	}
	
	public function saveVar(string $key, $value)
	{
		return $this->saveVars([$key => $value]);
	}
	
	public function saveVars(array $vars)
	{
		$query = $this->updateQuery();
		foreach ($vars as $key => $value)
		{
			if ($this->gdoVars[$key] != $value)
			{
				$query->set(sprintf("%s=%s", self::quoteIdentifierS($key), self::quoteS($value)));
			}
		}
		if ($result = $query->exec())
		{
			$this->gdoVars = array_merge($this->gdoVars, $vars);
			return $result;
		}
	}
	
	/**
	 * @return GDOQuery
	 */
	public function entityQuery()
	{
		return $this->query()->where($this->getPKWhere());
	}
	
	public function getSetClause()
	{
		$setClause = '';
		if ($this->dirty !== false)
		{
			foreach ($this->gdoColumnsCache() as $column)
			{
				$column instanceof GDOType;
				if ( ($this->dirty === true) || isset($this->dirty[$column->name]) )
				{
					if ($setClause !== '')
					{
						$setClause .= ',';
					}
					$setClause .= $column->identifier() . "=" . $this->quoted($column->name);
				}
			}
		}
		return $setClause;
	}
	
	####################
	### Primary Keys ###
	####################
	/**
	 * Get the primary key where condition for this row.
	 * @return string
	 */
	public function getPKWhere()
	{
		$where = "";
		foreach ($this->gdoPrimaryKeyColumns() as $column)
		{
			if ($where !== '')
			{
				$where .= ' AND ';
			}
			$where .= $column->identifier() . ' = ' . $this->quoted($column->name);
		}
		return $where;
	}
	
	public function quoted(string $key) { return self::quoteS($this->getVar($key)); }
	
	/**
	 * Get the primary key columns for a table.
	 * @return GDOType[]
	 */
	public function gdoPrimaryKeyColumns()
	{
		$columns = array();
		foreach ($this->gdoColumnsCache() as $column)
		{
			$column instanceof GDOType;
			if ($column->isPrimary())
			{
				$columns[] = $column;
			}
		}
		return $columns;
	}
	
	/**
	 * @return GDOType
	 */
	public function gdoPrimaryKeyColumn()
	{
		foreach ($this->gdoPrimaryKeyColumns() as $column)
		{
			return $column;
		}
	}
	
	/**
	 * @return GDO_AutoInc
	 */
	public function gdoAutoIncColumn()
	{
		foreach ($this->gdoColumnsCache() as $column)
		{
			if ($column instanceof GDO_AutoInc)
			{
				return $column;
			}
		}
	}
	
	################
	### Instance ###
	################
	/**
	 * @param array $gdoVars
	 * @return self
	 */
	private static function entity(array $gdoVars)
	{
		$class = self::gdoClassNameS();
		$instance = new $class;
		$instance->gdoVars = $gdoVars;
		return $instance;
	}
	
	/**
	 * raw initial string data. 
	 * @param array $initial
	 * @return array
	 */
	public static function blankData($initial = array())
	{
		$table = self::table();
		$gdoVars = array();
		foreach ($table->gdoColumnsCache() as $column)
		{
			if ($data = $column->blankData())
			{
				$gdoVars = array_merge($gdoVars, $data);
			}
		}
		$gdoVars = array_merge($gdoVars, $initial);
		return $gdoVars;
	}
	
	/**
	 * @return self
	 */
	public static function blank($initial = array())
	{
		return self::entity(self::blankData($initial))->dirty();
	}
	
	public function dirty($dirty=true)
	{
		$this->dirty = $dirty;
		return $this;
	}
	
	##############
	### Get ID ###
	##############
	public function getID()
	{
		if ($column = $this->gdoAutoIncColumn())
		{
			return $this->getVar($column->name);
		}
	}
	
	##############
	### Get by ###
	##############
	/**
	 * Get a row by a single arbritary column value.
	 * @param string $key
	 * @param string $value
	 * @return self
	 */
	public static function getBy(string $key, string $value)
	{
		return self::table()->findWhere(self::quoteIdentifierS($key) . '=' . self::quoteS($value));
	}
	
	#############
	### Cache ###
	#############
	private $cache;
	public function initCache() { $this->cache = new GDOCache($this); }
	public function initCached(array $row) { return $this->cache->initCached($row); }
	
	###########################
	###  Table manipulation ###
	###########################
	/**
	 * @param string $className
	 * @return self
	 */
	public static function tableFor(string $className) { return GDODB::tableS($className); }
	public static function table() { return self::tableFor(self::gdoClassNameS()); }
	
	public function createTable() { return GDODB::instance()->createTable($this); }
	public function dropTable() { return GDODB::instance()->dropTable($this); }
	public function truncate() { return GDODB::instance()->truncateTable($this); }
	
	/**
	 * @return GDOType[]
	 */
	public function gdoColumnsCache() { return GDODB::columnsS($this->gdoClassName()); }

	##############
	### Events ###
	##############
	public function afterInsert() {}
	public function afterUpdate() {}
	public function afterDelete() {}
	
}