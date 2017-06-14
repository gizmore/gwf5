<?php
class GDO_Email extends GDO_String
{
	public function __construct()
	{
		$this->pattern = "/^[^@]+@[^@]+$/i";
		$this->label('email');
	}
	
	public function render()
	{
		$tVars = array(
			'field' => $this,
		);
		return GWF_Template::mainPHP('form/email.php', $tVars);
	}
	
	public function renderCell()
	{
		$email = $this->getGDOVar();
		return GWF_HTML::anchor("mailto:$email", $email);
	}
}