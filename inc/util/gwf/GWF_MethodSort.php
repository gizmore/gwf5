<?php
/**
 * Ajax adapter that swaps two items using their GDO_Sort column.
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 */
abstract class GWF_MethodSort extends GWF_Method
{
	/**
	 * @return GDO
	 */
	public abstract function gdoSortObjects();
	
	public function canSort(GDO $gdo) { return true; }
	
	############
	### Exec ###
	############
	/**
	 * Method is ajax and always a write / transaction.
	 * {@inheritDoc}
	 * @see GWF_Method::isAlwaysTransactional()
	 */
	public function isAlwaysTransactional() { return true; }

	/**
	 * Force ajax and JSON rendering.
	 * {@inheritDoc}
	 * @see GWF_Method::isAjax()
	 */
	public function isAjax() { return true; }
	
	/**
	 * Find the sort column name and swap item sorting.
	 * {@inheritDoc}
	 * @see GWF_Method::execute()
	 */
	public function execute()
	{
		$table = $this->gdoSortObjects();
		if (!($name = $this->getSortingColumnName($table)))
		{
			return $this->error('err_table_not_sortable', [$table->gdoHumanName()]);
		}
		$a = $table->find(Common::getRequestString('a'));
		$b = $table->find(Common::getRequestString('b'));
		if ( (!$this->canSort($a)) || (!$this->canSort($b)) )
		{
			return $this->error('err_table_not_sortable', [$table->gdoHumanName()]);
		}
		$sortA = $a->getVar($name);
		$sortB = $b->getVar($name);
		$a->saveVar($name, $sortB);
		$b->saveVar($name, $sortA);
		return $this->message('msg_sort_success');
	}
	
	/**
	 * Determine the sort column.
	 * @param GDO $table
	 * @return string
	 */
	protected function getSortingColumnName(GDO $table)
	{
		foreach ($table->gdoColumnsCache() as $gdoType)
		{
			if ($gdoType instanceof GDO_Sort)
			{
				return $gdoType->name;
			}
		}
	}
}
