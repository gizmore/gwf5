<?php
/**
 * A Date is like a Datetime, but a bit older, so we start with year selection.
 * An example is the release date of a book, or a birthdate.
 * 
 * @see GWF_Time for conversion
 * @see GDO_DateTime
 * @see GDO_Time
 * @see GDO_Timestamp
 * 
 * @author gizmore
 * @version 5.0
 * @since 5.0
 */
class GDO_Date extends GDO_Timestamp
{
	public $dateStartView = 'year';
	
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} DATE {$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}

	public function renderCell()
	{
		return GWF_Time::displayDate($this->gdo->getVar($this->name));
	}

}
