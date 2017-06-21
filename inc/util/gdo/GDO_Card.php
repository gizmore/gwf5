<?php
/**
 * Similiar to a box, a card renders only for cell, with title and content.
 * It adds actions to the GDOType.
 * @author gizmore
 */
final class GDO_Card extends GDO_Box
{
	/**
	 * @var GDO_Button[]
	 */
	private $actions = [];
	
	/**
	 * @return GDO_Button[]
	 */
	public function getActions()
	{
		return $this->actions;
	}
	
	public function action(GDO_Button $action)
	{
		$this->actions[] = $action;
		return $this;
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('cell/card.php', ['field'=>$this]);
	}
	
}
