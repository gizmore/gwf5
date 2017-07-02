<?php
class GDO_Int extends GDOType
{
	public $min = null;
	public $max = null;
	public $unsigned = false;
	public $bytes = 4;
	public $step = 1;
	
	public function step($step)
	{
		$this->step = $step;
		return $this;
	}
	
	public function getGDOValue()
	{
		return (int)$this->getValue();
	}
	
	public function bytes(int $bytes = 4)
	{
		$this->bytes = $bytes;
		return $this;
	}
	
	public function signed()
	{
		$this->unsigned = false;
		return $this;
	}
	
	public function unsigned()
	{
		$this->unsigned = true;
		$this->min = $this->min <= 0 ? 0 : $this->min;
		return $this;
	}
	
	public function min($min)
	{
		$this->min = $min;
		return $this;
	}
	
	public function max($max)
	{
		$this->max = $max;
		return $this;
	}
	
	public function validate($value)
	{
		if ( ($value === null) && ($this->null) )
		{
			return true;
		}
		
		if ( (($this->min !== null) && ($value < $this->min)) || (($this->max !== null) && ($value > $this->max)) )
		{
			return $this->intError();
		}
		return true;
	}
	
	private function intError()
	{
		if (($this->min !== null) && ($this->max !== null))
		{
			return $this->error('err_int_not_between', [$this->min, $this->max]);
		}
		if ($this->min !== null)
		{
			return $this->error('err_int_too_small', [$this->min]);
		}
		if ($this->max !== null)
		{
			return $this->error('err_int_too_large', [$this->max]);
		}
	}

	public function gdoColumnDefine()
	{
		$unsigned = $this->unsigned ? " UNSIGNED" : "";
		return "{$this->identifier()} {$this->gdoSizeDefine()}INT{$unsigned}{$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
	
	private function gdoSizeDefine()
	{
		switch ($this->bytes)
		{
			case 1: return "TINY";
			case 2: return "MEDIUM";
			case 4: return "";
			case 8: return "BIG";
			default: throw new GWF_Exception('err_int_bytes_length', [$this->bytes]);
		}
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/int.php', ['field'=>$this]);
	}

	public function renderCell()
	{
		return $this->gdo->getVar($this->name);
	}
	
	public function renderFilter()
	{
		return GWF_Template::mainPHP('filter/int.php', ['field'=>$this]);
	}
	
	public function filterQuery(GDOQuery $query)
	{
		if ($filter = $this->filterValue())
		{
			$min = (int)GWF_String::substrTo($filter, '-', $filter);
			$max = (int)GWF_String::substrFrom($filter, '-', $filter);
			$nam = $this->identifier();
			$this->filterQueryCondition($query, "$nam >= $min AND $nam <= $max");
		}
	}
	
	public function htmlClass()
	{
		return sprintf(' class="gdo-num %s"', str_replace('_', '-', strtolower($this->gdoClassName())));
	}
}