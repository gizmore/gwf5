<?php
/**
 * Can be first element in a @link GWF_Table to show checkmar selection.
 * Table header is select all Tristate.
 * 
 * @author gizmore
 *
 */
final class GDO_RowNum extends GDO_Checkbox
{
	public $name = 'row';
	
	###############################
	### Different filter header ###
	###############################
	public function renderFilter()
	{
		return GWF_Template::mainPHP('filter/rownum.php', ['field'=>$this]);
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/rownum.php', ['field'=>$this]);
		
	}
}
