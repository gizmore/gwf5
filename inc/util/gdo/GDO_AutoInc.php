<?php
class GDO_AutoInc extends GDO_Int
{
	public function primary() { return $this; }
	public function isPrimary() { return true; }
	
	##############
	### Render ###
	##############
	public function renderForm()
	{
		$template = new GWF_Template('form/integer.php');
		return $template->render();
	}
	
	
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY";
	}
}
