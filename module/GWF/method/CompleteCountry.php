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
			if ( ($q === '') || ($country->getISO() === $q) ||
				(mb_stripos($country->displayName(), $q)!==false) )
			{
				$response[] = array(
					'id' => $iso,
					'text' => $country->displayName(),
					'display' => $country->renderCell(),
				);
			}
		}
		die(json_encode($response));
	}
}
