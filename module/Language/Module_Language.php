<?php
/**
 * - Selection of supported languages. (DONE)
 * - Ajax methods to retrieve translation (DONE)
 * - GDO_LangSwitch
 * - A table for language corrections
 * - A template for language corrections
 * - I18n statistics
 *  
 * @author gizmore
 * @version 5.0
 * @since 3.0
 */
final class Module_Language extends GWF_Module
{
	public function getConfig()
	{
		return array(
			GDO_Language::make('languages')->all()->multiple()->initial('["'.GWF_LANGUAGE.'"]'),	
		);
	}
	
	/**
	 * Get the supported  languages, GWF_LANGUAGE first.
	 * @return GWF_Language[]
	 */
	public function cfgSupported()
	{
		$supported = [GWF_LANGUAGE => GWF_Language::table()->find(GWF_LANGUAGE)];
		if ($additional = $this->getConfigValue('languages'))
		{
			$supported = array_merge($supported, $additional);
		}
		return $supported;
	}
}
