<?php
class GDO_CreatedAt extends GDO_Time
{
	public function blankData()
	{
		return [$this->name => time()];
	}
}
