<?php
class GDOCache
{
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
	
	/**
	 * The cache
	 * @var GDO[]
	 */
	private $cache = [];

	public function __construct(GDO $gdo)
	{
		$this->table = $gdo;
		$this->newDummy();
	}

	private function newDummy()
	{
		$class = $this->table->gdoClassName();
		$this->dummy = new $class();
	}
	
	/**
	 * @param string $id
	 * @return GDO
	 */
	public function findCached(string $id)
	{
		return @$this->cache[$id];
	}
	
	/**
	 * @param array $assoc
	 * @return GDO
	 */
	public function initCached(array $assoc)
	{
		$this->dummy->setGDOVars($assoc);
		$key = $this->dummy->getID();
		if (!isset($this->cache[$key]))
		{
			$this->cache[$key] = $this->dummy->dirty(false)->setPersisted();
			$this->newDummy();
		}
		return $this->cache[$key];
	}
}
