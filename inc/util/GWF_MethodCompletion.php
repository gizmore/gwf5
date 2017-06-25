<?php
abstract class GWF_MethodCompletion extends GWF_Method
{
	public function getMaxSuggestions() { return Module_GWF::instance()->cfgMaxSuggestions(); }
	public function getSearchTerm() { return Common::getRequestString('query'); }
}
