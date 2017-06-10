<?php
class GDO_Decimal extends GDO_Int
{
	public $digitsBefore = 5;
	public $digitsAfter = 5;
	
	
	public function digits(int $before, int $after)
	{
		$this->digitsBefore = $before;
		$this->digitsAfter = $after;
		return $this;
	}
	
	public function gdoColumnDefine()
	{
		$digits = sprintf("%d,%d", $this->digitsBefore + $this->digitsAfter, $this->digitsAfter);
		return "{$this->identifier()} DECIMAL($digits){$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
	
	public function render()
	{
		return GWF_Template::templateMain('form/decimal.php', ['field'=>$this]);
	}
	
	public function getGDOValue()
	{
		return round($this->getValue(), $this->digitsAfter);
	}
}
