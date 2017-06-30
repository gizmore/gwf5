<?php
class GDO_Sort extends GDO_Int
{
	public function __construct()
	{
		$this->min = 0;
		$this->max = 65535;
		$this->bytes = 2;
		$this->unsigned();
		$this->label('sorting');
	}
	
	public function gdoAfterCreate()
	{
		$this->gdo->saveVar($this->name, $this->gdo->getID());
	}

}
