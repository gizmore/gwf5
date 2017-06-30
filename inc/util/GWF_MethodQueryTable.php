<?php
/**
 * A method that displays a table.
 * 
 * @author gizmore
 * @version 5.0
 * @since 3.0
 */
abstract class GWF_MethodQueryTable extends GWF_Method
{
	public function ipp() { return Module_GWF::instance()->cfgItemsPerPage(); }
	public function isFiltered() { return true; }
	public function isPaginated() { return true; }
	
	################
	### Abstract ###
	################
	/**
	 * @return GDOQuery
	 */
	public abstract function getQuery();
	
	/**
	 * @return GDOType[]
	 */
	public abstract function getHeaders();
	
	public function onDecorateTable(GDO_Table $table) {}
	
	############
	### Exec ###
	############
	public function execute()
	{
		$table = GDO_Table::make();
		$table->addFields($this->getHeaders());
		$table->href($this->href());
		$table->query($this->getQuery());
		$table->gdo($table->query->table);
		$table->paginate($this->isPaginated(), $this->ipp());
		$table->filtered($this->isFiltered());
		$this->onDecorateTable($table);
		return $table->render();
	}
}
