<?php
/**
 * A bar is either row or column stacked fields.
 * 
 * @author gizmore
 * @version 5.0
 * @see GWF_Navbar
 */
class GDO_Bar extends GDOType
{
	use GWF_Fields;
	
	public $direction = 'row';
	public function direction(string $direction)
	{
		$this->direction = $direction;
		return $this;
	}
	
	public function render()
	{
	    return GWF_Template::mainPHP('cell/bar.php', ['field' => $this]);
	}
	
	public function renderCell()
	{
	    return $this->render()->getHTML();
	    
	}
}