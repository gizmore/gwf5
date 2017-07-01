<?php
final class Admin_ClearCache extends GWF_Method
{
	use GWF_MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		GDOCache::flush();
		GWF_Hook::call('ClearCache');
		GWF_File::removeDir(GWF_Minify::tempDirS());
		return $this->renderNavBar()->add($this->message('msg_cache_flushed'));
	}
}
