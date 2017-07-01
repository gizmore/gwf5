<?php
trait GWF_MethodAdmin
{
	public function renderNavBar(string $module=null)
	{
		return Module_Admin::instance()->templatePHP('navbar.php', ['moduleName' => $module]);
	}

	public function renderPermTabs(string $module=null)
	{
		return $this->renderNavBar($module)->add(Module_Admin::instance()->templatePHP('perm_tabs.php'));
	}
}
