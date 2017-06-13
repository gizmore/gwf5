<?php
class GWF_Country extends GDO
{
	public function gdoColumns()
	{
		return array(
			GDO_Char::make('c_iso')->label('id')->size(2)->ascii()->caseS()->primary(),
			GDO_Int::make('c_population')->unsigned(),
		);
	}
	
	public function getISO() { return $this->getVar('c_iso'); }
	
	public function displayName()
	{
		return t('country_'.$this->getISO());
	}
}
