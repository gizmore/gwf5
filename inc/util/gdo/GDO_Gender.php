<?php
class GDO_Gender extends GDO_Char
{
	public function __construct()
	{
		$this->size(1);
		$this->initial("0");
		$this->label('gender');
	}
	
	public function validate($value)
	{
		return $value === 'm' || $value === 'f' || $value === "0" ? true : $this->error('err_gender');
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		return GWF_Template::mainPHP('form/gender.php', ['field'=>$this]);
	}

	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/gender.php', ['field'=>$this]);
	}
}
