<?php
class GDO_Email extends GDO_String
{
	public function __construct()
	{
		$this->pattern = "/^[^@]+@[^@]+$/i";
	}
	
	public function render()
	{
		$tVars = array(
			'field' => $this,
		);
		return GWF_Template::templateMain('form/email.php', $tVars);
	}
	
	public function renderCell()
	{
		$email = $this->getGDOVar();
		return GWF_HTML::anchor("mailto:$email", $email);
	}
}