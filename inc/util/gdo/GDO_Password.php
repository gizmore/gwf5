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
		return new GWF_Password($this->gdo->getVar('user_password'));
	}
	
	public $hashed = false;
	public function hash(bool $hashed=true)
	{
		$this->hashed = $hashed;
		return $this;
	}
	
	public function addFormValue(GWF_Form $form, $value)
	{
		$this->oldValue = $this->getValue();
		$this->value = $this->hashed ? GWF_Password::create($value)->__toString() : $value;
		$form->addValue($this->name, $this->value);
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
