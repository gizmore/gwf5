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
	public function displayNameISO($iso) { return tiso($iso, 'lang_'.$this->getISO()); }
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/language.php', ['language'=>$this]);
	}
	public function renderChoice()
	{
		return GWF_Template::mainPHP('choice/language.php', ['language'=>$this]);
	}
	
	/**
	 * Get a language by ISO or return a stub object with name "Unknown".
	 * @param string $iso
	 * @return GWF_Language
	 */
	public static function getByISOOrUnknown(string $iso=null)
	{
	    if ( ($iso === null) || (!($language = self::getById($iso))) )
		{
			$language = self::blank(['lang_iso'=>'zz']);
		}
		return $language;
	}
	
	/**
	 * @return GWF_Language[]
	 */
	public function allSupported()
	{
		return Module_Language::instance()->cfgSupported();
	}
	
	/**
	 * @return GWF_Language[]
	 */
	public function all()
	{
		if (!($cache = GDOCache::get('gwf_languages')))
		{
// 			$isos = implode(',', array_map('quote', array('en','de','fr')));
			$cache = self::table()->select('*')->exec()->fetchAllArray2dObject();
			GDOCache::set('gwf_languages', $cache);
		}
		return $cache;
	}
	
	
}
