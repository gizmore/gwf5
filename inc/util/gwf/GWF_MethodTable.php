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
	public function isOrdered() { return true; }
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
	 * @return GDOArrayResult
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
		$table->ordered($this->isOrdered());
		$table->filtered($this->isFiltered());
		$table->paginate($this->isPaginated(), $this->ipp());
		
		$result = $this->getResult();
		foreach (array_reverse(Common::getRequestArray('o'), true) as $name => $asc)
		{
			if ($gdoType = $table->getField($name))
			{
				$result->data = $gdoType->sort($result->data, !!$asc);
			}
		}
		$result->data = array_values($result->data);
		$table->result($result);
		
// 		$table->fetchAs($table->result->table);
		return $table->render();
	}
}
