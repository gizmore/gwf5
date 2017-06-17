<?php
class GDO_Duration extends GDO_Int
{
	public $unsigned = true;

	public function defaultLabel() { return $this->label('duration'); }
}
