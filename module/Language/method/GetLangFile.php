<?php
/**
 * Get I18n data for js app.
 * @author gizmore
 * @since 3.0
 */
final class Language_Translations extends GWF_Method
{
	public function execute()
	{
		$iso = GWF_Trans::$ISO;
		$cache = GWF_Trans::getCache($iso);
		switch ($this->getFormat())
		{
			case 'json': die(json_encode([$iso => $cache]));
			case 'html': default: return new GWF_Response(print_r($cache, true));
		}
	}
}
