<?php
trait GWF_MethodAdmin
{
	public function renderNavBar()
	{
		return Module_Admin::instance()->templatePHP('navbar.php');
	}

	public function renderPermTabs()
	{
		return $this->renderNavBar()->add(Module_Admin::instance()->templatePHP('perm_tabs.php'));
	}
}
