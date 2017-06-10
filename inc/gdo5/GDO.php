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
	
	/**
	 * @return GDOType[]
	 */
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
	private $dirty = false;
	
	/**
	 * Get the GDOType for a key.
	 * @param string $key
	 * @return GDOType
	 */
	public function gdoColumn(string $key)
	{
		$columns = $this->gdoColumnsCache();
		return $columns[$key];
// 		foreach ($this->gdoColumnsCache() as $column)
// 		{
// 			if ($column->name === $key)
// 			{
// 				return $column;
// 			}
// 		}
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
	
// 	public function display(string $key)
// 	{
// 		if ($column = $this->gdoColumn($key))
// 		{
// 			return $column->gdoDisplay($this, $this->getVar($key));
// 		}
// 		$method_name = 'display_' . $key;
// 		if (method_exists($this, $method_name))
// 		{
// 			return call_user_func(array($this, $method_name));
// 		}
// 		else
// 		{
// 			return GWF_Error::error('err_missing_display_function', [$this->gdoClassName(), $method_name]);
// 		}
// 	}

	public function setVar(string $key, $value, $markDirty=true)
	{
		$this->gdoVars[$key] = $value;
		return $markDirty ? $this->markDirty($key) : $this;
	}
	
	public function setVars(array $vars, $markDirty=true)
	{
		foreach ($vars as $key => $value)
		{
			$this->setVar($key, $value, $markDirty);
		}
		return $this;
	}
	
	public function setValue(string $key, $value, $markDirty=true)
	{
		$this->gdoColumn($key)->gdo($this)->setGDOValue($value);
	}
	
	public function markClean(string $key)
	{
		if ($this->dirty === false)
		{
			$this->dirty = array_keys($this->gdoVars);
			unset($this->dirty[$key]);
		}
		elseif (is_array($this->dirty))
		{
			unset($this->dirty[$key]);
		}
		return $this;
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
		return $this->dirty === false ? false : (count($this->dirty) > 0);
	}
	
	public function setGDOVars(array $vars, $dirty=false)
	{
		$this->gdoVars = $vars;
		$this->dirty = $dirty;
		return $this;
	}
	
	/**
	 * @param string ...$keys
	 * @return string[]
	 */
	public function getVars(string ...$keys)
	{
		$back = [];
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
	
	/**
	 * Get gdoVars that have been changed.
	 * @return string[]
	 */
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
	 * Find a row by AutoInc Id.
	 * @param string $id
	 * @return self
	 * @see GDO_AutoInc
	 */
	public function find(string $id = null)
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
	
	public function select(string $columns='*')
	{
		return $this->query()->select($columns)->from($this->gdoTableIdentifier());
	}
	
	public function deleteWhere(string $condition)
	{
		return $this->query()->delete($this->gdoTableIdentifier())->where($condition);
	}
	
	public function delete()
	{
		if ($this->persisted)
		{
			$this->query()->delete($this->gdoTableIdentifier())->where($this->getPKWhere())->exec();
			$this->persisted = false;
			$this->dirty = false;
		}
		return $this;
	}
	
	public function replace()
	{
		if (!$this->persisted)
		{
			return $this->insert();
		}
		$this->query()->replace($this->gdoTableIdentifier())->values($this->getDirtyVars())->exec();
		$this->dirty = false;
		$this->persisted = true;
		$this->gdoAfterUpdate();
		return $this;
	}
	
	public function insert()
	{
		$this->query()->insert($this->gdoTableIdentifier())->values($this->getDirtyVars())->exec();
		$this->afterCreate();
		return $this;
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
			$this->gdoAfterUpdate();
		}
		return $this;
	}
	
	public function saveVar(string $key, $value)
	{
		return $this->saveVars([$key => $value]);
	}
	
	public function saveVars(array $vars)
	{
		$worthy = false;
		$query = $this->updateQuery();
		foreach ($vars as $key => $value)
		{
			if ($this->gdoVars[$key] != $value)
			{
				$query->set(sprintf("%s=%s", self::quoteIdentifierS($key), self::quoteS($value)));
				$this->markClean($key);
				$worthy = true; # We got a change
			}
		}
		if ($worthy)
		{
			$this->beforeUpdate($query);
			$query->exec();
			$this->gdoVars = array_merge($this->gdoVars, $vars);
			$this->gdoAfterUpdate();
		}
		return $this;
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
				if ( ($this->dirty === true) || (isset($this->dirty[$column->name])) )
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
		$columns = [];
		foreach ($this->gdoColumnsCache() as $column)
		{
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
	public static function blankData(array $initial = null)
	{
		$table = self::table();
		$gdoVars = [];
		foreach ($table->gdoColumnsCache() as $column)
		{
			if ($data = $column->blankData())
			{
				$gdoVars = array_merge($gdoVars, $data);
			}
		}
		if ($initial)
		{
			# Merge only existing keys
			$gdoVars = array_intersect_key($initial, $gdoVars) + $gdoVars;
		}
		return $gdoVars;
	}
	
	/**
	 * @return self
	 */
	public static function blank(array $initial = null)
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
	
	/**
	 * Get a row by auto inc column. 
	 * @param string $id
	 * @return self
	 */
	public static function getById($id)
	{
		return self::getBy(self::table()->gdoAutoIncColumn()->name, $id);
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
	
	/**
	 * @return GDOType[]
	 */
	public function getGDOColumns(array $names)
	{
		$columns = [];
		foreach ($names as $key)
		{
			$columns[$key] = $this->gdoColumn($key);
		}
		return $columns;
	}

	##############
	### Events ###
	##############
	private function beforeUpdate(GDOQuery $query)
	{
		foreach ($this->gdoColumnsCache() as $gdoType)
		{
			$gdoType->gdo($this)->gdoBeforeUpdate($query);
		}
	}
	
	private function afterCreate()
	{
		# Flags
		$this->dirty = false;
		$this->persisted = true;
		# Trigger event for AutoCol, EditedAt, EditedBy, etc.
		foreach ($this->gdoColumnsCache() as $gdoType)
		{
			$gdoType->gdo($this)->gdoAfterCreate();
		}
		$this->gdoAfterCreate();
	}
	
	# Overrides
	public function gdoAfterCreate() {}
	public function gdoAfterUpdate() {}
	public function gdoAfterDelete() {}
	
}