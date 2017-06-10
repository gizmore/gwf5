<?php
/**
 * 
 * @author gizmore
 *
 */
class GDO_Label extends GDO_Blank
{
	
	public function render()
	{
		return GWF_Template::mainPHP('form/label.php', ['field'=>$this]);
	}
	
}