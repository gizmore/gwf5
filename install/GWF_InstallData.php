<?php
require 'data/GWF_LanguageData.php';

final class GWF_InstallData
{
	public static function install()
	{
		foreach (GWF_LanguageData::getLanguages() as $data)
		{
			list($en, $native, $iso3, $iso2) = $data;
			$lang = GWF_Language::blank(['lang_iso' => $iso2])->replace();
		}
		
		foreach (GWF_LanguageData::getCountries() as $data)
		{
			list($name, $langs, $region, $iso2, $pop) = $data;
			$country = GWF_Country::blank(array(
				'c_iso' => strtolower($iso2),
				'c_population' => $pop,
			))->replace();
		}
	}
}
