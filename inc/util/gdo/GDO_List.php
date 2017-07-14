<?php
/**
 * Similiar to a table, a list displays multiple cards.
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 */
class GDO_List extends GDO_Table
{
	const MODE_CARD = 1;
	const MODE_LIST = 2;
	private $listMode= self::MODE_CARD;
	public function listMode(int $mode)
	{
		$this->listMode = $mode;
	}
	
	public $itemTemplate;
	public function itemTemplate(GDOType $gdoType)
	{
		$this->itemTemplate = $gdoType;
		return $this;
	}
	
	public function getItemTemplate()
	{
		return $this->itemTemplate ? $this->itemTemplate : GDO_GWF::make();
	}
	
	##############
	### Render ###
	##############
	public function renderCell()
	{
		return $this->render()->getHTML();
	}
	
	public function render()
	{
		$template = $this->listMode === self::MODE_CARD ? 'cell/list_card.php' : 'cell/list.php';
		return GWF_Template::mainPHP($template, ['field'=>$this]);
	}
	
	public function initJSON()
	{
		return array(
			'tableName' => $this->getResult()->table->gdoClassName(),
			'pagemenu' => $this->getPageMenu()->initJSON(),
// 			'sortable' => $this->sortable,
// 			'sortableURL' => $this->sortableURL,
		);
	}
	
}
