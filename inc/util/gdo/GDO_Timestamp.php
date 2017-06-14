<?php
class GDO_Timestamp extends GDOType
{
	public $dateStartView = 'month';
	public function startWithYear()
	{
		$this->dateStartView  = 'year';
		return $this;
	}
	public function startWithMonth()
	{
		$this->dateStartView  = 'month';
		return $this;
	}
	
	public $minDate;
	public function minDate($minDate)
	{
		$this->minDate = $minDate;
		return $this;
	}
	
	public $maxDate;
	public function maxDate($maxDate)
	{
		$this->maxDate = $maxDate;
		return $this;
	}
	
	##############
	### Column ###
	##############
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} TIMESTAMP{$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
	
	##############
	### Render ###
	##############
	public function renderCell()
	{
		return $this->gdo->getVar($this->name);
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/time.php', ['field'=>$this]);
	}
}