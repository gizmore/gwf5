<?php
trait GWF_MethodAdmin
{
	public function renderNavBar()
	{
		$tVars = array();
		return Module_Admin::instance()->templatePHP('navbar.php', $tVars);
	}
}