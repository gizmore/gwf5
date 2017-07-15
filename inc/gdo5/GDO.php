<?php
require "ql/GDODB.php";
require "ql/GDOQuery.php";
require "ql/GDOResult.php";
require "ql/GDOArrayResult.php";
require "ql/GDOCache.php";
require "ql/GDOType.php";

/**
 * This is not GDOType (yet?) and reflects a Database table.
 * Specify columns in your gdoColumns() and return GDOType[].
 * 
 * @WARNING If your primary key is a combined key, it is advised to disable memCached() by return false there
 * 
 * @author gizmore
 * @since 1.0
 * @version 5.0
 */
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
	
	public function gdoCached() { return true; }
	public function memCached() { return $this->gdoCached(); }
	public function gdoTableName() { return strtolower(get_called_class()); }
	public function gdoDependencies() { return null; }
	
	public function gdoEngine() { return get_called_class()::$ENGINE; }
	public function gdoTableIdentifier() { return self::quoteIdentifierS($this->gdoTableName()); }
	
	public function gdoAbstract() { return false; }
	public function gdoHumanName() { return t(strtolower(get_class($this))); }
	public function gdoClassName() { return self::gdoClassNameS(); } # }
	public static function gdoClassNameS() { return get_called_class(); }
	################
	### Escaping ###
	################
	
