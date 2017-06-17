<?php
class GDO_Validator extends GDO_Blank
{
	public function render()
	{
		$tVars = array(
			'field' => $this,
		);
		return GWF_Template::mainPHP('form/validator.php', $tVars);
	}
}
