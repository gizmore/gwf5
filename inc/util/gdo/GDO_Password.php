<?php
/**
 * Bcrypt hash form and database value
 * @author gizmore
 * @since 5.0
 */
class GDO_Password extends GDO_String
{
	public function __construct()
	{
		$this->min = 59;
		$this->max = 60;
		$this->encoding = self::ASCII;
		$this->caseSensitive = true;
	}
	
	public function getGDOValue()
	{
		return new GWF_Password($this->getValue());
	}
	
	public function render()
	{
		return GWF_Template::templateMain('form/password.php', ['field'=>$this]);
	}
	
	public function validate($value)
	{
		return mb_strlen($value) < 4 ? $this->error('err_pass_too_short', [4]) : true;
	}
	
}
