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
	public function getItemsPerPage() { return Module_GWF::instance()->cfgItemsPerPage(); }
	public function isPaginated() { return true; }
	protected $table;
	
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

	/**
	 * @return int
	 */
	public abstract function getResultCount();

	
	/**
	 * Hook here to custom initialize table object.
	 * @param GWF_Table $gwfTable
	 */
	public function onDecorateTable(GWF_Table $gwfTable) {}
	
	###############
	### Execute ###
	###############
	public function execute()
	{
		return $this->renderTable();
	}
	
	public function renderTable()
	{
		$gwfTable = $this->table = new GWF_Table();
		$gwfTable->method = $this;
		$gwfTable->href($_SERVER['REQUEST_URI']);
		$gwfTable->addFields($this->getHeaders());
		if ($this->isPaginated())
		{
			$gwfTable->paginated($this->getResultCount(), $this->getItemsPerPage());
		}
		$gwfTable->result($this->getResult());
		$this->onDecorateTable($gwfTable);
		return $gwfTable->render();
	}
}
