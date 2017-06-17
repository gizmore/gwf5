<?php
class GDO_Version extends GDO_Decimal
{
	public $digitsBefore = 1;
	public $digitsAfter = 2;
	
	public function defaultLabel() { return $this->label('version'); }
}
