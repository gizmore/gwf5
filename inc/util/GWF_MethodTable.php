<?php
/**
 * A method that displays a table.
 * 
 * @author gizmore
 * @version 5.0
 * @since 3.0
 */
abstract class GWF_MethodTable extends GWF_Method
{
	public function ipp() { return Module_GWF::instance()->cfgItemsPerPage(); }
	public function isFiltered() { return true; }
	public function isPaginated() { return true; }
	
	################
	### Abstract ###
	################
	/**
	 * @return GDOType[]
	 */
	public abstract function getHeaders();
	
	/**
	 * @return GDOResult
	 */
	public abstract function getResult();
	
	public function createTable(GDO_Table $table) {}

	###############
	### Execute ###
	###############
	public function execute()
	{
		return $this->renderTable();
	}
	
	public function renderTable()
	{
		$table = GDO_Table::make();
		$table->addFields($this->getHeaders());
		$this->createTable($table);
		$table->paginate($this->isPaginated(), $this->ipp());
		$table->filtered($this->isFiltered());
		$table->result($this->getResult());
// 		$table->fetchAs($table->result->table);
		return $table->render();
	}
}
