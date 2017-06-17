<?php
/**
 * A method that displays a table.
 * 
 * @author gizmore
 * @version 5.0
 * @since 3.0
 */
abstract class GWF_MethodQueryTable extends GWF_MethodTable
{
	################
	### Abstract ###
	################
	/**
	 * @return GDO
	 */
	public abstract function getGDO();
	
	/**
	 * @var GDO
	 */
	protected $gdo;
	
	/**
	 * @var GDOQuery
	 */
	protected $query;
	
	public function filterQuery(GDOQuery $query)
	{
		foreach ($this->table->getFields() as $gdoType)
		{
			$gdoType->filterQuery($query);
		}
		return $query;
	}
	
	###############
	### Execute ###
	###############
	public function getResultCount()
	{
		return (int)$this->getQuery()->select('COUNT(*)')->exec()->fetchValue();
	}
	
	/**
	 * @return GDOQuery
	 */
	public function getQuery()
	{
		return $this->filterQuery($this->gdo->query()->fromSelf());
	}
	
	/**
	 * @return GDOQuery
	 */
	public function getQueryPaginated()
	{
		$start = $this->table->pagemenu->getFrom();
		return $this->getQuery()->limit($this->getItemsPerPage(), $start);
	}
	
	public function execute()
	{
		$this->gdo = $this->getGDO();
		return parent::execute();
	}
}