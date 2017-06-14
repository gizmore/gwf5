<?php
class GWF_Language extends GDO
{
	public function gdoColumns()
	{
		return array(
			GDO_Char::make('lang_iso')->primary()->size(2),
		);
	}
	
	public function getISO() { return $this->getVar('lang_iso'); }
	public function displayName() { return t('lang_'.$this->getISO()); }
}
