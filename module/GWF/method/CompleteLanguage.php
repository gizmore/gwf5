<?php
final class GWF_CompleteLanguage extends GWF_MethodCompletion
{
	public function execute()
	{
		$response = [];
		$q = $this->getSearchTerm();
		$cell = GDO_Language::make('lang_iso');
		foreach (GWF_Language::all() as $iso => $language)
		{
			if ( ($q === '') || ($language->getISO() === $q) ||
				 (mb_stripos($language->displayName(), $q)!==false) ||
				 (mb_stripos($language->displayNameIso('en'), $q)!==false))
			{
				$response[] = array(
					'id' => $iso,
					'text' => $language->displayName(),
					'display' => $cell->gdo($language)->renderCell()->getHTML(),
				);
			}
		}
		die(json_encode($response));
	}
}
