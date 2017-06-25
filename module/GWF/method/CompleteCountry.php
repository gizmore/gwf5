<?php
final class GWF_CompleteCountry extends GWF_MethodCompletion
{
	public function execute()
	{
		$response = [];
		$q = $this->getSearchTerm();
		$cell = GDO_Country::make('lang_iso');
		foreach (GWF_Country::all() as $iso => $country)
		{
			if ( ($q === '') || ($language->getISO() === $q) ||
				(mb_stripos($country->displayName(), $q)!==false) )
			{
				$response[] = array(
					'id' => $iso,
					'text' => $country->displayName(),
					'display' => $cell->gdo($country)->renderCell()->getHTML(),
				);
			}
		}
		die(json_encode($response));
	}
}
