<?php
class GDO_Validator extends GDOType
{
	public function blankData() {}
	public function addFormValue(GWF_Form $form, $value) {}
	
	public function render()
	{
		$tVars = array(
			'field' => $this,
		);
		return GWF_Template::mainPHP('form/validator.php', $tVars);
	}
}