// 	public static function escapeIdentifierS(string $identifier) { return str_replace("`", "\`", $identifier); }
	public static function quoteIdentifierS(string $identifier) { return $identifier; } # NOT NEEDED yet :) return "`" . self::escapeIdentifierS($identifier) . "`"; }

	public static function escapeS(string $value) { return str_replace(array("'", '"'), array("\\'", '\\"'), $value); }
	public static function quoteS($value)
	{
		if (is_string($value))
		{
			return "'" . self::escapeS($value) . "'";
		}
		elseif ($value === null)
		{
			return "NULL";
		}
		elseif (is_numeric($value))
		{
			return $value;
		}
		else
		{
			throw new GWF_Exception('err_cannot_quote', [htmlspecialchars($value)]);
		}
	}
	
	#################
	### Persisted ###
	#################
	private $persisted = false;
	public function isPersisted() { return $this->persisted; }
	public function setPersisted(bool $persisted = true) { $this->persisted = $persisted; return $this; }
	
	########################
	### Custom temp vars ###
	########################
	/**
	 * @var mixed[string]
	 */
	private $temp;
	public function tempGet(string $key) { return @$this->temp[$key]; }
	public function tempSet(string $key, $value) { if (!$this->temp) $this->temp = []; $this->temp[$key] = $value; /* $this->recache(); */ }
	public function tempUnset(string $key) { unset($this->temp[$key]); /* $this->recache(); */ }
	
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
		return $this->gdoColumnsCache()[$key]->gdo($this);
	}
	
	public function gdoColumnsExcept(string ...$except)
	{
		$columns = $this->gdoColumnsCache();
		foreach ($except as $ex)
		{
			unset($columns[$ex]);
		}
		return $columns;
	}
	
	/**
	 * @return string[string]
	 */
	public function getGDOVars()
	{
		return $this->gdoVars;
	}
	
	public function hasVar(string $key=null)
	{
		return ($key === null) || ($this->gdoVars === null) ? false : array_key_exists($key, $this->gdoVars);
	}
	
	public function getVar(string $key)
	{
		return @$this->gdoVars[$key];
	}
	
	public function display(string $key)
	{
		return htmlspecialchars(@$this->gdoVars[$key]);
	}
	
	public function edisplay(string $key)
	{
		echo $this->display($key);
	}
	
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
	 * @param string[] $keys
	 * @return string[]
	 */
	public function getVars(array $keys)
	{
		$back = [];
		foreach ($keys as $key)
		{
			$back = array_merge($back, $this->gdoColumn($key)->gdo($this)->getGDOData());
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
		return $this->gdoColumn($key)->getGDOValue();
	}
	
	/**
	 * Get gdoVars that have been changed.
	 * @return string[]
	 */
	public function getDirtyVars()
	{
		if ($this->dirty === true)
		{
			return $this->getVars(array_keys($this->gdoColumnsCache()));
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
		return new GDOQuery(self::tableFor($this->gdoClassName()));
	}
	
	/**
	 * Find a row by AutoInc Id.
	 * @param string $id
	 * @return self
	 * @see GDO_AutoInc
	 */
	public function find(string $id=null, bool $exception=true)
	{
		if ($id !== null)
		{
			if (!($gdo = $this->getById($id)))
			{
				if ($exception)
				{
					throw new GWF_Exception('err_gdo_not_found', [$this->gdoHumanName(), htmlspecialchars($id)]);
				}
			}
			return $gdo;
		}
	}
	
	/**
	 * @param string $where
	 * @return int
	 */
	public function countWhere(string $condition='true')
	{
		$result = $this->query()->select("COUNT(*)")->from($this->gdoTableIdentifier())->where($condition)->exec()->fetchRow();
		return (int)$result[0];
	}
	
	/**
	 * @param string $where
	 * @return self
	 */
	public function findWhere(string $condition)
	{
		return $this->query()->select('*')->from($this->gdoTableIdentifier())->where($condition)->first()->exec()->fetchObject();
	}
	
	/**
	 * @param string $columns
	 * @return GDOQuery
	 */
	public function select(string $columns='*')
	{
		return $this->query()->select($columns)->from($this->gdoTableIdentifier());
	}
	
	/**
	 * @param string $condition
	 * @return GDOQuery
	 */
	public function deleteWhere(string $condition)
	{
		return $this->query()->delete($this->gdoTableIdentifier())->where($condition);
	}
	
	public function delete()
	{
		if ($this->persisted)
		{
			$this->deleteWhere($this->getPKWhere())->exec();
			$this->persisted = false;
			$this->dirty = false;
		}
		return $this;
	}
	
	public function replace()
	{
		if (empty($this->getID()))
		{
			return $this->insert();
		}
		$this->query()->replace($this->gdoTableIdentifier())->values($this->gdoVars)->exec();
		$this->dirty = false;
		$this->persisted = true;
		$this->gdoAfterUpdate();
// 		$this->recache();
		return $this;
	}
	
	public function insert()
	{
		$this->query()->insert($this->gdoTableIdentifier())->values($this->getDirtyVars())->exec();
		$this->afterCreate();
		return $this;
	}
	
	public function update()
	{
		return $this->query()->update($this->gdoTableIdentifier());
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
			$this->recache();
			$this->gdoAfterUpdate();
		}
		return $this;
	}
	
	public function increase(string $key, int $by=1)
	{
		return $this->saveVar($key, $this->getVar($key)+$by);
	}
	
	public function saveVar(string $key, $value)
	{
		return $this->saveVars([$key => $value]);
	}
	
	public function saveVars(array $vars)
	{
		$worthy = false; # Anything changed?
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
			$this->beforeUpdate($query); # Can do trickery here... not needed? 
			$query->exec();
			$this->gdoVars = array_merge($this->gdoVars, $vars);
			$this->recache();
			$this->gdoAfterUpdate(); # GDO_AutoInc uses this hook
		}
		return $this;
	}
	
	public function saveValue(string $key, $value)
	{
		$this->gdoColumn($key)->setGDOValue($value);
		return $this->saveVar($key, $this->getVar($key));
	}
	
	public function saveValues(array $values)
	{
		$vars = array();
		foreach ($values as $key => $value)
		{
			$this->gdoColumn($key)->setGDOValue($value);
			$vars[$key] = $this->getVar($key);
		}
		return $this->saveVars($vars);
	}
	
	public function toJSON()
	{
		$values = [];
		foreach ($this->gdoColumnsCache() as $key => $gdoType)
		{
			$values = array_merge($values, $gdoType->value(@$this->gdoVars[$key])->gdo($this)->toJSON());
		}
		return $values;
	}
	
	/**
	 * @return GDOQuery
	 */
	public function entityQuery()
	{
		if (!$this->persisted)
		{
			throw new GWF_Exception('err_save_unpersisted_entity', [$this->gdoClassName()]);
		}
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
				$columns[$column->name] = $column;
			}
			else
			{
				break;
			}
		}
		return $columns;
	}
	
	/**
	 * @return GDOType
	 */
	public function gdoPrimaryKeyColumn()
	{
		foreach ($this->gdoColumnsCache() as $column)
		{
			if ($column->isPrimary())
			{
				return $column;
			}
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
	public static function entity(array $gdoVars)
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
		$id = '';
		foreach ($this->gdoPrimaryKeyColumns() as $name => $column)
		{
			$id = $id === '' ? $id : "$id:";
			$id .= $this->getVar($name);
		}
		return $id;
	}
	
	public function displayName()
	{
		return $this->getID();
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
	public static function getBy(string $key, string $value=null)
	{
		return self::table()->findWhere(self::quoteIdentifierS($key) . '=' . self::quoteS($value));
	}
	
	public static function getByVars(array $vars)
	{
		$query = self::table()->select();
		foreach ($vars as $key => $value)
		{
			$query->where(self::quoteIdentifierS($key) . '=' . self::quoteS($value));
		}
		return $query->first()->exec()->fetchObject();
	}
	
	/**
	 * Get a row by auto inc column. 
	 * @param string $id
	 * @return self
	 */
	public static function getById(string ...$id)
	{
		$table = self::table();
		if ( (!$table->cache) || (!($object = $table->cache->findCached(...$id))) )
		{
			$i = 0;
			$query = $table->select();
			foreach ($table->gdoPrimaryKeyColumns() as $column)
			{
				$query->where($column->identifier() . '=' . self::quoteS($id[$i++]));
			}
			$object = $query->first()->exec()->fetchObject();
		}
		return $object;
	}
	
	public static function findById(string ...$id)
	{
		if ($object = self::getById(...$id))
		{
			return $object;
		}
		throw new GWF_Exception('err_gdo_not_found', [self::table()->gdoHumanName(), implode(':', $id)]);
	}
	
	/**
	 * Fetch from result set as this table.
	 * @param GDOResult $result
	 * @return self
	 */
	public function fetch(GDOResult $result)
	{
		return $result->fetchAs($this);
	}
	
	#############
	### Cache ###
	#############
	/**
	 * @var GDOCache
	 */
	public $cache;
	public function initCache() { $this->cache = new GDOCache($this); }
	public function initCached(array $row)
	{
		return $this->memCached() ? $this->cache->initGDOMemcached($row) : $this->cache->initGDOCached($row);
	}
	public function gkey()
	{
		return $this->gdoClassName() . $this->getID();
	}
	public function recache()
	{
		if ($this->gdoCached())
		{
			$this->table()->cache->recache($this);
		}
	}
	
	public function uncache()
	{
		if ($this->gdoCached())
		{
			$this->table()->cache->uncache($this);
		}
		
	}
	
	public function __wakeup()
	{
		self::table();
	}
	
	/**
	 * @return GDO[]
	 */
	public function all()
	{
		return self::allWhere();
	}
	
	/**
	 * @return GDO[]
	 */
	public function allWhere(string $condition='true')
	{
		return self::table()->select()->where($condition)->exec()->fetchAllArray2dObject();
	}
	
	###########################
	###  Table manipulation ###
	###########################
	/**
	 * @param string $className
	 * @return self
	 */
	public static function tableFor(string $className) { return GDODB::tableS($className); }
	public static function table() { return self::tableFor(get_called_class()); }
	
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
// 		$this->recache();
	}
	
	# Overrides
	public function gdoAfterCreate() {}
	public function gdoAfterUpdate() {}
	public function gdoAfterDelete() {}
	
	public function gdoHashcode()
	{
		return self::gdoHashcodeS($this->gdoVars);
	}
	
	public static function gdoHashcodeS(array $gdoVars)
	{
		return substr(md5(md5(md5(str_repeat(GWF_SALT, 4)).json_encode(array_values($gdoVars)))), 0, 16);
	}
	
	##############
	### Render ###
	##############
	public function renderList() { return GWF_Template::mainPHP("listitem/gdo.php", ['gdo'=>$this]); }
	public function renderCard() { return GWF_Template::mainPHP("card/{$this->gdoTableName()}.php", ['gdo'=>$this]); }
	public function renderCell() { return $this->renderChoice(); }
	public function renderChoice() { return sprintf('%s-%s', $this->getID(), $this->displayName()); }
	
	###############
	### Sorting ###
	###############
	/**
	 * Sort GDO[] by a field.
	 * @param GDO[] $array
	 * @param string $columnName
	 * @param bool $ascending
	 */
	public function sort(array &$array, string $columnName, bool $ascending=true)
	{
		return $this->gdoColumn($columnName)->sort($array, $ascending);
	}
}
function quote(string $value=null)
{
	return GDO::quoteS($value);
}
