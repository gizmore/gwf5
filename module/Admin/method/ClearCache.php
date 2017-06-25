<?php
final class Admin_ClearCache extends GWF_Method
{
	use GWF_MethodAdmin;
	
	public function execute()
	{
		GDOCache::flush();
		GWF_Hook::call('ClearCache');
		return $this->renderNavBar()->add($this->message('msg_cache_flushed'));
	}
}
