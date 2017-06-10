<?php
trait GWF_MethodAdmin
{
	public function renderNavBar()
	{
		$tVars = array();
		return Module_Admin::instance()->template('navbar.php', $tVars);
	}
}