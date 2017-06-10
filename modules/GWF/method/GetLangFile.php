<?php
final class GWF_GetLangFile extends GWF_Method
{
	public function execute()
	{
		$iso = GWF_Trans::$ISO;
		$cache = GWF_Trans::getCache($iso);
		switch ($this->getFormat())
		{
			case 'json': return new GWF_Response([$iso => $cache]);
			case 'html': default: return new GWF_Response(print_r($cache, true));
		}
	}
	
}
