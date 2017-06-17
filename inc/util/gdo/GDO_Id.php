<?php
class GDO_Id extends GDO_Int
{
	public $unsigned = true;

	public function defaultLabel() { return $this->label('id'); }
}