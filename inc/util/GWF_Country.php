<?php
class GWF_Country extends GDO
{
	public function memCached() { return false; }
	
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
	
	/**
	 * @return GWF_Country[]
	 */
	public function all()
	{
		if (!($cache = GDOCache::get('gwf_country')))
		{
			$cache = self::table()->select('*')->exec()->fetchAllArray2dObject();
			GDOCache::set('gwf_country', $cache);
		}
		return $cache;
	}

	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/country.php', ['field'=>$this, 'country' => $this, $choice => false])->getHTML();
	}

	public function renderChoice()
	{
		return GWF_Template::mainPHP('choice/country.php', ['field'=>$this, 'country' => $this, $choice => true])->getHTML();
	}
}
