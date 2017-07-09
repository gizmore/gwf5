<?php
class GDO_Decimal extends GDO_Int
{
	public $digitsBefore = 5;
	public $digitsAfter = 5;
	
	
	public function digits(int $before, int $after)
	{
		$this->digitsBefore = $before;
		$this->digitsAfter = $after;
		$step = $after < 1 ? 1 : floatval('0.'.str_repeat('0', $after-1).'1');
		return $this->step($step);
	}
	
	public function gdoColumnDefine()
	{
		$digits = sprintf("%d,%d", $this->digitsBefore + $this->digitsAfter, $this->digitsAfter);
		return "{$this->identifier()} DECIMAL($digits){$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/decimal.php', ['field'=>$this]);
	}
	
	public function getGDOValue()
	{
		return round($this->getValue(), $this->digitsAfter);
	}
}
