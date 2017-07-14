<?php
class GDO_Email extends GDO_String
{
	public $pattern = "/^[^@]+@[^@]+$/i";
	
	public function defaultLabel() { return $this->label('email'); }
	
	public function render()
	{
		return GWF_Template::mainPHP('form/email.php', ['field'=>$this]);
	}
	
	public function renderCell()
	{
		if ($email = $this->getGDOVar())
		{
			return GWF_HTML::anchor("mailto:$email", $email);
		}
	}
}