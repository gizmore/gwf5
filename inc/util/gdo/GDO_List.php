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
	##############
	### Render ###
	##############
	public function renderCell()
	{
		return $this->render()->getHTML();
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('cell/list.php', ['field'=>$this]);
	}
	
	public function initJSON()
	{
		return json_encode(array(
			'tableName' => $this->result->table->gdoClassName(),
			'sortable' => $this->sortable,
			'sortableURL' => $this->sortableURL,
		));
	}
	
}
