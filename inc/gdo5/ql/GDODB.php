<?php
/**
 * 
 * @author gizmore
 */
class GDODB
{
	public static $INSTANCE; 
	public static function instance() { return self::$INSTANCE; }
	
	private $link;
	
	private $debug;
	
	public $reads = 0;
	public $writes = 0;
	public $queries = 0;
	public $queryTime = 0;
	
	public static $READS = 0;
	public static $WRITES = 0;
	public static $QUERIES = 0;
	public static $QUERY_TIME = 0;
	
	/**
	 * @var GDO[]
	 */
	private static $TABLES = array();

	/**
	 * @var GDOType[]
	 */
	private static $COLUMNS = array();
	
	public function __construct(string $host, string $user, string $pass, string $db, $debug=false)
	{
		self::$INSTANCE = $this;
		$this->debug = $debug;
		$this->link = mysqli_connect($host, $user, $pass, $db);
		$this->queryWrite("SET NAMES UTF8");
	}
	
	public function queryRead($query)
	{
		$this->reads++;
		self::$READS++;
		return $this->query($query);
	}
	
	public function queryWrite($query)
	{
		$this->writes++;
		self::$WRITES++;
		return $this->query($query);
	}
	
	private function query($query)
	{
		$this->queries++;
		self::$QUERIES++;
		$t1 = microtime(true);
		if (!($result = mysqli_query($this->link, $query)))
		{
			throw new GWF_Exception("err_db", [mysqli_error($this->link), $query]);
		}
		$t2 = microtime(true);
		$timeTaken = $t2 - $t1;
		$this->queryTime += $timeTaken;
		self::$QUERY_TIME += $timeTaken;
		if ($this->debug)
		{
			$timeTaken = sprintf('%.04f', $timeTaken);
			GWF_Log::log('queries', "#" . self::$QUERIES . ": ($timeTaken) ".$query, GWF_Log::DEBUG);
		}
		return $result;
	}
	
	public function insertId()
	{
		return mysqli_insert_id($this->link);
	}
	
	###################
	### Table cache ###
	###################
	public static function tableS($classname)
	{
		if (!isset(self::$TABLES[$classname]))
		{
			self::$TABLES[$classname] = $gdo = new $classname();
			# TODO: Cache the columns as name => value???
			if (!(self::$COLUMNS[$classname] = $gdo->gdoColumns()))
			{
				throw new GWF_Exception('err_gdo_columns_missing');
			}
			$gdo->initCache();
		}
		return self::$TABLES[$classname];
	}
	
	/**
	 * @param unknown $classname
	 * @return mixed
	 */
	public static function columnsS($classname)
	{
		return self::$COLUMNS[$classname];
	}
	
	####################
	### Table create ###
	####################
	public function createTable(GDO $gdo)
	{
		$columns = array();
		$primary = array();
		
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
		echo "<pre>$query</pre>\n";
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
}