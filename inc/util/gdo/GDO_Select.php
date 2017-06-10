<?php
class GDO_Select extends GDO_Combobox
{
	public function validate($value)
	{
		if ( ($this->null) && ($value === $this->emptyValue) )
		{
			return true;
		}
		if (!isset($this->choices[$value]))
		{
			return $this->error('err_invalid_choice');
		}
		return true;
	}
	
}