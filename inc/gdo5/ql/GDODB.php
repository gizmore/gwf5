<?php
/**
 * mysqli abstraction.
 * 
 * @author gizmore
 * @version 5.0
 */
class GDODB
{
	public static $INSTANCE;
	/**
	 * @return GDODB
	 */
	public static function instance() { return self::$INSTANCE; }
	
	# Connection
	private $link, $host, $user, $pass, $db, $debug;
	
	# Timing
	public $reads = 0;
	public $writes = 0;
	public $commits = 0;
	public $queries = 0;
	public $queryTime = 0;
	
	public static $READS = 0;
	public static $WRITES = 0;
	public static $COMMITS = 0;
	public static $QUERIES = 0;
	public static $QUERY_TIME = 0;
	
	/**
	 * @var GDO[]
	 */
	private static $TABLES = [];

	/**
	 * @var GDOType[]
	 */
	private static $COLUMNS = [];
	
	public function __construct(string $host, string $user, string $pass, string $db, bool $debug=false)
	{
		self::$INSTANCE = $this;
		$this->debug = $debug;
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
	}
	
	public function getLink()
	{
		if (!$this->link)
		{
			$t1 = microtime(true);
			$this->link = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
			$timeTaken = microtime(true) - $t1;
			$this->queryTime += $timeTaken; self::$QUERY_TIME += $timeTaken;
			$this->query("SET NAMES UTF8");
		}
		return $this->link;
	}

	#############
	### Query ###
	#############
	public function queryRead($query)
	{
		$this->reads++; self::$READS++;
		return $this->query($query);
	}
	
	public function queryWrite($query)
	{
		$this->writes++; self::$WRITES++;
		return $this->query($query);
	}
	
	private function query($query)
	{
		$this->queries++; self::$QUERIES++;
		$t1 = microtime(true);
		if (!($result = mysqli_query($this->getLink(), $query)))
		{
			throw new GWF_Exception("err_db", [mysqli_error($this->link), htmlspecialchars($query)]);
		}
		$t2 = microtime(true);
		$timeTaken = $t2 - $t1;
		$this->queryTime += $timeTaken; self::$QUERY_TIME += $timeTaken;
		if ($this->debug)
		{
			$timeTaken = sprintf('%.04f', $timeTaken);
			printf("<!- #%d took %ss : %s -->\n", self::$QUERIES, $timeTaken, htmlspecialchars($query));
			GWF_Log::log('queries', "#" . self::$QUERIES . ": ({$timeTaken}s) ".$query, GWF_Log::DEBUG);
		}
		return $result;
	}
	
	public function insertId()
	{
		return mysqli_insert_id($this->getLink());
	}
	
	public function affectedRows()
	{
		return mysqli_affected_rows($this->getLink());
	}
	
	###################
	### Table cache ###
	###################
	/**
	 * @param string $classname
	 * @throws GWF_Exception
	 * @return GDO
	 */
	public static function tableS(string $classname)
	{
		if (!isset(self::$TABLES[$classname]))
		{
			self::$TABLES[$classname] = $gdo = new $classname();
			self::$COLUMNS[$classname] = self::hashedColumns($gdo->gdoColumns());
			$gdo instanceof GDO;
			if ($gdo->gdoCached())
			{
				$gdo->initCache();
			}
		}
		return self::$TABLES[$classname];
	}
	
	/**
	 * Extract name from gdo columns for hashmap.
	 * @param GDOType[] $gdoColumns
	 * @return GDOType[]
	 */
	private static function hashedColumns(array $gdoColumns)
	{
		$columns = [];
		foreach ($gdoColumns as $gdoType)
		{
			$columns[$gdoType->name] = $gdoType;
		}
		return $columns;
	}
	
	/**
	 * @param string $classname
	 * @return GDOType[]
	 */
	public static function columnsS(string $classname)
	{
		return self::$COLUMNS[$classname];
	}
	
	####################
	### Table create ###
	####################
	/**
	 * Create a database table from a GDO. 
	 * @param GDO $gdo
	 * @return bool
	 */
	public function createTable(GDO $gdo)
	{
		$columns = [];
		$primary = [];
		
		foreach ($gdo->gdoColumnsCache() as $colNr => $column)
		{
			if ($define = $column->gdoColumnDefine())
			{
				$columns[] = $define;
			}
			if ($column->primary)
			{
				$primary[] = $column->identifier();
			}
		}
		
		if (count($primary))
		{
			$primary = implode(',', $primary);
			$columns[] = "PRIMARY KEY ($primary)";
		}
		
		$columns = implode(",\n", $columns);
		
		$query = "CREATE TABLE IF NOT EXISTS {$gdo->gdoTableIdentifier()} (\n$columns\n) ENGINE = {$gdo->gdoEngine()}";
		
		if ($this->debug)
		{
			printf("<pre>%s</pre>\n", htmlspecialchars($query));
		}
		return $this->queryWrite($query);
	}
	
	public function dropTable(GDO $gdo)
	{
		return $this->queryWrite("DROP TABLE IF EXISTS {$gdo->gdoTableIdentifier()}");
	}
	
	public function truncateTable(GDO $gdo)
	{
		return $this->queryWrite("TRUNCATE TABLE IF EXISTS {$gdo->gdoTableIdentifier()}");
	}
	
	###################
	### Transaction ###
	###################
	public function transactionBegin()
	{
		return mysqli_begin_transaction($this->getLink());
	}
	
	public function transactionEnd()
	{
		$this->commits++; self::$COMMITS++;
		$t1 = microtime(true);
		$result = mysqli_commit($this->getLink());
		$t2 = microtime(true);
		$tt = $t2 - $t1;
		$this->queryTime += $tt; self::$QUERY_TIME += $tt;
		return $result;
	}
	
	public function transactionRollback()
	{
		return mysqli_rollback($this->getLink());
	}
}
