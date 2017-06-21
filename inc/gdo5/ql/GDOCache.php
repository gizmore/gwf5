<?php
/**
 * GDOCache is a global object cache, where each fetched object (with the same key) from the database results in the same instance.
 * This way you can never have two dangling out of sync users in your application.
 * It also saves a bit mem.
 * Of course this comes with a slight overhead.
 * As GDO5 was written from scratch with this in mind, the overhead is quite small.
 * 
 * New and unplanned is the use of memcached :)
 * 
 * There are a few global memcached keys scattered across the application, fetching all rows.
 * Those GDOs dont use memcached on a per row basis
 * 
 * gwf_modules
 * gwf_country
 * gwf_language
 * 
 * The other memcached keys work on a per row basis with table_name_id as key.
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 * @license MIT
 */
class GDOCache
{
	/**
	 * @var Memcached
	 */
	private static $MEMCACHED;
	public static function get(string $key) { return self::$MEMCACHED->get($key); }
	public static function set(string $key, $value) { self::$MEMCACHED->set($key, $value); }
	public static function unset(string $key) { self::$MEMCACHED->delete($key); }
	public static function flush() { self::$MEMCACHED->flush(); }
	public static function init()
	{
		self::$MEMCACHED = new Memcached();
		self::$MEMCACHED->addServer(GWF_MEMCACHE_HOST, GWF_MEMCACHE_PORT);
	}
	
	#################
	### GDO Cache ###
	#################
	/**
	 * The table object is fine to keep clean?
	 * @var GDO
	 */
	private $table;
	
	/**
	 * Zero alloc, one item dummy queue.
	 * @var GDO
	 */
	private $dummy;
	
	private $klass;
	
	/**
	 * The cache
	 * @var GDO[]
	 */
	private $cache = [];

	public function __construct(GDO $gdo)
	{
		$this->table = $gdo;
		$this->klass = $gdo->gdoClassName();
		$this->newDummy();
	}

	private function newDummy()
	{
		$this->dummy = new $this->klass();
	}
	
	/**
	 * @param string $id
	 * @return GDO
	 */
	public function findCached(string $id)
	{
		if (!isset($this->cache[$id]))
		{
			if ($mcached = self::get($this->klass . $id))
			{
				$this->cache[$id] = $mcached;
			}
		}
		return @$this->cache[$id];
	}
	
	/**
	 * Only GDO Cache / No memcached initializer.
	 * @param array $assoc
	 * @return GDO
	 */
	public function initGDOCached(array $assoc)
	{
		$this->dummy->setGDOVars($assoc);
		$key = $this->dummy->getID();
		if (!isset($this->cache[$key]))
		{
			$this->cache[$key] = $this->dummy->setPersisted();
			$this->newDummy();
		}
		return $this->cache[$key];
	}
	
	public function recache(GDO $object)
	{
		$this->cache[$object->getID()] = $object;
		if ($object->memCached())
		{
			self::$MEMCACHED->set($object->gkey(), $object, GWF_MEMCACHE_TTL);
		}
	}
	
	/**
	 * memcached + gdo cache initializer
	 * @param array $assoc
	 * @return GDO
	 */
	public function initGDOMemcached(array $assoc)
	{
		$this->dummy->setGDOVars($assoc);
		$key = $this->dummy->getID();
		if (!isset($this->cache[$key]))
		{
			$gkey = $this->dummy->gkey();
			if (!($mcached = self::$MEMCACHED->get($gkey)))
			{
				$mcached = $this->dummy->setPersisted();
				self::$MEMCACHED->set($gkey, $mcached, GWF_MEMCACHE_TTL);
				$this->newDummy();
			}
			$this->cache[$key] = $mcached;
		}
		return $this->cache[$key];
	}
}
