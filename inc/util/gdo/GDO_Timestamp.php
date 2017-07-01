<?php
class GDO_Timestamp extends GDOType
{
	#####################
	### Starting view ###
	#####################
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
	
	###############
	### Min/Max ###
	###############
	public function minAge($duration) { return $this->minTimestamp(time() - $duration); }
	public function maxAge($duration) { return $this->maxTimestamp(time() - $duration); }
	
	public $minDate;
	public function minTimestamp($minTimestamp)
	{
		return $this->minDate(GWF_Time::getDate($minTimestamp));
	}
	public function minDate($minDate)
	{
		$this->minDate = $minDate;		
		return $this;
	}
	
	public $maxDate;
	public function maxTimestamp($maxTimestamp)
	{
		return $this->maxDate(GWF_Time::getDate($maxTimestamp));
	}
	public function maxDate($maxDate)
	{
		$this->maxDate = $maxDate;
		return $this;
	}

	################
	### Validate ###
	################
	public function validate($value)
	{
		if ( ($this->minDate !== null) && ($value < $this->minDate) )
		{
			return $this->error('err_min_date', [$this->name, GWF_Time::displayDate($this->minDate, 'days')]);
		}
		if ( ($this->maxDate !== null) && ($value > $this->maxDate) )
		{
			return $this->error('err_max_date', [$this->name, GWF_Time::displayDate($this->maxDate, 'days')]);
		}
		return parent::validate($value);
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
		return GWF_Time::displayDate($this->gdo->getVar($this->name));
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/time.php', ['field'=>$this]);
	}
}