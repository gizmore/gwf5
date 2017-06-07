<?php
class GDO_Gender extends GDO_Char
{
	public function __construct()
	{
		$this->size(1);
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
		return GWF_Template::templateMain('form/gender.php', ['field'=>$this]);
	}

	public function displayMaleLabel() { return GWF_Trans::t('male'); }
	public function displayFemaleLabel() { return GWF_Trans::t('female'); }
	public function displayNoGenderLabel() { return GWF_Trans::t('no_gender'); }
}
