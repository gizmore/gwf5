<?php
class GWF_Country extends GDO
{
	public function gdoColumns()
	{
		return array(
			GDO_Char::make('c_iso')->label('id')->size(2)->ascii()->caseS()->primary(),
			GDO_Int::make('c_population')->initial('0')->unsigned(),
		);
	}
	
	public function getISO() { return $this->getVar('c_iso'); }
	public function displayName() { return t('country_'.$this->getISO()); }

	/**
	 * Get a country by ID or return a stub object with name "Unknown".
	 * @param int $id
	 * @return GWF_Country
	 */
	public static function getByISOOrUnknown(string $iso=null)
	{
		if (!($country = self::getById($iso)))
		{
			$country = self::blank(['c_iso'=>'zz']);
		}
		return $country;
	}
}
