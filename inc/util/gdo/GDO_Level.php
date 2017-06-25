<?php
final class GDO_Level extends GDO_Int
{
	public $unsigned = true;
	public function defaultLabel() { return $this->label('level'); }
}
