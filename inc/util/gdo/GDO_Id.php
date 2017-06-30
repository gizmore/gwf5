<?php
class GDO_Id extends GDO_Int
{
	public $unsigned = true;

	public function defaultLabel() { return $this->label('id'); }
	
// 	public function renderCell()
// 	{
// 		return "<div data-drag=true jqyoui-draggable='{animate:true}'>".parent::renderCell()."</div>";
// 	}
}