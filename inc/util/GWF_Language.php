<?php
class GWF_Language extends GDO
{
	public function memCached() { return false; }
	
	public function gdoColumns()
	{
		return array(
			GDO_Char::make('lang_iso')->primary()->size(2),
		);
	}
	
	public function getISO() { return $this->getVar('lang_iso'); }
	public function displayName() { return t('lang_'.$this->getISO()); }
	
	/**
	 * Get a language by ISO or return a stub object with name "Unknown".
	 * @param string $iso
	 * @return GWF_Language
	 */
	public static function getByISOOrUnknown(string $iso=null)
	{
		if (!($language = self::getById($iso)))
		{
			$language = self::blank(['lang_iso'=>'zz']);
		}
		return $language;
	}
	
}
