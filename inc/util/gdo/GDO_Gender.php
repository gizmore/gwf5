<?php
class GDO_Gender extends GDO_Char
{
	public function __construct()
	{
		$this->size(1);
		$this->notNull();
		$this->initial = " ";
	}
	
	public function validate($value)
	{
		return $value === 'm' || $value === 'f' || $value === ' ' ? true : $this->error('err_gender');
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		return GWF_Template::mainPHP('form/gender.php', ['field'=>$this]);
	}

	public function displayMaleLabel() { return t('male'); }
	public function displayFemaleLabel() { return t('female'); }
	public function displayNoGenderLabel() { return t('no_gender'); }

	public function renderCell()
	{
		switch ($this->getGDOVar())
		{
			case 'm': return l('male');
			case 'f': return l('female');
			default: return '';
		}
		
	}
}
