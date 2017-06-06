<?php
class GDO_Email extends GDO_String
{
	public function render()
	{
		$tVars = array(
			'field' => $this,
		);
		return GWF_Template::templateMain('form/email.php', $tVars);
	}
	
	public function validate($value)
	{
		
	}
}