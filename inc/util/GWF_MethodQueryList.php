<?php
/**
 * Abstract class that renders a list.
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 */
abstract class GWF_MethodQueryList extends GWF_MethodQuery
{
	/**
	 * @return GDO
	 */
	public abstract function gdoTable();
	
	public function gdoListMode() { return GDO_List::MODE_LIST; }
	
	public function gdoDecorateList(GDO_List $list) {}
	
	/**
	 * @return GDOQuery
	 */
	public function gdoQuery() { return $this->gdoTable()->select(); }

	/**
	 * @return GDOType[]
	 */
	public function gdoParameters()
	{
		return array(
			GDO_PageMenu::make('page')->initial('1'),
		);
	}
	
	/**
	 * @return GWF_Response
	 */
	public function execute()
	{
		return $this->renderPage();
	}
	
	/**
	 * @return GWF_Response
	 */
	public function renderPage()
	{
		$list = GDO_List::make();
		$list->label('list_'.strtolower(get_called_class()), [$this->getSiteName()]);
		$list->addFields($this->gdoParameters());
		$list->query($this->gdoFilteredQuery());
		$list->listMode($this->gdoListMode());
		$list->paginate();
		$list->href($this->href());
		$this->gdoDecorateList($list);
		return GWF_Response::make($list->renderCell());
	}
}
